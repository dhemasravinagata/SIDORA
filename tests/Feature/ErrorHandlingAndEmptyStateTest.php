<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ErrorHandlingAndEmptyStateTest extends TestCase
{
    public function test_non_existent_route_returns_404(): void
    {
        $response = $this->get('/halaman-yang-pasti-tidak-ada-123');
        $response->assertStatus(404);
    }

    public function test_legacy_dispatcher_routes_correctly(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get('/index.php?page=admin-dashboard');
        $response->assertStatus(200);
    }

    public function test_legacy_dispatcher_blocks_unauthorized_role(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $this->actingAs($petugas);

        $response = $this->get('/index.php?page=admin-dashboard');
        $response->assertStatus(403);
    }

    public function test_donor_search_with_no_matches_displays_cleanly(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $this->actingAs($petugas);

        $response = $this->get('/petugas/daftar-pendonor?search=NamaYangPastiTidakAda987');
        $response->assertStatus(200);
        $response->assertSee('Belum ada data pendonor');
    }

    public function test_non_existent_id_on_edit_or_delete_returns_404(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $this->actingAs($petugas);

        $response = $this->post('/petugas/edit-pendonor', [
            'id'             => 999999,
            'nama'           => 'Tidak Ada',
            'nik'            => '1871000000000000',
            'golongan_darah' => 'A',
            'rhesus'         => '+',
            'telepon'        => '081234567800',
        ]);

        $response->assertStatus(404);
    }
}
