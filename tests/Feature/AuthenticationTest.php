<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@sidora.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_petugas_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'petugas1@sidora.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_active_hospital_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'rsud@sidora.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('rs.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_pending_hospital_cannot_login(): void
    {
        $pendingRs = User::firstOrCreate(
            ['email' => 'uji_pending_rs@sidora.com'],
            [
                'name'     => 'RS Uji Pending',
                'username' => 'uji_pending_rs',
                'password' => Hash::make('password'),
                'role'     => 'rumahsakit',
                'status'   => 'pending',
            ]
        );
        $pendingRs->update(['status' => 'pending']);

        $response = $this->post('/login', [
            'email'    => 'uji_pending_rs@sidora.com',
            'password' => 'password',
        ]);

        $response->assertSessionHas('warning');
        $this->assertStringContainsString('belum aktif', session('warning'));
        $this->assertStringContainsString('verifikasi', session('warning'));
        $this->assertGuest();

        // Follow redirect to login page and ensure alert banner is rendered
        $loginPage = $this->get('/login');
        $loginPage->assertStatus(200);
        $loginPage->assertSee('Akun Menunggu Verifikasi');
        $loginPage->assertSee('belum aktif karena masih dalam tahap verifikasi');
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@sidora.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHas('error', 'Username/Email atau password salah.');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@sidora.com')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_hospital_registration_validates_duplicate_email(): void
    {
        $response = $this->post('/register-rs', [
            'nama_rs'  => 'RS Duplikat Test',
            'email'    => 'admin@sidora.com', // Already taken
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('sudah terdaftar', session('error'));
    }

    public function test_hospital_registration_handles_whitespace_and_succeeds(): void
    {
        $uniqueEmail = 'rs_baru_' . time() . '@example.com';
        $response = $this->post('/register-rs', [
            'nama_rs'  => 'RS Sehat Sentosa',
            'email'    => '  ' . strtoupper($uniqueEmail) . '  ',
            'password' => 'password123',
            'telepon'  => '081234567890',
            'tipe_rs'  => 'Umum',
            'no_izin'  => '123/RS/2026',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email'  => strtolower($uniqueEmail),
            'role'   => 'rumahsakit',
            'status' => 'pending',
        ]);
    }

    public function test_login_supports_case_insensitive_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'ADMIN@SIDORA.COM',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_legacy_slash_login_route_renders_cleanly(): void
    {
        $response = $this->get('/index.php/login');
        $response->assertStatus(200);
    }
}
