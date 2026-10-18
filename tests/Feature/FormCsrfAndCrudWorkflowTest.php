<?php

namespace Tests\Feature;

use App\Models\JadwalDonor;
use App\Models\Pendonor;
use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahDetail;
use App\Models\RiwayatDonasi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FormCsrfAndCrudWorkflowTest extends TestCase
{
    private function getAdmin(): User
    {
        return User::where('role', 'admin')->first();
    }

    private function getPetugas(): User
    {
        return User::where('role', 'petugas')->first();
    }

    private function getHospital(): User
    {
        return User::where('role', 'rumahsakit')->where('status', 'aktif')->first();
    }

    public function test_all_rendered_post_forms_contain_csrf_token(): void
    {
        // 1. Check Petugas pages
        $this->actingAs($this->getPetugas());
        $respDaftar = $this->get('/petugas/daftar-pendonor');
        $respDaftar->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respDaftar->getContent());

        $respFormPendonor = $this->get('/petugas/form-pendonor');
        $respFormPendonor->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respFormPendonor->getContent());

        $respFormRiwayat = $this->get('/petugas/form-riwayat');
        $respFormRiwayat->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respFormRiwayat->getContent());

        $respRiwayatDonasi = $this->get('/petugas/riwayat-donasi');
        $respRiwayatDonasi->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respRiwayatDonasi->getContent());

        // 2. Check Admin pages
        $this->actingAs($this->getAdmin());
        $respAdminDashboard = $this->get('/admin/dashboard');
        $respAdminDashboard->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respAdminDashboard->getContent());

        $respKelolaPetugas = $this->get('/admin/kelola-petugas');
        $respKelolaPetugas->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respKelolaPetugas->getContent());

        $respJadwalDonor = $this->get('/admin/jadwal-donor');
        $respJadwalDonor->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respJadwalDonor->getContent());

        $respFormJadwal = $this->get('/admin/form-jadwal');
        $respFormJadwal->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respFormJadwal->getContent());

        $respFormPetugas = $this->get('/admin/form-petugas');
        $respFormPetugas->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respFormPetugas->getContent());

        $respStokDarah = $this->get('/admin/stok-darah');
        $respStokDarah->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respStokDarah->getContent());

        $respPermintaanDarah = $this->get('/admin/permintaan-darah');
        $respPermintaanDarah->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respPermintaanDarah->getContent());

        // 3. Check Rumah Sakit pages
        $this->actingAs($this->getHospital());
        $respRsPermintaan = $this->get('/rs/permintaan');
        $respRsPermintaan->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respRsPermintaan->getContent());

        $respRsProfil = $this->get('/rs/profil');
        $respRsProfil->assertStatus(200);
        $this->assertStringContainsString('name="_token"', $respRsProfil->getContent());
    }

    public function test_petugas_can_edit_donor_via_legacy_route(): void
    {
        $this->actingAs($this->getPetugas());
        $donor = Pendonor::first();

        $newName = 'Updated Donor Name ' . rand(100, 999);
        $response = $this->post('/index.php?page=petugas-edit-pendonor', [
            'id'             => $donor->id,
            'nama'           => $newName,
            'nik'            => $donor->nik,
            'golongan_darah' => $donor->golongan,
            'rhesus'         => $donor->rhesus,
            'telepon'        => '081299998888',
            'status'         => 'aktif',
        ]);

        $response->assertRedirect(route('petugas.daftar-pendonor'));
        $this->assertDatabaseHas('pendonor', [
            'id'   => $donor->id,
            'nama' => $newName,
        ]);
    }

    public function test_petugas_can_delete_donor_via_legacy_route(): void
    {
        $this->actingAs($this->getPetugas());
        $tempDonor = Pendonor::create([
            'nama'          => 'Temporary Delete Donor',
            'nik'           => '1871' . rand(1000000000, 9999999999),
            'golongan'      => 'A',
            'rhesus'        => '+',
            'telepon'       => '081234567890',
            'status'        => 'aktif',
        ]);

        $response = $this->post('/index.php?page=petugas-hapus-pendonor', [
            'id' => $tempDonor->id,
        ]);

        $response->assertRedirect(route('petugas.daftar-pendonor'));
        $this->assertDatabaseMissing('pendonor', ['id' => $tempDonor->id]);
    }

    public function test_admin_can_edit_and_delete_jadwal_via_legacy_route(): void
    {
        $this->actingAs($this->getAdmin());

        $jadwal = JadwalDonor::create([
            'lokasi'        => 'Lokasi Awal Donor',
            'tanggal'       => now()->addDays(5)->toDateString(),
            'target'        => 50,
            'waktu_mulai'   => '09:00',
            'waktu_selesai' => '13:00',
            'status'        => 'Akan Datang',
        ]);

        // Edit
        $response = $this->post('/index.php?page=admin-edit-jadwal', [
            'id'            => $jadwal->id,
            'lokasi'        => 'Lokasi Update Donor',
            'tanggal'       => now()->addDays(6)->toDateString(),
            'target'        => 75,
            'waktu_mulai'   => '08:00',
            'waktu_selesai' => '12:00',
            'status'        => 'Sedang Berlangsung',
        ]);
        $response->assertRedirect(route('admin.jadwal-donor'));
        $this->assertDatabaseHas('jadwal_donor', [
            'id'     => $jadwal->id,
            'lokasi' => 'Lokasi Update Donor',
            'target' => 75,
        ]);

        // Delete
        $delResp = $this->post('/index.php?page=admin-hapus-jadwal', [
            'id' => $jadwal->id,
        ]);
        $delResp->assertRedirect(route('admin.jadwal-donor'));
        $this->assertDatabaseMissing('jadwal_donor', ['id' => $jadwal->id]);
    }

    public function test_admin_can_edit_petugas_via_legacy_route(): void
    {
        $this->actingAs($this->getAdmin());
        $petugas = $this->getPetugas();

        $response = $this->post('/index.php?page=admin-edit-petugas', [
            'id'       => $petugas->id,
            'nama'     => 'Petugas Edited Name',
            'email'    => $petugas->email,
            'username' => $petugas->username,
            'telepon'  => '081233445566',
            'status'   => 'aktif',
        ]);

        $response->assertRedirect(route('admin.kelola-petugas'));
        $this->assertDatabaseHas('users', [
            'id'   => $petugas->id,
            'name' => 'Petugas Edited Name',
        ]);
    }

    public function test_rs_can_update_profil_and_password_via_legacy_route(): void
    {
        $hospital = $this->getHospital();
        $hospital->update(['password' => Hash::make('password')]);
        $this->actingAs($hospital);

        // Update profil
        $newRsName = 'RSUD Abdul Moeloek Updated';
        $response = $this->post('/index.php?page=rs-profil-update', [
            'nama'     => $newRsName,
            'username' => $hospital->username,
            'telepon'  => '0721-998877',
            'alamat'   => 'Jl. Dr. Rivai No. 6 Baru',
        ]);
        $response->assertRedirect(route('rs.profil'));
        $this->assertDatabaseHas('users', [
            'id'   => $hospital->id,
            'name' => $newRsName,
        ]);

        // Ubah password
        $passResp = $this->post('/index.php?page=rs-ubah-password', [
            'old_password'     => 'password',
            'new_password'     => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);
        $passResp->assertRedirect(route('rs.profil'));

        $hospital->refresh();
        $this->assertTrue(Hash::check('newpassword123', $hospital->password));

        // Revert password back to 'password' for consistency
        $hospital->update(['password' => Hash::make('password')]);
    }
}
