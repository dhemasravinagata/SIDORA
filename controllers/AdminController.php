<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pendonor.php';
require_once __DIR__ . '/../models/JadwalDonor.php';
require_once __DIR__ . '/../models/StokDarah.php';
require_once __DIR__ . '/../models/RiwayatDonasi.php';
require_once __DIR__ . '/../models/PermintaanDarah.php';

use Core\Logger as Log;
use Core\Validator;

class AdminController
{
    private $userModel;
    private $pendonorModel;
    private $jadwalModel;
    private $stokModel;
    private $permintaanModel;
    private $riwayatModel;

    public function __construct()
    {
        $this->userModel       = new User();
        $this->pendonorModel   = new Pendonor();
        $this->jadwalModel     = new JadwalDonor();
        $this->stokModel       = new StokDarah();
        $this->permintaanModel = new PermintaanDarah();
        $this->riwayatModel    = new RiwayatDonasi();
    }

    private function csvDateText($value)
    {
        return $value ? "\t" . $value : '';
    }

    private function requireAdmin()
    {
        if (!isset($_SESSION['user'])) {
            flash('warning', 'Silakan login terlebih dahulu untuk mengakses menu Administrator.');
            redirect('index.php?page=login');
        }

        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Log::warning("Akses ditolak ke menu admin untuk pengguna: " . ($_SESSION['user']['username'] ?? 'unknown') . " (role: " . ($_SESSION['user']['role'] ?? '-') . ")");
            abort(403, 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Administrator.');
        }
    }

    public function dashboard()
    {
        $this->requireAdmin();
        $stokList = $this->stokModel->getAll();
        $stokTotal = array_reduce($stokList, function($carry, $item) {
            return $carry + (int)$item['jumlah'];
        }, 0);

        $permintaanAll = $this->permintaanModel->getAll();
        $permintaanPending = count(array_filter($permintaanAll, function($p) {
            return ($p['status'] ?? '') === 'Pending';
        }));

        $users = $this->userModel->getAll();
        $petugasCount = count(array_filter($users, fn($u) => ($u['role'] ?? '') === 'petugas'));
        $rsCount = count(array_filter($users, fn($u) => ($u['role'] ?? '') === 'rumahsakit'));

        $statistics = [
            'pendonor'   => count($this->pendonorModel->getAll()),
            'stok_total' => $stokTotal,
            'permintaan' => $permintaanPending,
            'jadwal'     => count($this->jadwalModel->getAll()),
            'petugas'    => $petugasCount,
            'rumahsakit' => $rsCount,
        ];

        $permintaan  = $this->permintaanModel->getAllWithUser();
        $stokDarah   = $stokList;

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function stokDarah()
    {
        $this->requireAdmin();
        $stokList = $this->stokModel->getAll();

        $stokTotal = 0;
        $stokAman = 0;
        $stokRendah = 0;
        $stokKritis = 0;

        $stockMap = [];
        foreach ($stokList as $stok) {
            $g = strtoupper($stok['golongan'] ?? $stok['golongan_darah'] ?? '');
            $r = $stok['rhesus'] ?? $stok['rh'] ?? '';
            $qty = (int)($stok['quantity'] ?? $stok['jumlah'] ?? 0);
            $stockMap[$g . $r] = $qty;
            $stokTotal += $qty;
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodTypes as $bt) {
            $qty = $stockMap[$bt] ?? 0;
            if ($qty >= 50) {
                $stokAman++;
            } elseif ($qty >= 30) {
                $stokRendah++;
            } else {
                $stokKritis++;
            }
        }

        $statistics = [
            'stok_total'  => $stokTotal,
            'stok_aman'   => $stokAman,
            'stok_rendah' => $stokRendah,
            'stok_kritis' => $stokKritis,
        ];
        $stokHistory = $this->stokModel->getHistoryLast7Days();

        require __DIR__ . '/../views/admin/stok-darah.php';
    }

    public function alertStok()
    {
        $this->requireAdmin();
        Log::info("Admin mengecek dan mencatat alert stok darah kritis.");
        flash('success', 'Peringatan status stok kritis berhasil diverifikasi.');
        redirect('index.php?page=admin-stok-darah');
    }

    public function pesanStok()
    {
        $this->requireAdmin();

        $validator = Validator::make($_POST, [
            'golongan' => 'required',
            'jumlah'   => 'required|numeric|min:1',
        ], [
            'golongan.required' => 'Golongan darah wajib dipilih.',
            'jumlah.required'   => 'Jumlah kantong darah wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            redirect('index.php?page=admin-stok-darah');
        }

        $golongan = trim($_POST['golongan']);
        $jumlah   = intval($_POST['jumlah']);

        Log::info("Admin memesan penambahan stok darah {$golongan} sebanyak {$jumlah} kantong");
        flash('success', "Permintaan tambahan stok golongan {$golongan} sebanyak {$jumlah} kantong berhasil dicatat.");
        redirect('index.php?page=admin-stok-darah');
    }

    public function permintaanDarah()
    {
        $this->requireAdmin();
        $permintaan = $this->permintaanModel->getAllWithUser();
        require __DIR__ . '/../views/admin/permintaan-darah.php';
    }

    public function terimaPermintaan()
    {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        abort_unless($id > 0, 404, 'Permintaan darah tidak valid.');

        $permintaan = $this->permintaanModel->getById($id);
        if (!$permintaan) {
            flash('error', 'Permintaan darah tidak ditemukan.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        if (($permintaan['status'] ?? '') !== 'Pending') {
            flash('warning', 'Hanya permintaan berstatus Pending yang dapat disetujui.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        $details = $this->permintaanModel->getDetails($id);
        if (empty($details)) {
            flash('error', 'Detail permintaan darah kosong.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        $stokCukup = true;
        foreach ($details as $det) {
            if (!$this->stokModel->isStockAvailable($det['golongan'], $det['rhesus'], $det['jumlah'])) {
                $stokCukup = false;
                break;
            }
        }

        if (!$stokCukup) {
            Log::warning("Stok darah tidak mencukupi untuk memenuhi permintaan ID #{$id}");
            flash('error', 'Stok darah tidak mencukupi untuk memenuhi permintaan ini.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        if ($this->permintaanModel->approve($id)) {
            foreach ($details as $det) {
                $this->stokModel->reduceStock(
                    $det['golongan'],
                    $det['rhesus'],
                    $det['jumlah'],
                    "Persetujuan permintaan darah #REQ-{$id}"
                );
            }
            Log::info("Admin menyetujui permintaan darah #REQ-{$id} untuk RS: " . ($permintaan['rumah_sakit'] ?? '-'));
            flash('success', "Permintaan darah #REQ-{$id} berhasil disetujui. Stok darah telah dialokasikan.");
        } else {
            Log::error("Gagal menyetujui permintaan darah #REQ-{$id}");
            flash('error', 'Gagal menyetujui permintaan darah.');
        }

        redirect('index.php?page=admin-permintaan-darah');
    }

    public function tolakPermintaan()
    {
        $this->requireAdmin();

        $validator = Validator::make($_POST, [
            'permintaan_id' => 'required|numeric',
            'alasan'        => 'required|min:4|max:255',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi untuk transparansi ke rumah sakit.',
            'alasan.min'      => 'Alasan penolakan minimal 4 karakter.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            redirect('index.php?page=admin-permintaan-darah');
        }

        $id     = intval($_POST['permintaan_id']);
        $alasan = trim($_POST['alasan']);

        $permintaan = $this->permintaanModel->getById($id);
        if (!$permintaan) {
            flash('error', 'Permintaan darah tidak ditemukan.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        if ($this->permintaanModel->reject($id, $alasan)) {
            Log::info("Admin menolak permintaan darah #REQ-{$id}. Alasan: {$alasan}");
            flash('info', "Permintaan darah #REQ-{$id} telah ditolak.");
        } else {
            flash('error', 'Gagal memproses penolakan permintaan.');
        }

        redirect('index.php?page=admin-permintaan-darah');
    }

    public function kirimPermintaan()
    {
        $this->requireAdmin();

        $validator = Validator::make($_POST, [
            'permintaan_id' => 'required|numeric',
            'kurir'         => 'required|min:3|max:100',
        ], [
            'kurir.required' => 'Nama kurir / ekspedisi pengantar darah wajib diisi.',
            'kurir.min'      => 'Nama kurir minimal 3 karakter.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            redirect('index.php?page=admin-permintaan-darah');
        }

        $id    = intval($_POST['permintaan_id']);
        $kurir = trim($_POST['kurir']);

        $permintaan = $this->permintaanModel->getById($id);
        if (!$permintaan) {
            flash('error', 'Permintaan darah tidak ditemukan.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        if (($permintaan['status'] ?? '') !== 'Disetujui') {
            flash('warning', 'Permintaan hanya dapat dikirim jika sudah berstatus Disetujui.');
            redirect('index.php?page=admin-permintaan-darah');
        }

        if ($this->permintaanModel->markAsSent($id, $kurir)) {
            Log::info("Admin memperbarui pengiriman permintaan darah #REQ-{$id} dengan kurir: {$kurir}");
            flash('success', "Permintaan #REQ-{$id} telah ditandai sedang dikirim via {$kurir}.");
        } else {
            flash('error', 'Gagal memperbarui status pengiriman.');
        }

        redirect('index.php?page=admin-permintaan-darah');
    }

    public function permintaanKirim()   { return $this->kirimPermintaan(); }
    public function prosesPermintaan()  { return $this->tolakPermintaan(); }
    public function permintaanProcess() { return $this->tolakPermintaan(); }

    public function kelolaPetugas()
    {
        $this->requireAdmin();
        $users = $this->userModel->getAll();
        $petugasList = array_values(array_filter($users, fn($user) => ($user['role'] ?? '') === 'petugas'));
        $rumahSakitList = array_values(array_filter(
            $users,
            fn($user) => ($user['role'] ?? '') === 'rumahsakit' && ($user['status'] ?? '') !== 'ditolak'
        ));
        require __DIR__ . '/../views/admin/kelola-petugas.php';
    }

    public function formPetugas()
    {
        $this->requireAdmin();
        require __DIR__ . '/../views/admin/form-petugas.php';
    }

    public function tambahPetugasProcess()
    {
        $this->requireAdmin();

        $validator = Validator::make($_POST, [
            'nama'     => 'required|min:3|max:150',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|min:3|max:50|unique:users,username',
            'password' => 'required|min:6',
            'telepon'  => 'nullable|min:6|max:20',
        ], [
            'nama.required'     => 'Nama petugas wajib diisi.',
            'email.required'    => 'Email petugas wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'username.required' => 'Username petugas wajib diisi.',
            'username.unique'   => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=admin-kelola-petugas');
        }

        $nama     = trim($_POST['nama']);
        $email    = trim($_POST['email']);
        $telepon  = trim($_POST['telepon'] ?? '');
        $status   = $_POST['status'] ?? 'aktif';
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $created = $this->userModel->createPetugas([
            'name'     => $nama,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'petugas',
            'status'   => $status,
            'telepon'  => $telepon,
            'username' => $username,
        ]);

        if ($created) {
            Log::info("Admin menambahkan petugas baru: {$nama} ({$username})");
            flash('success', "Akun petugas '{$nama}' berhasil ditambahkan.");
        } else {
            Log::error("Gagal menambahkan akun petugas: {$nama}");
            flash('error', 'Gagal menambahkan akun petugas baru.');
        }

        redirect('index.php?page=admin-kelola-petugas');
    }

    public function editPetugasProcess()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? 0);
        abort_unless($id > 0, 404, 'Petugas tidak ditemukan.');

        $validator = Validator::make($_POST, [
            'nama'     => 'required|min:3|max:150',
            'email'    => "required|email|unique:users,email,{$id}",
            'username' => "required|min:3|max:50|unique:users,username,{$id}",
            'telepon'  => 'nullable|min:6|max:20',
        ], [
            'nama.required'     => 'Nama petugas wajib diisi.',
            'email.required'    => 'Email petugas wajib diisi.',
            'email.unique'      => 'Email sudah digunakan akun lain.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan akun lain.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=admin-kelola-petugas');
        }

        $nama     = trim($_POST['nama']);
        $email    = trim($_POST['email']);
        $telepon  = trim($_POST['telepon'] ?? '');
        $status   = $_POST['status'] ?? 'aktif';
        $username = trim($_POST['username']);
        $password = trim($_POST['password'] ?? '');

        $data = [
            'name'     => $nama,
            'email'    => $email,
            'telepon'  => $telepon,
            'status'   => $status,
            'username' => $username,
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->updatePetugas($id, $data);
        Log::info("Admin memperbarui data petugas ID #{$id} ({$nama})");
        flash('success', "Data petugas '{$nama}' berhasil diperbarui.");
        redirect('index.php?page=admin-kelola-petugas');
    }

    public function hapusPetugasProcess()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id) {
            $user = $this->userModel->findById($id);
            $this->userModel->deletePetugas($id);
            Log::info("Admin menghapus akun petugas ID #{$id} (" . ($user['name'] ?? '-') . ")");
            flash('success', 'Akun petugas berhasil dihapus dari sistem.');
        } else {
            flash('error', 'ID petugas tidak valid.');
        }
        redirect('index.php?page=admin-kelola-petugas');
    }

    public function hapusRsProcess()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id) {
            $rs = $this->userModel->findById($id);
            $this->userModel->deleteRumahSakit($id);
            Log::info("Admin menghapus akun Rumah Sakit ID #{$id} (" . ($rs['name'] ?? '-') . ")");
            flash('success', 'Akun rumah sakit berhasil dihapus.');
        } else {
            flash('error', 'ID rumah sakit tidak valid.');
        }

        redirect('index.php?page=admin-kelola-petugas');
    }

    public function approveRs()
    {
        $this->requireAdmin();
        $id     = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? null;

        if ($id && in_array($status, ['aktif', 'ditolak', 'nonaktif', 'pending'], true)) {
            $user = $this->userModel->findById($id);
            if (!$user || ($user['role'] ?? '') !== 'rumahsakit') {
                flash('error', 'Data rumah sakit tidak ditemukan.');
            } elseif ($this->userModel->updateStatusByRole($id, $status, 'rumahsakit')) {
                Log::info("Admin memperbarui status Rumah Sakit #{$id} ({$user['name']}) menjadi {$status}");
                flash('success', "Status akun rumah sakit '{$user['name']}' berhasil diperbarui menjadi '{$status}'.");
            } else {
                flash('error', 'Gagal memperbarui status rumah sakit.');
            }
        } else {
            flash('error', 'Parameter verifikasi status tidak valid.');
        }

        redirect('index.php?page=admin-kelola-petugas');
    }

    public function jadwalDonor()
    {
        $this->requireAdmin();
        $jadwalList = $this->jadwalModel->getAll();

        $currentMonth = date('Y-m');
        $jadwalBulanIni = 0;
        $totalPeserta = 0;
        
        foreach ($jadwalList as $j) {
            if (strpos($j['tanggal'] ?? '', $currentMonth) === 0) {
                $jadwalBulanIni++;
            }
            $totalPeserta += intval($j['terdaftar'] ?? 0);
        }

        $statistics = [
            'jadwal'       => count($jadwalList),
            'jadwal_bulan' => $jadwalBulanIni,
            'peserta'      => $totalPeserta
        ];

        require __DIR__ . '/../views/admin/jadwal-donor.php';
    }

    public function formJadwal()
    {
        $this->requireAdmin();
        require __DIR__ . '/../views/admin/form-jadwal.php';
    }

    public function formJadwalProcess()
    {
        $this->requireAdmin();

        $validator = Validator::make($_POST, [
            'lokasi'  => 'required|min:3|max:200',
            'tanggal' => 'required|date',
            'target'  => 'required|numeric|min:1',
        ], [
            'lokasi.required'  => 'Lokasi kegiatan donor darah wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan donor darah wajib diisi.',
            'target.required'  => 'Target jumlah pendonor wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=admin-form-jadwal');
        }

        $lokasi       = trim($_POST['lokasi']);
        $tanggal      = $_POST['tanggal'];
        $target       = intval($_POST['target']);
        $waktuMulai   = $_POST['waktu_mulai'] ?? $_POST['jam_mulai'] ?? '08:00';
        $waktuSelesai = $_POST['waktu_selesai'] ?? $_POST['jam_selesai'] ?? '14:00';
        $status       = $_POST['status'] ?? 'Akan Datang';
        $catatan      = trim($_POST['catatan'] ?? '');

        $this->jadwalModel->create([
            'lokasi'        => $lokasi, 
            'tanggal'       => $tanggal, 
            'target'        => $target,
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'status'        => $status,
            'catatan'       => $catatan
        ]);

        Log::info("Admin menambahkan jadwal donor baru di {$lokasi} tanggal {$tanggal}");
        flash('success', 'Jadwal donor darah baru berhasil ditambahkan.');
        redirect('index.php?page=admin-jadwal-donor');
    }

    public function editJadwalProcess()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? 0);
        abort_unless($id > 0, 404, 'Jadwal donor tidak ditemukan.');

        $validator = Validator::make($_POST, [
            'lokasi'  => 'required|min:3|max:200',
            'tanggal' => 'required|date',
            'target'  => 'required|numeric|min:1',
        ], [
            'lokasi.required'  => 'Lokasi kegiatan donor darah wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan donor darah wajib diisi.',
            'target.required'  => 'Target jumlah pendonor wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            redirect('index.php?page=admin-jadwal-donor');
        }

        $lokasi       = trim($_POST['lokasi']);
        $tanggal      = $_POST['tanggal'];
        $target       = intval($_POST['target']);
        $waktuMulai   = $_POST['waktu_mulai'] ?? $_POST['jam_mulai'] ?? '08:00';
        $waktuSelesai = $_POST['waktu_selesai'] ?? $_POST['jam_selesai'] ?? '14:00';
        $status       = $_POST['status'] ?? 'Akan Datang';
        $catatan      = trim($_POST['catatan'] ?? '');

        $this->jadwalModel->update($id, [
            'lokasi'        => $lokasi, 
            'tanggal'       => $tanggal, 
            'target'        => $target,
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'status'        => $status,
            'catatan'       => $catatan
        ]);

        Log::info("Admin memperbarui jadwal donor ID #{$id} di {$lokasi}");
        flash('success', 'Jadwal donor darah berhasil diperbarui.');
        redirect('index.php?page=admin-jadwal-donor');
    }

    public function hapusJadwalProcess()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id) {
            $this->jadwalModel->delete($id);
            Log::info("Admin menghapus jadwal donor ID #{$id}");
            flash('success', 'Jadwal donor darah berhasil dihapus.');
        } else {
            flash('error', 'ID jadwal tidak valid.');
        }
        redirect('index.php?page=admin-jadwal-donor');
    }

    public function daftarPendonor()
    {
        $this->requireAdmin();
        $pendonorList = $this->pendonorModel->getAll();
        $search   = $_GET['search'] ?? '';
        $golongan = $_GET['golongan'] ?? '';
        $status   = $_GET['status'] ?? '';

        if ($search || $golongan || $status) {
            $pendonorList = array_filter($pendonorList, function($item) use ($search, $golongan, $status) {
                if ($golongan && ($item['golongan'] ?? '') != $golongan) return false;
                if ($status && ($item['status'] ?? 'aktif') != $status) return false;
                if ($search) {
                    $s = strtolower($search);
                    if (
                        strpos(strtolower($item['nama'] ?? ''), $s) === false &&
                        strpos(strtolower($item['nik'] ?? ''), $s) === false &&
                        strpos(strtolower($item['telepon'] ?? ''), $s) === false
                    ) return false;
                }
                return true;
            });
        }

        require __DIR__ . '/../views/admin/daftar-pendonor-admin.php';
    }

    public function exportSemuaLaporan()
    {
        $this->requireAdmin();
        Log::info("Admin mengekspor seluruh laporan operasional SIDORA ke format CSV");

        $users      = $this->userModel->getAll();
        $petugas    = array_values(array_filter($users, fn($user) => ($user['role'] ?? '') === 'petugas'));
        $rumahSakit = array_values(array_filter(
            $users,
            fn($user) => ($user['role'] ?? '') === 'rumahsakit' && ($user['status'] ?? '') !== 'ditolak'
        ));
        $pendonor   = $this->pendonorModel->getAll();
        $permintaan = $this->permintaanModel->getAllWithUser();
        $stokDarah  = $this->stokModel->getAll();
        $stokHistory = $this->stokModel->getHistoryLast7Days();
        $riwayatDonasi = $this->riwayatModel->getAll();
        $jadwalDonor = $this->jadwalModel->getAll();

        $stokTotal = array_reduce($stokDarah, fn($carry, $item) => $carry + (int)($item['jumlah'] ?? 0), 0);
        $permintaanPending = count(array_filter($permintaan, fn($item) => ($item['status'] ?? '') === 'Pending'));
        $permintaanDisetujui = count(array_filter($permintaan, fn($item) => in_array(($item['status'] ?? ''), ['Disetujui', 'Dikirim'])));
        $permintaanDitolak = count(array_filter($permintaan, fn($item) => ($item['status'] ?? '') === 'Ditolak'));

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=Laporan_SIDORA_" . date('Y-m-d') . ".csv");
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); 

        fputcsv($out, ['=== RINGKASAN OPERASIONAL SIDORA ===']);
        fputcsv($out, ['Item', 'Jumlah']);
        fputcsv($out, ['Total Petugas', count($petugas)]);
        fputcsv($out, ['Total Rumah Sakit Terverifikasi/Pending', count($rumahSakit)]);
        fputcsv($out, ['Total Pendonor', count($pendonor)]);
        fputcsv($out, ['Total Stok Darah', $stokTotal]);
        fputcsv($out, ['Total Riwayat Donasi', count($riwayatDonasi)]);
        fputcsv($out, ['Total Jadwal Donor', count($jadwalDonor)]);
        fputcsv($out, ['Total Permintaan Darah', count($permintaan)]);
        fputcsv($out, ['Permintaan Pending', $permintaanPending]);
        fputcsv($out, ['Permintaan Disetujui/Dikirim', $permintaanDisetujui]);
        fputcsv($out, ['Permintaan Ditolak', $permintaanDitolak]);
        fputcsv($out, []);

        fputcsv($out, ['=== DATA PETUGAS ===']);
        fputcsv($out, ['Nama', 'Email', 'Username', 'Telepon', 'Status', 'Tanggal Bergabung']);
        foreach ($petugas as $p) {
            fputcsv($out, [
                $p['name'] ?? '',
                $p['email'] ?? '',
                $p['username'] ?? '',
                $p['telepon'] ?? '',
                $p['status'] ?? '',
                $this->csvDateText($p['created_at'] ?? ''),
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== DATA RUMAH SAKIT ===']);
        fputcsv($out, ['Nama Rumah Sakit', 'Email', 'Username', 'Tipe Rumah Sakit', 'No. Izin Operasional', 'Telepon', 'Kontak / PIC', 'Alamat', 'Kelurahan / Desa', 'Kecamatan', 'Provinsi', 'Kode Pos', 'Status', 'Tanggal Daftar']);
        foreach ($rumahSakit as $rs) {
            fputcsv($out, [
                $rs['name'] ?? '',
                $rs['email'] ?? '',
                $rs['username'] ?? '',
                $rs['tipe_rs'] ?? '',
                $rs['no_izin'] ?? '',
                $rs['telepon'] ?? '',
                $rs['kontak'] ?? '',
                $rs['alamat'] ?? '',
                $rs['desa'] ?? '',
                $rs['kecamatan'] ?? '',
                $rs['provinsi'] ?? '',
                $rs['kode_pos'] ?? '',
                $rs['status'] ?? '',
                $this->csvDateText($rs['created_at'] ?? ''),
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== DATA PENDONOR ===']);
        fputcsv($out, ['Nama', 'Golongan Darah', 'NIK', 'Telepon', 'Email', 'Alamat', 'Kota', 'Provinsi', 'Status']);
        foreach ($pendonor as $pd) {
            fputcsv($out, [
                $pd['nama'] ?? '',
                ($pd['golongan'] ?? '') . ($pd['rhesus'] ?? ''),
                $pd['nik'] ?? '',
                $pd['telepon'] ?? '',
                $pd['email'] ?? '',
                $pd['alamat'] ?? '',
                $pd['kota'] ?? '',
                $pd['provinsi'] ?? '',
                $pd['status'] ?? '',
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== RIWAYAT DONASI ===']);
        fputcsv($out, ['Tanggal Donasi', 'Nama Pendonor', 'NIK', 'Golongan Darah', 'Jumlah Kantong', 'Tekanan Darah', 'Status', 'Telepon', 'Alamat']);
        foreach ($riwayatDonasi as $riwayat) {
            fputcsv($out, [
                $this->csvDateText($riwayat['tanggal'] ?? ''),
                $riwayat['nama_pendonor'] ?? '',
                $riwayat['nik'] ?? '',
                ($riwayat['golongan'] ?? '') . ($riwayat['rhesus'] ?? ''),
                $riwayat['jumlah'] ?? '',
                $riwayat['tekanan_darah'] ?? '',
                $riwayat['status'] ?? '',
                $riwayat['telepon'] ?? '',
                $riwayat['alamat'] ?? '',
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== STOK DARAH SAAT INI ===']);
        fputcsv($out, ['Golongan Darah', 'Rhesus', 'Jumlah Kantong', 'Minimum Stok', 'Status Stok']);
        foreach ($stokDarah as $stok) {
            $jumlah = (int)($stok['jumlah'] ?? 0);
            $minStock = (int)($stok['min_stock'] ?? 50);
            $statusStok = $jumlah >= $minStock ? 'Aman' : ($jumlah >= 30 ? 'Rendah' : 'Kritis');
            fputcsv($out, [
                $stok['golongan_darah'] ?? '',
                $stok['rhesus'] ?? '',
                $jumlah,
                $minStock,
                $statusStok,
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== RIWAYAT UPDATE STOK 7 HARI TERAKHIR ===']);
        fputcsv($out, ['Tanggal', 'Golongan Darah', 'Masuk', 'Keluar', 'Stok Akhir', 'Catatan']);
        foreach ($stokHistory as $history) {
            fputcsv($out, [
                $this->csvDateText($history['tanggal'] ?? ''),
                ($history['golongan'] ?? '') . ($history['rhesus'] ?? ''),
                $history['masuk'] ?? 0,
                $history['keluar'] ?? 0,
                $history['stok_akhir'] ?? 0,
                $history['catatan'] ?? '',
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== JADWAL DONOR ===']);
        fputcsv($out, ['Tanggal', 'Waktu Mulai', 'Waktu Selesai', 'Lokasi', 'Target', 'Terdaftar', 'Status', 'Catatan']);
        foreach ($jadwalDonor as $jadwal) {
            fputcsv($out, [
                $this->csvDateText($jadwal['tanggal'] ?? ''),
                $jadwal['waktu_mulai'] ?? '',
                $jadwal['waktu_selesai'] ?? '',
                $jadwal['lokasi'] ?? '',
                $jadwal['target'] ?? '',
                $jadwal['terdaftar'] ?? '',
                $jadwal['status'] ?? '',
                $jadwal['catatan'] ?? '',
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['=== DATA PERMINTAAN ===']);
        fputcsv($out, ['Rumah Sakit', 'Golongan', 'Jumlah', 'Prioritas', 'Status', 'Tanggal Permintaan', 'Alasan Tolak', 'Tanggal Tolak', 'Tanggal Kirim', 'Kurir', 'Keterangan']);
        foreach ($permintaan as $req) {
            fputcsv($out, [
                $req['rumah_sakit'] ?? '',
                ($req['golongan'] ?? '') . ($req['rhesus'] ?? ''),
                $req['detail_jumlah'] ?? 0,
                $req['prioritas'] ?? '',
                $req['status'] ?? '',
                $this->csvDateText($req['created_at'] ?? ''),
                $req['alasan_tolak'] ?? '',
                $this->csvDateText($req['tanggal_tolak'] ?? ''),
                $this->csvDateText($req['tanggal_kirim'] ?? ''),
                $req['kurir'] ?? '',
                $req['keterangan'] ?? '',
            ]);
        }
        fclose($out);
        exit;
    }

    public function exportPermintaanLaporan()
    {
        $this->requireAdmin();
        Log::info("Admin mengekspor laporan permintaan darah ke format CSV");

        $permintaan = $this->permintaanModel->getAllWithUser();

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=Permintaan_Darah_" . date('Y-m-d') . ".csv");
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, ['No', 'Rumah Sakit', 'Golongan', 'Jumlah', 'Prioritas', 'Status', 'Tanggal', 'Kurir']);
        $no = 1;
        foreach ($permintaan as $req) {
            fputcsv($out, [
                $no++,
                $req['rumah_sakit'] ?? '',
                ($req['golongan'] ?? '') . ($req['rhesus'] ?? ''),
                $req['detail_jumlah'] ?? 0,
                $req['prioritas'] ?? '',
                $req['status'] ?? '',
                $this->csvDateText($req['created_at'] ?? ''),
                $req['kurir'] ?? '',
            ]);
        }
        fclose($out);
        exit;
    }
}
