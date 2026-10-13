<?php

namespace Tests\Feature;

use App\Models\JadwalDonor;
use App\Models\Pendonor;
use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahDetail;
use App\Models\RiwayatDonasi;
use App\Models\StokDarah;
use App\Models\User;
use Tests\TestCase;

class DemonstrationWalkthroughTest extends TestCase
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

    /**
     * 1. UJI HALAMAN PUBLIK & OTENTIKASI
     */
    public function test_demo_public_pages(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register-rs')->assertStatus(200);
    }

    /**
     * 2. UJI SEMUA HALAMAN MODUL ADMIN
     */
    public function test_demo_all_admin_pages(): void
    {
        $this->actingAs($this->getAdmin());

        $this->get('/admin/dashboard')->assertStatus(200);
        $this->get('/admin/stok-darah')->assertStatus(200);
        $this->get('/admin/permintaan-darah')->assertStatus(200);
        $this->get('/admin/kelola-petugas')->assertStatus(200);
        $this->get('/admin/jadwal-donor')->assertStatus(200);
        $this->get('/admin/form-jadwal')->assertStatus(200);
        $this->get('/admin/form-petugas')->assertStatus(200);
        $this->get('/admin/daftar-pendonor')->assertStatus(200);
        $this->get('/admin/export-semua')->assertStatus(200);
        $this->get('/admin/export-permintaan')->assertStatus(200);
    }

    /**
     * 3. UJI SEMUA HALAMAN MODUL PETUGAS
     */
    public function test_demo_all_petugas_pages(): void
    {
        $this->actingAs($this->getPetugas());

        $this->get('/petugas/dashboard')->assertStatus(200);
        $this->get('/petugas/daftar-pendonor')->assertStatus(200);
        $this->get('/petugas/form-pendonor')->assertStatus(200);
        $this->get('/petugas/riwayat-donasi')->assertStatus(200);
        $this->get('/petugas/form-riwayat')->assertStatus(200);
        $this->get('/petugas/jadwal-donor')->assertStatus(200);
        $this->get('/petugas/stok-darah')->assertStatus(200);
    }

    /**
     * 4. UJI SEMUA HALAMAN MODUL RUMAH SAKIT
     */
    public function test_demo_all_rumah_sakit_pages(): void
    {
        $this->actingAs($this->getHospital());

        $this->get('/rs/dashboard')->assertStatus(200);
        $this->get('/rs/permintaan')->assertStatus(200);
        $this->get('/rs/history-permintaan')->assertStatus(200);
        $this->get('/rs/stok-darah')->assertStatus(200);
        $this->get('/rs/profil')->assertStatus(200);
    }

    /**
     * 5. UJI INTEGRITAS DATABASE & ALUR END-TO-END DEMO UTP
     */
    public function test_demo_end_to_end_utp_flow(): void
    {
        $admin = $this->getAdmin();
        $petugas = $this->getPetugas();
        $hospital = $this->getHospital();

        // Langkah A: Petugas input donor baru
        $this->actingAs($petugas);
        $nik = '1871' . time();
        $resDonor = $this->post('/petugas/tambah-pendonor', [
            'nama'           => 'Calon Pendonor UTP',
            'no_identitas'   => $nik,
            'golongan_darah' => 'AB',
            'rhesus'         => '+',
            'telepon'        => '081234567899',
            'status'         => 'aktif',
            'status_donasi'  => 'Berhasil',
            'tekanan_darah'  => '120/80',
        ]);
        $resDonor->assertRedirect(route('petugas.daftar-pendonor'));

        // Langkah B: RS mengajukan permintaan darah AB+
        $this->actingAs($hospital);
        $patientName = 'Pasien Demo UTP ' . uniqid();
        $resReq = $this->post('/rs/permintaan', [
            'tujuan_permintaan' => 'pasien',
            'nama_pasien'       => $patientName,
            'no_pasien'         => 'RM-DEMO-' . time(),
            'ruangan'           => 'ICU Lantai 2',
            'dokter'            => 'Dr. Penguji UTP',
            'golongan'          => 'AB',
            'rhesus'            => '+',
            'jumlah'            => 1,
            'prioritas'         => 'Darurat',
            'keterangan'        => 'Pengujian UTP End-to-End',
        ]);
        $resReq->assertRedirect(route('rs.history-permintaan'));

        $permintaan = PermintaanDarah::where('keterangan', 'like', "%{$patientName}%")->orderBy('id', 'desc')->first();
        $this->assertNotNull($permintaan);
        $this->assertEquals('Pending', $permintaan->status);

        // Langkah C: Admin menyetujui permintaan (stok berkurang)
        $this->actingAs($admin);
        $resApprove = $this->get('/admin/terima-permintaan?id=' . $permintaan->id);
        $resApprove->assertRedirect(route('admin.permintaan-darah'));

        $permintaan->refresh();
        $this->assertEquals('Disetujui', $permintaan->status);

        // Langkah D: Admin mengirim permintaan dengan kurir
        $resShip = $this->post('/admin/kirim-permintaan', [
            'permintaan_id' => $permintaan->id,
            'kurir'         => 'Kurir Demo UTP - Kendaraan Dinas PMI',
        ]);
        $resShip->assertRedirect(route('admin.permintaan-darah'));

        $permintaan->refresh();
        $this->assertEquals('Dikirim', $permintaan->status);
        $this->assertEquals('Kurir Demo UTP - Kendaraan Dinas PMI', $permintaan->kurir);
    }
}
