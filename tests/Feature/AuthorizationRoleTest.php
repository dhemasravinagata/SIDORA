<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthorizationRoleTest extends TestCase
{
    public function test_guest_cannot_access_protected_dashboards(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/petugas/dashboard')->assertRedirect(route('login'));
        $this->get('/rs/dashboard')->assertRedirect(route('login'));
    }

    public function test_petugas_cannot_access_admin_routes(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $this->actingAs($petugas);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_rumah_sakit_cannot_access_admin_or_petugas_routes(): void
    {
        $rs = User::where('role', 'rumahsakit')->where('status', 'aktif')->first();
        $this->actingAs($rs);

        $this->get('/admin/dashboard')->assertStatus(403);
        $this->get('/petugas/dashboard')->assertStatus(403);
    }

    public function test_admin_cannot_access_rumah_sakit_portal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/rs/dashboard')->assertStatus(403);
    }
}
