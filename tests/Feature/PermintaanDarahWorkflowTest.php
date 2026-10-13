<?php

namespace Tests\Feature;

use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahDetail;
use App\Models\StokDarah;
use App\Models\User;
use Tests\TestCase;

class PermintaanDarahWorkflowTest extends TestCase
{
    private function getAdmin(): User
    {
        return User::where('role', 'admin')->first();
    }

    private function getHospital(): User
    {
        return User::where('role', 'rumahsakit')->where('status', 'aktif')->first();
    }

    public function test_hospital_can_create_blood_request(): void
    {
        $hospital = $this->getHospital();
        $this->actingAs($hospital);

        $response = $this->post('/rs/permintaan', [
            'tujuan_permintaan' => 'pasien',
            'nama_pasien'       => 'Ny. Rahmawati',
            'no_pasien'         => 'RM-10293',
            'ruangan'           => 'Paviliun Melati',
            'dokter'            => 'Dr. Hendra, Sp.A',
            'golongan'          => 'A',
            'rhesus'            => '+',
            'jumlah'            => 2,
            'prioritas'         => 'Darurat',
            'keterangan'        => 'Operasi segera',
        ]);

        $response->assertRedirect(route('rs.history-permintaan'));
        $this->assertDatabaseHas('permintaan_darah', [
            'user_id'   => $hospital->id,
            'status'    => 'Pending',
            'prioritas' => 'Darurat',
        ]);
    }

    public function test_hospital_only_sees_own_requests(): void
    {
        $hospital = $this->getHospital();
        $this->actingAs($hospital);

        $response = $this->get('/rs/history-permintaan');
        $response->assertStatus(200);

        // Pastikan hanya permintaan milik rumah sakit ini yang dimuat
        $permintaanList = $response->viewData('permintaanList');
        foreach ($permintaanList as $item) {
            $this->assertEquals($hospital->id, $item->user_id);
        }
    }

    public function test_admin_can_approve_request_and_stock_is_deducted(): void
    {
        $admin = $this->getAdmin();
        $hospital = $this->getHospital();

        // Siapkan stok awal
        $stok = StokDarah::where('golongan_darah', 'A')->where('rhesus', '+')->first();
        $stok->jumlah = 20;
        $stok->save();

        // Buat permintaan Pending
        $permintaan = PermintaanDarah::create([
            'user_id'    => $hospital->id,
            'status'     => 'Pending',
            'prioritas'  => 'Darurat',
            'keterangan' => 'Uji persetujuan stok',
        ]);
        PermintaanDarahDetail::create([
            'permintaan_id' => $permintaan->id,
            'golongan'      => 'A',
            'rhesus'        => '+',
            'jumlah'        => 3,
        ]);

        $this->actingAs($admin);
        $response = $this->get('/admin/terima-permintaan?id=' . $permintaan->id);
        $response->assertRedirect(route('admin.permintaan-darah'));

        // Cek status berubah jadi Disetujui
        $permintaan->refresh();
        $this->assertEquals('Disetujui', $permintaan->status);

        // Cek stok terpotong 3 kantong
        $stok->refresh();
        $this->assertEquals(17, $stok->jumlah);
    }

    public function test_admin_cannot_approve_request_twice_preventing_duplicate_deduction(): void
    {
        $admin = $this->getAdmin();
        $hospital = $this->getHospital();

        $stok = StokDarah::where('golongan_darah', 'A')->where('rhesus', '+')->first();
        $stok->jumlah = 20;
        $stok->save();

        $permintaan = PermintaanDarah::create([
            'user_id'    => $hospital->id,
            'status'     => 'Disetujui', // Sudah disetujui sebelumnya
            'prioritas'  => 'Darurat',
            'keterangan' => 'Sudah disetujui',
        ]);
        PermintaanDarahDetail::create([
            'permintaan_id' => $permintaan->id,
            'golongan'      => 'A',
            'rhesus'        => '+',
            'jumlah'        => 5,
        ]);

        $this->actingAs($admin);
        $response = $this->get('/admin/terima-permintaan?id=' . $permintaan->id);
        $response->assertSessionHas('warning');

        // Stok tidak boleh berkurang lagi
        $stok->refresh();
        $this->assertEquals(20, $stok->jumlah);
    }

    public function test_admin_cannot_approve_if_stock_insufficient_preventing_negative_stock(): void
    {
        $admin = $this->getAdmin();
        $hospital = $this->getHospital();

        // Set stok ke 1 kantong
        $stok = StokDarah::where('golongan_darah', 'B')->where('rhesus', '-')->first();
        $stok->jumlah = 1;
        $stok->save();

        $permintaan = PermintaanDarah::create([
            'user_id'    => $hospital->id,
            'status'     => 'Pending',
            'prioritas'  => 'Darurat',
            'keterangan' => 'Minta melebihi stok',
        ]);
        PermintaanDarahDetail::create([
            'permintaan_id' => $permintaan->id,
            'golongan'      => 'B',
            'rhesus'        => '-',
            'jumlah'        => 5, // Lebih besar dari stok (1)
        ]);

        $this->actingAs($admin);
        $response = $this->get('/admin/terima-permintaan?id=' . $permintaan->id);
        $response->assertSessionHas('error');

        // Status tetap Pending dan stok tidak menjadi negatif
        $permintaan->refresh();
        $stok->refresh();
        $this->assertEquals('Pending', $permintaan->status);
        $this->assertEquals(1, $stok->jumlah);
    }

    public function test_admin_can_reject_request_with_reason(): void
    {
        $admin = $this->getAdmin();
        $hospital = $this->getHospital();

        $permintaan = PermintaanDarah::create([
            'user_id'    => $hospital->id,
            'status'     => 'Pending',
            'prioritas'  => 'Biasa',
            'keterangan' => 'Permintaan untuk ditolak',
        ]);

        $this->actingAs($admin);
        $response = $this->post('/admin/tolak-permintaan', [
            'permintaan_id' => $permintaan->id,
            'alasan'        => 'Stok darah sedang diprioritaskan untuk penanganan darurat ICU.',
        ]);

        $response->assertRedirect(route('admin.permintaan-darah'));

        $permintaan->refresh();
        $this->assertEquals('Ditolak', $permintaan->status);
        $this->assertEquals('Stok darah sedang diprioritaskan untuk penanganan darurat ICU.', $permintaan->alasan_tolak);
    }

    public function test_admin_can_ship_approved_request(): void
    {
        $admin = $this->getAdmin();
        $hospital = $this->getHospital();

        $permintaan = PermintaanDarah::create([
            'user_id'    => $hospital->id,
            'status'     => 'Disetujui',
            'prioritas'  => 'Biasa',
            'keterangan' => 'Permintaan siap kirim',
        ]);

        $this->actingAs($admin);
        $response = $this->post('/admin/kirim-permintaan', [
            'permintaan_id' => $permintaan->id,
            'kurir'         => 'Ambulans Reaksi Cepat PMI Lampung BE 1234 CD',
        ]);

        $response->assertRedirect(route('admin.permintaan-darah'));

        $permintaan->refresh();
        $this->assertEquals('Dikirim', $permintaan->status);
        $this->assertEquals('Ambulans Reaksi Cepat PMI Lampung BE 1234 CD', $permintaan->kurir);
    }
}
