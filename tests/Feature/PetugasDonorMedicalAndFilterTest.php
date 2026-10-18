<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\RiwayatDonasi;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasDonorMedicalAndFilterTest extends TestCase
{
    private User $petugas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::firstOrCreate(
            ['username' => 'petugas_tester'],
            [
                'name'     => 'Petugas Tester',
                'email'    => 'petugas_tester@example.com',
                'role'     => 'petugas',
                'password' => bcrypt('password123'),
                'status'   => 'aktif',
            ]
        );
    }

    public function test_donor_creation_auto_increments_id_and_stores_medical_and_donation_data()
    {
        $nik = '320101' . rand(1000000000, 9999999999);

        $response = $this->actingAs($this->petugas)->post('/index.php?page=petugas-tambah-pendonor', [
            'nama'           => 'Budi Darmawan',
            'no_identitas'   => $nik,
            'golongan_darah' => 'B',
            'rhesus'         => '+',
            'telepon'        => '081234567890',
            'email'          => 'budi.darmawan@example.com',
            'tanggal_lahir'  => '1995-05-15',
            'jenis_kelamin'  => 'Laki-laki',
            'alamat'         => 'Jl. Pahlawan Medis No. 10',
            'kota'           => 'Surabaya',
            'provinsi'       => 'Jawa Timur',
            'pekerjaan'      => 'Karyawan Swasta',
            'status'         => 'aktif',
            'tekanan_darah'  => '118/78',
            'detak_jantung'  => '74',
            'hemoglobin'     => '15.2',
            'suhu_tubuh'     => '36.6',
            'kesehatan_umum' => 'Sehat Baik',
            'catatan'        => 'Kondisi pendonor sangat prima',
            'jumlah'         => 1,
            'status_donasi'  => 'Berhasil',
        ]);

        $response->assertRedirect();

        // Verify Pendonor record
        $pendonor = Pendonor::where('nik', $nik)->first();
        $this->assertNotNull($pendonor);
        $this->assertGreaterThan(0, $pendonor->id); // Auto-incremented
        $this->assertEquals('Budi Darmawan', $pendonor->nama);
        $this->assertEquals('B', $pendonor->golongan);
        $this->assertEquals('+', $pendonor->rhesus);
        $this->assertEquals('118/78', $pendonor->tekanan_darah);
        $this->assertEquals('74', $pendonor->detak_jantung);
        $this->assertEquals('15.2', $pendonor->hemoglobin);
        $this->assertEquals('36.6', $pendonor->suhu_tubuh);
        $this->assertEquals('Sehat Baik', $pendonor->kesehatan_umum);
        $this->assertEquals('Kondisi pendonor sangat prima', $pendonor->catatan);

        // Verify RiwayatDonasi record created simultaneously from donor registration form
        $riwayat = RiwayatDonasi::where('pendonor_id', $pendonor->id)->first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('B', $riwayat->golongan);
        $this->assertEquals('+', $riwayat->rhesus);
        $this->assertEquals(1, $riwayat->jumlah);
        $this->assertEquals('Berhasil', $riwayat->status);
        $this->assertEquals('118/78', $riwayat->tekanan_darah);
        $this->assertEquals('74', $riwayat->detak_jantung);
        $this->assertEquals('15.2', $riwayat->hemoglobin);
        $this->assertEquals('36.6', $riwayat->suhu_tubuh);
        $this->assertEquals('Sehat Baik', $riwayat->kesehatan_umum);
        $this->assertEquals('Kondisi pendonor sangat prima', $riwayat->catatan);
    }

    public function test_petugas_can_edit_donor_and_update_medical_records()
    {
        $nik = '320202' . rand(1000000000, 9999999999);
        $pendonor = Pendonor::create([
            'nama'           => 'Siti Aisyah',
            'nik'            => $nik,
            'golongan'       => 'A',
            'rhesus'         => '+',
            'telepon'        => '081298765432',
            'email'          => 'siti@example.com',
            'tanggal_lahir'  => '1998-08-20',
            'jenis_kelamin'  => 'Perempuan',
            'alamat'         => 'Jl. Mawar No. 5',
            'status'         => 'aktif',
            'tekanan_darah'  => '120/80',
            'detak_jantung'  => '72',
            'hemoglobin'     => '14.0',
            'suhu_tubuh'     => '36.5',
            'kesehatan_umum' => 'Sehat Baik',
        ]);

        $response = $this->actingAs($this->petugas)->post('/index.php?page=petugas-edit-pendonor', [
            'id'             => $pendonor->id,
            'nama'           => 'Siti Aisyah Updated',
            'nik'            => $nik,
            'golongan_darah' => 'A',
            'rhesus'         => '-',
            'telepon'        => '081298765432',
            'email'          => 'siti.updated@example.com',
            'tanggal_lahir'  => '1998-08-20',
            'jenis_kelamin'  => 'Perempuan',
            'alamat'         => 'Jl. Melati No. 12',
            'status'         => 'aktif',
            'tekanan_darah'  => '110/70',
            'detak_jantung'  => '68',
            'hemoglobin'     => '13.8',
            'suhu_tubuh'     => '36.4',
            'kesehatan_umum' => 'Sehat Baik',
            'catatan'        => 'Pemeriksaan tensi rutin terkontrol',
        ]);

        $response->assertRedirect();

        $fresh = $pendonor->fresh();
        $this->assertEquals('Siti Aisyah Updated', $fresh->nama);
        $this->assertEquals('-', $fresh->rhesus);
        $this->assertEquals('110/70', $fresh->tekanan_darah);
        $this->assertEquals('68', $fresh->detak_jantung);
        $this->assertEquals('13.8', $fresh->hemoglobin);
        $this->assertEquals('36.4', $fresh->suhu_tubuh);
        $this->assertEquals('Pemeriksaan tensi rutin terkontrol', $fresh->catatan);
    }

    public function test_daftar_pendonor_page_renders_filter_options_and_auto_increment_indicators()
    {
        $response = $this->actingAs($this->petugas)->get('/index.php?page=petugas-daftar-pendonor');

        $response->assertStatus(200);
        $response->assertSee('id="resetPendonorFilter"', false);
        $response->assertSee('id="filterGolDarah"', false);
        $response->assertSee('value="A"', false);
        $response->assertSee('value="A+"', false);
        $response->assertSee('value="A-"', false);
        $response->assertSee('value="B+"', false);
        $response->assertSee('value="O+"', false);
        $response->assertSee('tambahPendonorModal', false);
        $response->assertDontSee('Auto-Increment', false);
        $response->assertSee('tekanan_darah', false);
        $response->assertSee('detak_jantung', false);
        $response->assertSee('hemoglobin', false);
        $response->assertSee('suhu_tubuh', false);
    }

    public function test_server_side_golongan_filtering_matches_parent_group_and_specific_rhesus()
    {
        $nikAplus = '320303' . rand(1000000000, 9999999999);
        $nikAminus = '320304' . rand(1000000000, 9999999999);
        $nikBplus = '320305' . rand(1000000000, 9999999999);

        Pendonor::create([
            'nama' => 'Donor Alpha Positif',
            'nik' => $nikAplus,
            'golongan' => 'A',
            'rhesus' => '+',
            'telepon' => '0811111111',
            'status' => 'aktif',
        ]);

        Pendonor::create([
            'nama' => 'Donor Alpha Negatif',
            'nik' => $nikAminus,
            'golongan' => 'A',
            'rhesus' => '-',
            'telepon' => '0822222222',
            'status' => 'aktif',
        ]);

        Pendonor::create([
            'nama' => 'Donor Beta Positif',
            'nik' => $nikBplus,
            'golongan' => 'B',
            'rhesus' => '+',
            'telepon' => '0833333333',
            'status' => 'aktif',
        ]);

        // 1. Filter parent "A" returns both A+ and A-
        $resA = $this->actingAs($this->petugas)->get('/index.php?page=petugas-daftar-pendonor&golongan=A');
        $resA->assertStatus(200);
        $resA->assertSee('Donor Alpha Positif');
        $resA->assertSee('Donor Alpha Negatif');
        $resA->assertDontSee('Donor Beta Positif');

        // 2. Filter specific "A+" returns only A+
        $resAplus = $this->actingAs($this->petugas)->get('/index.php?page=petugas-daftar-pendonor&golongan=A%2B');
        $resAplus->assertStatus(200);
        $resAplus->assertSee('Donor Alpha Positif');
        $resAplus->assertDontSee('Donor Alpha Negatif');
        $resAplus->assertDontSee('Donor Beta Positif');
    }

    public function test_donor_creation_with_combined_blood_type_like_A_plus_and_O_minus()
    {
        $nik = '320505' . rand(1000000000, 9999999999);

        $response = $this->actingAs($this->petugas)->post('/index.php?page=petugas-tambah-pendonor', [
            'nama'           => 'Doni Pratama',
            'no_identitas'   => $nik,
            'golongan_darah' => 'A+', // Submitted as A+
            'rhesus'         => '+',
            'telepon'        => '081233445566',
            'status'         => 'aktif',
            'status_donasi'  => 'Berhasil',
        ]);

        $response->assertRedirect();

        $pendonor = Pendonor::where('nik', $nik)->first();
        $this->assertNotNull($pendonor);
        $this->assertEquals('A', $pendonor->golongan);
        $this->assertEquals('+', $pendonor->rhesus);

        // Edit using O-
        $editResponse = $this->actingAs($this->petugas)->post('/index.php?page=petugas-edit-pendonor', [
            'id'             => $pendonor->id,
            'nama'           => 'Doni Pratama Updated',
            'nik'            => $nik,
            'golongan_darah' => 'O-', // Submitted as O-
            'rhesus'         => '-',
            'telepon'        => '081233445566',
            'status'         => 'aktif',
        ]);

        $editResponse->assertRedirect();
        $fresh = $pendonor->fresh();
        $this->assertEquals('O', $fresh->golongan);
        $this->assertEquals('-', $fresh->rhesus);
    }
}

