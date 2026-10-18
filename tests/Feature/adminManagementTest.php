<?php

namespace Tests\Feature;

use App\Models\JadwalDonor;
use App\Models\User;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    private function getAdmin(): User
    {
        return User::where('role', 'admin')->first();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $this->actingAs($this->getAdmin());
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_can_view_stok_darah(): void
    {
        $this->actingAs($this->getAdmin());
        $response = $this->get('/admin/stok-darah');
        $response->assertStatus(200);
    }

    public function test_admin_can_add_petugas(): void
    {
        $this->actingAs($this->getAdmin());

        $random = rand(100, 999);
        $response = $this->post('/admin/tambah-petugas', [
            'nama'     => "Petugas Lab {$random}",
            'email'    => "petugas{$random}@sidora.com",
            'username' => "petugas{$random}",
            'password' => 'secret123',
            'telepon'  => '081234567899',
            'status'   => 'aktif',
        ]);

        $response->assertRedirect(route('admin.kelola-petugas'));
        $this->assertDatabaseHas('users', ['username' => "petugas{$random}", 'role' => 'petugas']);
    }

    public function test_admin_can_create_jadwal_donor(): void
    {
        $this->actingAs($this->getAdmin());

        // Check that form-jadwal does NOT contain status field
        $formResponse = $this->get('/index.php?page=admin-form-jadwal');
        $formResponse->assertStatus(200);
        $formResponse->assertDontSee('name="status"', false);

        $response = $this->post('/admin/tambah-jadwal', [
            'lokasi'        => 'Aula Gedung Serbaguna Unila',
            'tanggal'       => now()->addDays(5)->toDateString(),
            'target'        => 120,
            'waktu_mulai'   => '08:00',
            'waktu_selesai' => '13:00',
            // Notice: status is NOT provided
            'catatan'       => 'Kerjasama KSR PMI Unit Unila',
        ]);

        $response->assertRedirect(route('admin.jadwal-donor'));
        $this->assertDatabaseHas('jadwal_donor', [
            'lokasi' => 'Aula Gedung Serbaguna Unila',
            'status' => 'Akan Datang',
        ]);
    }

    public function test_admin_can_approve_hospital_account(): void
    {
        $this->actingAs($this->getAdmin());

        $hospital = User::where('role', 'rumahsakit')->where('status', 'pending')->first();
        if ($hospital) {
            $response = $this->post('/admin/approve-rs', [
                'id'     => $hospital->id,
                'status' => 'aktif',
            ]);

            $response->assertRedirect(route('admin.kelola-petugas'));
            $hospital->refresh();
            $this->assertEquals('aktif', $hospital->status);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_admin_can_export_csv_reports(): void
    {
        $this->actingAs($this->getAdmin());

        $response = $this->get('/admin/export-semua');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_admin_kelola_user_page_renders_filters_and_containers(): void
    {
        $this->actingAs($this->getAdmin());

        $response = $this->get('/index.php?page=admin-kelola-petugas');
        $response->assertStatus(200);
        $response->assertSee('id="filterTarget"', false);
        $response->assertSee('id="searchUser"', false);
        $response->assertSee('id="filterStatus"', false);
        $response->assertSee('id="resetUserFilter"', false);
        $response->assertSee('id="containerRumahSakit"', false);
        $response->assertSee('id="containerPetugas"', false);
        $response->assertSee('id="tabelRumahSakit"', false);
        $response->assertSee('id="tabelPetugas"', false);
    }

    public function test_admin_permintaan_darah_page_renders_filters(): void
    {
        $this->actingAs($this->getAdmin());

        $response = $this->get('/index.php?page=admin-permintaan-darah');
        $response->assertStatus(200);
        $response->assertSee('id="searchPermintaan"', false);
        $response->assertSee('id="filterStatus"', false);
        $response->assertSee('id="filterPrioritas"', false);
        $response->assertSee('id="resetBtn"', false);
    }

    public function test_admin_jadwal_donor_page_renders_filters(): void
    {
        $this->actingAs($this->getAdmin());

        $response = $this->get('/index.php?page=admin-jadwal-donor');
        $response->assertStatus(200);
        $response->assertSee('id="searchJadwal"', false);
        $response->assertSee('id="filterTanggal"', false);
        $response->assertSee('id="resetJadwalBtn"', false);
    }
}
