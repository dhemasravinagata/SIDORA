<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/StokDarah.php';
require_once __DIR__ . '/../models/PermintaanDarah.php';

use Core\Logger as Log;
use Core\Validator;

class RumahSakitController
{
    private $userModel;
    private $stokModel;
    private $permintaanModel;

    public function __construct()
    {
        $this->userModel       = new User();
        $this->stokModel       = new StokDarah();
        $this->permintaanModel = new PermintaanDarah();
    }

    private function requireRumahSakit()
    {
        if (!isset($_SESSION['user'])) {
            flash('warning', 'Silakan login terlebih dahulu untuk mengakses portal Rumah Sakit.');
            redirect('index.php?page=login');
        }

        if (($_SESSION['user']['role'] ?? '') !== 'rumahsakit') {
            Log::warning("Akses ditolak ke menu RS untuk pengguna: " . ($_SESSION['user']['username'] ?? 'unknown') . " (role: " . ($_SESSION['user']['role'] ?? '-') . ")");
            abort(403, 'Akses Ditolak: Halaman ini hanya diperuntukkan bagi Akun Rumah Sakit.');
        }
    }

    public function dashboard()
    {
        $this->requireRumahSakit();
        $stokList       = $this->stokModel->getAll();
        $userId         = $_SESSION['user']['id'];
        $permintaanList = $this->permintaanModel->getAllByUser($userId);

        $statistics = [
            'total_permintaan'   => count($permintaanList),
            'permintaan_ditinjau' => count(array_filter($permintaanList, fn($p) => ($p['status'] ?? '') === 'Pending')),
            'disetujui'          => count(array_filter($permintaanList, fn($p) => in_array(($p['status'] ?? ''), ['Disetujui', 'Dikirim']))),
            'permintaan_ditolak' => count(array_filter($permintaanList, fn($p) => ($p['status'] ?? '') === 'Ditolak')),
        ];

        require __DIR__ . '/../views/rumahsakit/dashboard.php';
    }

    public function permintaan()
    {
        $this->requireRumahSakit();
        $stokList = $this->stokModel->getAll();
        require __DIR__ . '/../views/rumahsakit/permintaan-darah.php';
    }

    public function permintaanStore()
    {
        $this->requireRumahSakit();
        $userId = $_SESSION['user']['id'];
        $tujuan = trim($_POST['tujuan_permintaan'] ?? 'pasien');

        $rules = [
            'golongan'  => 'required|in:A,B,AB,O',
            'rhesus'    => 'required|in:+,-',
            'jumlah'    => 'required|numeric|min:1',
            'prioritas' => 'required|in:Biasa,Penting,Darurat,Sangat Mendesak',
        ];

        $messages = [
            'golongan.required'  => 'Golongan darah wajib dipilih.',
            'rhesus.required'    => 'Rhesus darah wajib dipilih.',
            'jumlah.required'    => 'Jumlah kantong darah wajib diisi angka positif.',
            'prioritas.required' => 'Tingkat prioritas permintaan wajib ditentukan.',
        ];

        if ($tujuan === 'pasien') {
            $rules['nama_pasien'] = 'required|min:3|max:100';
            $rules['no_pasien']   = 'required|min:2|max:50';
            $rules['ruangan']     = 'required|min:2|max:50';
            $rules['dokter']      = 'required|min:3|max:100';

            $messages['nama_pasien.required'] = 'Nama pasien penerima darah wajib diisi.';
            $messages['no_pasien.required']   = 'Nomor Rekam Medis (RM) pasien wajib diisi.';
            $messages['ruangan.required']     = 'Ruangan/Kamar perawatan pasien wajib diisi.';
            $messages['dokter.required']      = 'Nama dokter penanggung jawab wajib diisi.';
        }

        $validator = Validator::make($_POST, $rules, $messages);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=rs-permintaan');
        }

        $golongan        = trim($_POST['golongan']);
        $rhesus          = trim($_POST['rhesus']);
        $jumlah          = intval($_POST['jumlah']);
        $prioritas       = trim($_POST['prioritas']);
        $keteranganInput = trim($_POST['keterangan'] ?? '');

        if ($tujuan === 'stok') {
            $keterangan = sprintf(
                'Tujuan: stok. Keperluan: Penambahan Stok BDRS (Dropping). Catatan: %s',
                $keteranganInput ?: '-'
            );
        } else {
            $namaPasien = trim($_POST['nama_pasien']);
            $noPasien   = trim($_POST['no_pasien']);
            $ruangan    = trim($_POST['ruangan']);
            $dokter     = trim($_POST['dokter']);

            $keterangan = sprintf(
                'Tujuan: pasien. Pasien: %s (No RM: %s). Ruangan: %s. Dokter: %s. Catatan: %s',
                $namaPasien,
                $noPasien,
                $ruangan,
                $dokter,
                $keteranganInput ?: '-'
            );
        }

        $insertId = $this->permintaanModel->create([
            'user_id'    => $userId,
            'status'     => 'Pending',
            'prioritas'  => $prioritas,
            'keterangan' => $keterangan,
        ]);

        if ($insertId) {
            $this->permintaanModel->addDetail([
                'permintaan_id' => $insertId,
                'golongan'      => $golongan,
                'rhesus'        => $rhesus,
                'jumlah'        => $jumlah,
            ]);

            Log::info("Rumah Sakit ID #{$userId} mengajukan permintaan darah baru #REQ-{$insertId}: {$golongan}{$rhesus} ({$jumlah} kantong, Prioritas: {$prioritas})");
            flash('success', "Permintaan darah (#REQ-{$insertId}) berhasil diajukan dan sedang menunggu konfirmasi admin.");
            redirect('index.php?page=rs-history-permintaan');
        }

        Log::error("Rumah Sakit ID #{$userId} gagal mengajukan permintaan darah.");
        flash('error', 'Terjadi kesalahan sistem saat mengajukan permintaan.');
        redirect('index.php?page=rs-permintaan');
    }

    public function historyPermintaan()
    {
        $this->requireRumahSakit();
        $userId         = $_SESSION['user']['id'];
        $permintaanList = $this->permintaanModel->getAllByUser($userId);
        require __DIR__ . '/../views/rumahsakit/history-permintaan.php';
    }

    public function stokDarah()
    {
        $this->requireRumahSakit();
        $stokList = $this->stokModel->getAll();
        require __DIR__ . '/../views/rumahsakit/stok-darah.php';
    }

    public function profil()
    {
        $this->requireRumahSakit();
        $user = $this->userModel->findById($_SESSION['user']['id']) ?: $_SESSION['user'];
        require __DIR__ . '/../views/rumahsakit/profil.php';
    }

    public function profilUpdate()
    {
        $this->requireRumahSakit();
        $userId = $_SESSION['user']['id'];

        $validator = Validator::make($_POST, [
            'nama'     => 'required|min:3|max:150',
            'username' => "nullable|min:3|max:50|unique:users,username,{$userId}",
            'telepon'  => 'nullable|min:6|max:25',
        ], [
            'nama.required'   => 'Nama Rumah Sakit wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan akun lain.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            redirect('index.php?page=rs-profil');
        }

        $nama     = trim($_POST['nama']);
        $username = trim($_POST['username'] ?? '');
        $alamat   = trim($_POST['alamat'] ?? '');
        $telepon  = trim($_POST['telepon'] ?? '');
        $kontak   = trim($_POST['kontak'] ?? '');
        $desa      = trim($_POST['desa'] ?? '');
        $kecamatan = trim($_POST['kecamatan'] ?? '');
        $provinsi  = trim($_POST['provinsi'] ?? '');
        $kode_pos  = trim($_POST['kode_pos'] ?? '');

        $currentUser = $this->userModel->findById($userId) ?: [];
        $tipeRs = trim($_POST['tipe_rs'] ?? ($currentUser['tipe_rs'] ?? ''));
        $noIzin = trim($_POST['no_izin'] ?? ($currentUser['no_izin'] ?? ''));

        $this->userModel->updateProfil($userId, [
            'name'      => $nama,
            'username'  => $username,
            'alamat'    => $alamat,
            'telepon'   => $telepon,
            'kontak'    => $kontak,
            'desa'      => $desa,
            'kecamatan' => $kecamatan,
            'provinsi'  => $provinsi,
            'kode_pos'  => $kode_pos,
            'tipe_rs'   => $tipeRs,
            'no_izin'   => $noIzin,
        ]);

        $_SESSION['user']['name']      = $nama;
        $_SESSION['user']['username']  = $username;
        $_SESSION['user']['alamat']    = $alamat;
        $_SESSION['user']['telepon']   = $telepon;
        $_SESSION['user']['kontak']    = $kontak;
        $_SESSION['user']['desa']      = $desa;
        $_SESSION['user']['kecamatan'] = $kecamatan;
        $_SESSION['user']['provinsi']  = $provinsi;
        $_SESSION['user']['kode_pos']  = $kode_pos;
        $_SESSION['user']['tipe_rs']   = $tipeRs;
        $_SESSION['user']['no_izin']   = $noIzin;

        Log::info("Rumah Sakit ID #{$userId} memperbarui informasi profil");
        flash('success', 'Profil Rumah Sakit berhasil diperbarui.');
        redirect('index.php?page=rs-profil');
    }

    public function ubahPassword()
    {
        $this->requireRumahSakit();
        $userId = $_SESSION['user']['id'];

        $validator = Validator::make($_POST, [
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required'     => 'Password lama wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal harus 6 karakter.',
            'confirm_password.required' => 'Konfirmasi password baru wajib diisi.',
            'confirm_password.same'     => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            redirect('index.php?page=rs-profil');
        }

        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];

        $userDb = $this->userModel->findById($userId);
        if (!$userDb) {
            flash('error', 'Data pengguna tidak ditemukan.');
            redirect('index.php?page=rs-profil');
        }

        $valid = password_verify($oldPassword, $userDb['password'])
              || $oldPassword === $userDb['password'];

        if (!$valid) {
            Log::warning("Gagal mengubah password Rumah Sakit ID #{$userId}: Password lama salah");
            flash('error', 'Password lama tidak sesuai.');
            redirect('index.php?page=rs-profil');
        }

        $this->userModel->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
        Log::info("Rumah Sakit ID #{$userId} berhasil mengubah password akun");
        flash('success', 'Password berhasil diubah. Silakan gunakan password baru pada sesi masuk berikutnya.');
        redirect('index.php?page=rs-profil');
    }
}
