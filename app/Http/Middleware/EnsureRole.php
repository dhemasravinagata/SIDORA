<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Cek status akun pengguna
        if ($user->status !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $pesan = $user->status === 'pending'
                ? 'Akun Anda masih menunggu verifikasi dari Administrator PMI.'
                : 'Akun Anda dinonaktifkan. Silakan hubungi Administrator PMI.';

            return redirect()->route('login')->with('error', $pesan);
        }

        // Cek kecocokan role pengguna
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
