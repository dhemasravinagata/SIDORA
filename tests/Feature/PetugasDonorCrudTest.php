<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\StokDarah;
use App\Models\User;
use Tests\TestCase;

class PetugasDonorCrudTest extends TestCase
{
    private function getPetugas(): User
    {
        return User::where('role', 'petugas')->first();
    }

    public function test_petugas_can_view_donor_list(): void
    {
        $this->actingAs($this->getPetugas());
        $response = $this->get('/petugas/daftar-pendonor');
        $response->assertStatus(200);
    }

    public function test_petugas_can_create_new_donor(): void
    {
        $this->actingAs($this->getPetugas());

        $nik = '18719999' . rand(10000000, 99999999);
        $response = $this->post('/petugas/tambah-pendonor', [
            'nama'           => 'Doni Pratama',
            'no_identitas'   => $nik,
            'golongan_darah' => 'B',
            'rhesus'         => '+',
            'telepon'        => '081234567899',
            'status'         => 'aktif',
            'status_donasi'  => 'Berhasil',
            'tekanan_darah'  => '120/80',
        ]);

        $response->assertRedirect(route('petugas.daftar-pendonor'));
        $this->assertDatabaseHas('pendonor', ['nik' => $nik, 'nama' => 'Doni Pratama']);
    }

    public function test_donor_creation_validates_duplicate_nik(): void
    {
        $this->actingAs($this->getPetugas());
        $existing = Pendonor::first();

        $response = $this->post('/petugas/tambah-pendonor', [
            'nama'           => 'Nama Lain',
            'no_identitas'   => $existing->nik,
            'golongan_darah' => 'A',
            'rhesus'         => '+',
            'telepon'        => '081234567800',
        ]);

        $response->assertSessionHasErrors('no_identitas');
    }

    public function test_petugas_can_update_donor(): void
    {
        $this->actingAs($this->getPetugas());
        $donor = Pendonor::first();

        $response = $this->post('/petugas/edit-pendonor', [
            'id'             => $donor->id,
            'nama'           => $donor->nama . ' Updated',
            'nik'            => $donor->nik,
            'golongan_darah' => $donor->golongan,
            'rhesus'         => $donor->rhesus,
            'telepon'        => '081234567899',
            'status'         => 'aktif',
        ]);

        $response->assertRedirect(route('petugas.daftar-pendonor'));
        $this->assertDatabaseHas('pendonor', ['id' => $donor->id, 'nama' => $donor->nama . ' Updated']);
    }

    public function test_donation_with_success_status_increases_stock(): void
    {
        $this->actingAs($this->getPetugas());
        $donor = Pendonor::first();

        $stokBefore = StokDarah::where('golongan_darah', 'O')->where('rhesus', '+')->first();
        $initialQty = $stokBefore ? $stokBefore->jumlah : 0;

        $response = $this->post('/petugas/tambah-riwayat', [
            'pendonor_id'   => $donor->id,
            'golongan'      => 'O',
            'rhesus'        => '+',
            'jumlah'        => 2,
            'tanggal'       => now()->toDateString(),
            'status'        => 'Berhasil',
            'tekanan_darah' => '120/80',
        ]);

        $response->assertRedirect(route('petugas.riwayat-donasi'));

        $stokAfter = StokDarah::where('golongan_darah', 'O')->where('rhesus', '+')->first();
        $this->assertEquals($initialQty + 2, $stokAfter->jumlah);
    }
}
