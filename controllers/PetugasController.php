<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pendonor.php';
require_once __DIR__ . '/../models/JadwalDonor.php';
require_once __DIR__ . '/../models/StokDarah.php';
require_once __DIR__ . '/../models/RiwayatDonasi.php';

use Core\Logger as Log;
use Core\Validator;

class PetugasController
{
    private $pendonorModel;
    private $jadwalModel;
    private $stokModel;
    private $riwayatModel;

    public function __construct()
    {
        $this->pendonorModel = new Pendonor();
        $this->jadwalModel   = new JadwalDonor();
        $this->stokModel     = new StokDarah();
        $this->riwayatModel  = new RiwayatDonasi();
    }

    private function requirePetugas()
    {
        if (!isset($_SESSION['user'])) {
            flash('warning', 'Silakan login terlebih dahulu untuk mengakses menu Petugas.');
            redirect('index.php?page=login');
        }

        if (($_SESSION['user']['role'] ?? '') !== 'petugas') {
            Log::warning("Akses ditolak ke menu petugas untuk pengguna: " . ($_SESSION['user']['username'] ?? 'unknown') . " (role: " . ($_SESSION['user']['role'] ?? '-') . ")");
            abort(403, 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Petugas SIDORA.');
        }
    }

    public function dashboard()
    {
        $this->requirePetugas();
        $stokList   = $this->stokModel->getAll();
        $jadwalList = $this->jadwalModel->getAll();
        $riwayatList = $this->riwayatModel->getAll();

        $stokTotal = array_reduce($stokList, fn($c, $i) => $c + (int)($i['jumlah'] ?? 0), 0);
        $today = date('Y-m-d');
        $donasiHariIni = count(array_filter($riwayatList, fn($r) => ($r['tanggal'] ?? '') === $today));

        $statistics = [
            'pendonor'        => count($this->pendonorModel->getAll()),
            'donasi_hari_ini' => $donasiHariIni,
            'stok_total'      => $stokTotal,
            'jadwal'          => count($jadwalList),
        ];

        require __DIR__ . '/../views/petugas/dashboard.php';
    }

    public function daftarPendonor()
    {
        $this->requirePetugas();
        $pendonorList = $this->pendonorModel->getAll();
        require __DIR__ . '/../views/petugas/daftar-pendonor.php';
    }

    public function formPendonor()
    {
        $this->requirePetugas();
        require __DIR__ . '/../views/petugas/form-pendonor.php';
    }

    public function tambahPendonorProcess()
    {
        $this->requirePetugas();

        $validator = Validator::make($_POST, [
            'nama'           => 'required|min:3|max:150',
            'no_identitas'   => 'required|min:8|max:30|unique:pendonor,nik',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'rhesus'         => 'required|in:+,-',
            'telepon'        => 'required|min:6|max:20',
            'email'          => 'nullable|email',
            'tanggal_lahir'  => 'nullable|date',
        ], [
            'nama.required'           => 'Nama lengkap pendonor wajib diisi.',
            'no_identitas.required'   => 'Nomor identitas (NIK) wajib diisi.',
            'no_identitas.unique'     => 'NIK pendonor ini sudah terdaftar dalam sistem.',
            'golongan_darah.required' => 'Golongan darah wajib dipilih (A, B, AB, O).',
            'rhesus.required'         => 'Rhesus darah wajib dipilih (+ atau -).',
            'telepon.required'        => 'Nomor telepon wajib diisi.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=petugas-daftar-pendonor');
        }

        $nama          = trim($_POST['nama']);
        $no_identitas  = trim($_POST['no_identitas']);
        $golongan      = trim($_POST['golongan_darah']);
        $rhesus        = trim($_POST['rhesus']);
        $telepon       = trim($_POST['telepon']);
        $email         = trim($_POST['email'] ?? '');
        $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '') ?: null;
        $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
        $alamat        = trim($_POST['alamat'] ?? '');
        $kota          = trim($_POST['kota'] ?? '');
        $provinsi      = trim($_POST['provinsi'] ?? '');
        $pekerjaan     = trim($_POST['pekerjaan'] ?? '');
        $status        = ($_POST['status'] ?? '') === 'aktif' ? 'aktif' : 'nonaktif';

        $pendonorId = $this->pendonorModel->create([
            'nama'          => $nama,
            'nik'           => $no_identitas,
            'golongan'      => $golongan,
            'rhesus'        => $rhesus,
            'telepon'       => $telepon,
            'email'         => $email,
            'tanggal_lahir' => $tanggal_lahir,
            'jenis_kelamin' => $jenis_kelamin,
            'alamat'        => $alamat,
            'kota'          => $kota,
            'provinsi'      => $provinsi,
            'pekerjaan'     => $pekerjaan,
            'status'        => $status,
        ]);

        $tekanan_darah = trim($_POST['tekanan_darah'] ?? '120/80');
        $status_donasi = trim($_POST['status_donasi'] ?? 'Berhasil');

        $this->riwayatModel->create([
            'pendonor_id'   => $pendonorId,
            'golongan'      => $golongan,
            'rhesus'        => $rhesus,
            'jumlah'        => 1,
            'tanggal'       => date('Y-m-d'),
            'tekanan_darah' => $tekanan_darah,
            'status'        => $status_donasi,
        ]);

        if ($status_donasi === 'Berhasil') {
            $this->stokModel->addStock($golongan, $rhesus, 1);
            Log::info("Petugas mencatat penambahan stok darah {$golongan}{$rhesus} (+1 kantong) dari pendonor {$nama}");
        }

        Log::info("Petugas mendaftarkan pendonor baru: {$nama} (NIK: {$no_identitas})");
        flash('success', "Pendonor '{$nama}' berhasil didaftarkan" . ($status_donasi === 'Berhasil' ? ' dan stok darah otomatis diperbarui.' : '.'));
        redirect('index.php?page=petugas-daftar-pendonor');
    }

    public function editPendonorProcess()
    {
        $this->requirePetugas();
        $id = intval($_POST['id'] ?? 0);
        abort_unless($id > 0, 404, 'Data pendonor tidak valid.');

        $validator = Validator::make($_POST, [
            'nama'           => 'required|min:3|max:150',
            'nik'            => "required|min:8|max:30|unique:pendonor,nik,{$id}",
            'golongan_darah' => 'required|in:A,B,AB,O',
            'rhesus'         => 'required|in:+,-',
            'telepon'        => 'required|min:6|max:20',
        ], [
            'nama.required' => 'Nama lengkap pendonor wajib diisi.',
            'nik.required'  => 'NIK pendonor wajib diisi.',
            'nik.unique'    => 'NIK ini sudah digunakan pendonor lain.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=petugas-daftar-pendonor');
        }

        $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '') ?: null;
        $nama = trim($_POST['nama']);

        $this->pendonorModel->update($id, [
            'nama'          => $nama,
            'nik'           => trim($_POST['nik']),
            'golongan'      => trim($_POST['golongan_darah']),
            'rhesus'        => trim($_POST['rhesus']),
            'telepon'       => trim($_POST['telepon']),
            'email'         => trim($_POST['email'] ?? ''),
            'tanggal_lahir' => $tanggal_lahir,
            'jenis_kelamin' => trim($_POST['jenis_kelamin'] ?? ''),
            'alamat'        => trim($_POST['alamat'] ?? ''),
            'kota'          => trim($_POST['kota'] ?? ''),
            'provinsi'      => trim($_POST['provinsi'] ?? ''),
            'pekerjaan'     => trim($_POST['pekerjaan'] ?? ''),
            'status'        => ($_POST['status'] ?? '') === 'aktif' ? 'aktif' : 'nonaktif',
        ]);

        Log::info("Petugas memperbarui data pendonor ID #{$id} ({$nama})");
        flash('success', "Data pendonor '{$nama}' berhasil diperbarui.");
        redirect('index.php?page=petugas-daftar-pendonor');
    }

    public function hapusPendonorProcess()
    {
        $this->requirePetugas();
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id) {
            $p = $this->pendonorModel->getById($id);
            $this->pendonorModel->delete($id);
            Log::info("Petugas menghapus data pendonor ID #{$id} (" . ($p['nama'] ?? '-') . ")");
            flash('success', 'Data pendonor berhasil dihapus.');
        } else {
            flash('error', 'ID pendonor tidak valid.');
        }
        redirect('index.php?page=petugas-daftar-pendonor');
    }

    public function riwayatDonasi()
    {
        $this->requirePetugas();
        $riwayatList = $this->riwayatModel->getAll();
        $status  = $_GET['status'] ?? '';
        $tanggal = $_GET['tanggal'] ?? '';

        if ($status || $tanggal) {
            $riwayatList = array_filter($riwayatList, function($item) use ($status, $tanggal) {
                if ($tanggal && ($item['tanggal'] ?? '') != $tanggal) return false;
                if ($status && ($item['status'] ?? '') != $status) return false;
                return true;
            });
        }

        require __DIR__ . '/../views/petugas/riwayat-donasi.php';
    }

    public function formRiwayat()
    {
        $this->requirePetugas();
        $pendonorList = $this->pendonorModel->getAll();
        require __DIR__ . '/../views/petugas/form-riwayat.php';
    }

    public function formRiwayatProcess()
    {
        $this->requirePetugas();

        $validator = Validator::make($_POST, [
            'pendonor_id' => 'required|numeric',
            'golongan'    => 'required|in:A,B,AB,O',
            'rhesus'      => 'required|in:+,-',
            'jumlah'      => 'required|numeric|min:1',
            'tanggal'     => 'required|date',
        ], [
            'pendonor_id.required' => 'Pilih pendonor yang melakukan donor darah.',
            'golongan.required'    => 'Golongan darah wajib diisi.',
            'rhesus.required'      => 'Rhesus darah wajib diisi.',
            'jumlah.required'      => 'Jumlah kantong darah wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=petugas-form-riwayat');
        }

        $pendonorId    = intval($_POST['pendonor_id']);
        $golongan      = trim($_POST['golongan']);
        $rhesus        = trim($_POST['rhesus']);
        $jumlah        = intval($_POST['jumlah']);
        $tanggal       = $_POST['tanggal'];
        $tekanan_darah = trim($_POST['tekanan_darah'] ?? '120/80');
        $status        = trim($_POST['status'] ?? 'Berhasil');

        $this->riwayatModel->create([
            'pendonor_id'   => $pendonorId,
            'golongan'      => $golongan,
            'rhesus'        => $rhesus,
            'jumlah'        => $jumlah,
            'tanggal'       => $tanggal,
            'tekanan_darah' => $tekanan_darah,
            'status'        => $status,
        ]);

        if ($status === 'Berhasil') {
            $this->stokModel->addStock($golongan, $rhesus, $jumlah);
            Log::info("Petugas menambahkan riwayat donasi donor #{$pendonorId} ({$golongan}{$rhesus}, {$jumlah} kantong, stok bertambah)");
        } else {
            Log::info("Petugas mencatat riwayat donasi gagal/batal untuk donor #{$pendonorId}");
        }

        flash('success', 'Riwayat donasi darah berhasil dicatat' . ($status === 'Berhasil' ? ' dan stok diperbarui.' : '.'));
        redirect('index.php?page=petugas-riwayat-donasi');
    }

    public function editRiwayatProcess()
    {
        $this->requirePetugas();
        $id = intval($_POST['id'] ?? 0);
        abort_unless($id > 0, 404, 'Data riwayat donasi tidak ditemukan.');

        $validator = Validator::make($_POST, [
            'golongan' => 'required|in:A,B,AB,O',
            'rhesus'   => 'required|in:+,-',
            'jumlah'   => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            redirect('index.php?page=petugas-riwayat-donasi');
        }

        $old = $this->riwayatModel->getById($id);
        $golongan = trim($_POST['golongan']);
        $rhesus   = trim($_POST['rhesus']);
        $jumlah   = intval($_POST['jumlah']);
        $status   = trim($_POST['status'] ?? 'Berhasil');

        // Balikkan stok lama jika status lama adalah Berhasil
        if ($old && ($old['status'] ?? '') === 'Berhasil') {
            $this->stokModel->reduceStock($old['golongan'], $old['rhesus'], $old['jumlah']);
        }

        $this->riwayatModel->update($id, [
            'pendonor_id'   => intval($_POST['pendonor_id'] ?? $old['pendonor_id']),
            'golongan'      => $golongan,
            'rhesus'        => $rhesus,
            'jumlah'        => $jumlah,
            'tanggal'       => $_POST['tanggal'] ?? $old['tanggal'],
            'tekanan_darah' => trim($_POST['tekanan_darah'] ?? '120/80'),
            'status'        => $status,
        ]);

        // Tambah stok baru jika status baru adalah Berhasil
        if ($status === 'Berhasil') {
            $this->stokModel->addStock($golongan, $rhesus, $jumlah);
        }

        Log::info("Petugas memperbarui riwayat donasi ID #{$id}");
        flash('success', 'Riwayat donasi berhasil diperbarui.');
        redirect('index.php?page=petugas-riwayat-donasi');
    }

    public function hapusRiwayatProcess()
    {
        $this->requirePetugas();
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id) {
            $old = $this->riwayatModel->getById($id);
            if ($old && ($old['status'] ?? '') === 'Berhasil') {
                $this->stokModel->reduceStock($old['golongan'], $old['rhesus'], $old['jumlah']);
            }
            $this->riwayatModel->delete($id);
            Log::info("Petugas menghapus riwayat donasi ID #{$id}");
            flash('success', 'Riwayat donasi berhasil dihapus dan stok telah disesuaikan.');
        } else {
            flash('error', 'ID riwayat tidak valid.');
        }
        redirect('index.php?page=petugas-riwayat-donasi');
    }

    public function jadwalDonor()
    {
        $this->requirePetugas();
        $jadwalList = $this->jadwalModel->getAll();
        require __DIR__ . '/../views/petugas/jadwal-donor.php';
    }

    public function stokDarah()
    {
        $this->requirePetugas();
        $stokList = $this->stokModel->getAll();
        require __DIR__ . '/../views/petugas/stok-darah.php';
    }
}
