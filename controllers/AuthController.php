<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

use Core\Logger as Log;
use Core\Validator;

class AuthController
{
    public function showLogin()
    {
        require 'views/auth/login.php';
    }

    public function login()
    {
        $validator = Validator::make($_POST, [
            'email'    => 'required',
            'password' => 'required',
        ], [
            'email.required'    => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmailOrUsername($email);

        if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
            if ($user['role'] === 'rumahsakit' && $user['status'] !== 'aktif') {
                Log::warning("Percobaan login akun Rumah Sakit yang belum diverifikasi: {$email}");
                flash('warning', 'Akun rumah sakit belum aktif. Mohon menunggu verifikasi dan persetujuan dari admin.');
                redirect('index.php?page=login');
            }
            if ($user['status'] === 'nonaktif' || $user['status'] === 'ditolak') {
                Log::warning("Percobaan login akun nonaktif/ditolak: {$email}");
                flash('error', 'Akun Anda berstatus nonaktif atau ditolak. Silakan hubungi administrator.');
                redirect('index.php?page=login');
            }

            $_SESSION['user'] = $user;
            unset($_SESSION['old'], $_SESSION['errors']);

            Log::info("Pengguna berhasil masuk: {$user['username']} ({$user['role']})");
            flash('success', "Selamat datang, " . ($user['name'] ?? $user['username']) . "!");

            if ($user['role'] === 'admin') {
                redirect('index.php?page=admin-dashboard');
            } elseif ($user['role'] === 'petugas') {
                redirect('index.php?page=petugas-dashboard');
            } else {
                redirect('index.php?page=rs-dashboard');
            }
        }

        Log::warning("Gagal login dengan identitas: {$email}");
        flash('error', 'Username/Email atau password salah.');
        $_SESSION['old'] = ['email' => $email];
        redirect('index.php?page=login');
    }

    public function registerRs()
    {
        $validator = Validator::make($_POST, [
            'nama_rs'  => 'required|min:3|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'username' => 'nullable|min:3|max:50|unique:users,username',
            'telepon'  => 'nullable|min:6|max:25',
        ], [
            'nama_rs.required' => 'Nama Rumah Sakit wajib diisi.',
            'email.required'   => 'Email resmi Rumah Sakit wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'email.unique'     => 'Email ini sudah terdaftar dalam sistem.',
            'password.required'=> 'Password wajib diisi.',
            'password.min'     => 'Password minimal harus 6 karakter.',
            'username.unique'  => 'Username sudah digunakan oleh akun lain.',
        ]);

        if ($validator->fails()) {
            flash('error', $validator->first());
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=register-rs');
        }

        $namaRs    = trim($_POST['nama_rs'] ?? '');
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $telepon   = trim($_POST['telepon'] ?? '');
        $kontak    = trim($_POST['kontak'] ?? '');
        $alamat    = trim($_POST['alamat'] ?? '');
        $desa      = trim($_POST['desa'] ?? '');
        $kecamatan = trim($_POST['kecamatan'] ?? '');
        $provinsi  = trim($_POST['provinsi'] ?? '');
        $kode_pos  = trim($_POST['kode_pos'] ?? '');
        $tipe_rs   = trim($_POST['tipe_rs'] ?? '');
        $no_izin   = trim($_POST['no_izin'] ?? '');

        $userModel = new User();

        try {
            $created = $userModel->createRumahSakitPending([
                'name'      => $namaRs,
                'email'     => $email,
                'password'  => password_hash($password, PASSWORD_DEFAULT),
                'role'      => 'rumahsakit',
                'status'    => 'pending',
                'username'  => $username ?: null,
                'telepon'   => $telepon,
                'kontak'    => $kontak,
                'alamat'    => $alamat,
                'kecamatan' => $kecamatan,
                'desa'      => $desa,
                'kode_pos'  => $kode_pos,
                'provinsi'  => $provinsi,
                'tipe_rs'   => $tipe_rs,
                'no_izin'   => $no_izin,
            ]);

            if ($created) {
                Log::info("Pendaftaran Rumah Sakit baru diajukan: {$namaRs} ({$email})");
                flash('success', 'Registrasi Rumah Sakit berhasil diajukan. Mohon menunggu proses verifikasi oleh Admin.');
                redirect('index.php?page=login');
            }
        } catch (PDOException $e) {
            Log::error("Kesalahan database saat registrasi RS: " . $e->getMessage());
            flash('error', 'Terjadi kesalahan sistem saat memproses registrasi. Silakan coba kembali.');
            $_SESSION['old'] = $_POST;
            redirect('index.php?page=register-rs');
        }

        flash('error', 'Gagal mendaftarkan akun Rumah Sakit.');
        redirect('index.php?page=register-rs');
    }

    public function logout()
    {
        $userIdentifier = $_SESSION['user']['username'] ?? $_SESSION['user']['email'] ?? 'User';
        Log::info("Pengguna keluar dari sistem: {$userIdentifier}");

        session_unset();
        session_destroy();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        session_start();
        flash('info', 'Anda telah berhasil logout.');
        redirect('index.php?page=login');
    }
}
