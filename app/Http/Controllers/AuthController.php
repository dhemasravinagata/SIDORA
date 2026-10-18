<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function showRegisterRs()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register-rs');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $loginIdentifier = trim($request->input('email'));
        $password        = $request->input('password');

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($loginIdentifier)])
            ->orWhereRaw('LOWER(username) = ?', [strtolower($loginIdentifier)])
            ->first();

        if ($user && (Hash::check($password, $user->password) || $password === $user->password)) {
            // Update password plain hash jika perlu
            if ($password === $user->password && !str_starts_with($user->password, '$2y$')) {
                $user->password = Hash::make($password);
                $user->save();
            }

            if ($user->role === 'rumahsakit' && $user->status !== 'aktif') {
                Log::warning("Percobaan login akun Rumah Sakit yang belum diverifikasi: {$loginIdentifier}");
                $namaRs = $user->name ?? $user->username;
                if ($user->status === 'ditolak') {
                    return back()
                        ->withInput($request->only('email'))
                        ->with('error', "Pendaftaran akun Rumah Sakit '{$namaRs}' telah ditolak oleh Administrator. Silakan hubungi admin SIDORA untuk informasi lebih lanjut.");
                }

                return back()
                    ->withInput($request->only('email'))
                    ->with('warning', "Akun Rumah Sakit '{$namaRs}' belum aktif karena masih dalam tahap verifikasi oleh Administrator SIDORA. Mohon menunggu proses verifikasi dan persetujuan admin sebelum dapat masuk.");
            }

            if ($user->status === 'nonaktif' || $user->status === 'ditolak') {
                Log::warning("Percobaan login akun nonaktif/ditolak: {$loginIdentifier}");
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Akun Anda berstatus nonaktif atau ditolak. Silakan hubungi administrator.');
            }

            Auth::login($user, $request->boolean('remember_me'));
            $request->session()->regenerate();

            // Set session legacy compatibility
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
            $_SESSION['user'] = $user->toArray();

            Log::info("Pengguna berhasil masuk: {$user->username} ({$user->role})");
            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang, ' . ($user->name ?? $user->username) . '!');
        }

        Log::warning("Gagal login dengan identitas: {$loginIdentifier}");
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Username/Email atau password salah.');
    }

    public function registerRs(Request $request)
    {
        if ($request->has('email')) {
            $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        }
        if ($request->has('username')) {
            $rawUsername = trim((string) $request->input('username'));
            $request->merge(['username' => $rawUsername !== '' ? $rawUsername : null]);
        }
        if ($request->has('nama_rs')) {
            $request->merge(['nama_rs' => trim((string) $request->input('nama_rs'))]);
        }

        $validator = Validator::make($request->all(), [
            'nama_rs'   => 'required|string|min:3|max:150',
            'email'     => 'required|string|email:rfc,filter|max:150|unique:users,email',
            'password'  => 'required|string|min:6',
            'username'  => 'nullable|string|min:3|max:50|unique:users,username',
            'telepon'   => 'nullable|string|min:6|max:25',
            'kontak'    => 'nullable|string|max:100',
            'alamat'    => 'nullable|string|max:255',
            'desa'      => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'provinsi'  => 'nullable|string|max:100',
            'kode_pos'  => 'nullable|string|max:10',
            'tipe_rs'   => 'nullable|string|max:50',
            'no_izin'   => 'nullable|string|max:100',
        ], [
            'nama_rs.required'  => 'Nama Rumah Sakit wajib diisi.',
            'nama_rs.min'       => 'Nama Rumah Sakit minimal 3 karakter.',
            'email.required'    => 'Email resmi Rumah Sakit wajib diisi.',
            'email.email'       => 'Format email tidak valid (contoh: rumahsakit@gmail.com).',
            'email.unique'      => 'Email ":input" sudah terdaftar dalam sistem SIDORA. Silakan gunakan email lain atau login jika sudah memiliki akun.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
            'username.unique'   => 'Username ":input" sudah digunakan oleh akun lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        try {
            $user = User::create([
                'name'      => trim($request->input('nama_rs')),
                'email'     => trim($request->input('email')),
                'username'  => trim($request->input('username')) ?: null,
                'password'  => Hash::make($request->input('password')),
                'role'      => 'rumahsakit',
                'status'    => 'pending',
                'telepon'   => trim($request->input('telepon')),
                'kontak'    => trim($request->input('kontak')),
                'alamat'    => trim($request->input('alamat')),
                'desa'      => trim($request->input('desa')),
                'kecamatan' => trim($request->input('kecamatan')),
                'provinsi'  => trim($request->input('provinsi')),
                'kode_pos'  => trim($request->input('kode_pos')),
                'tipe_rs'   => trim($request->input('tipe_rs')),
                'no_izin'   => trim($request->input('no_izin')),
            ]);

            Log::info("Pendaftaran Rumah Sakit baru diajukan: {$user->name} ({$user->email})");
            return redirect()->route('login')
                ->with('success', 'Registrasi Rumah Sakit berhasil diajukan. Mohon menunggu proses verifikasi oleh Admin.');
        } catch (\Exception $e) {
            Log::error("Kesalahan sistem saat registrasi RS: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses registrasi. Silakan coba kembali.');
        }
    }

    public function logout(Request $request)
    {
        $userIdentifier = Auth::user()?->username ?? Auth::user()?->email ?? 'User';
        Log::info("Pengguna keluar dari sistem: {$userIdentifier}");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['user']);
        }

        return redirect()->route('login')->with('info', 'Anda telah berhasil logout.');
    }

    private function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'petugas'    => redirect()->route('petugas.dashboard'),
            'rumahsakit' => redirect()->route('rs.dashboard'),
            default      => redirect('/'),
        };
    }
}
