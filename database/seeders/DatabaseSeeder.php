<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $passwordHash = Hash::make('password');

        $users = [
            [
                'id'         => 1,
                'name'       => 'Administrator SIDORA',
                'username'   => 'admin',
                'email'      => 'admin@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'admin',
                'status'     => 'aktif',
                'telepon'    => '081234567800',
                'kontak'     => 'Admin Utama',
                'alamat'     => 'Kantor Pusat PMI Kota Bandar Lampung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'Petugas Donasi 1',
                'username'   => 'petugas1',
                'email'      => 'petugas1@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'petugas',
                'status'     => 'aktif',
                'telepon'    => '081234567801',
                'kontak'     => 'Petugas Lapangan',
                'alamat'     => 'Unit Transfusi Darah PMI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'name'       => 'Petugas Donasi 2',
                'username'   => 'petugas2',
                'email'      => 'petugas2@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'petugas',
                'status'     => 'aktif',
                'telepon'    => '081234567802',
                'kontak'     => 'Petugas Laboratorium',
                'alamat'     => 'Unit Transfusi Darah PMI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'name'       => 'RSUD Dr. H. Abdul Moeloek',
                'username'   => 'rsud_abdulmoeloek',
                'email'      => 'rsud@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'rumahsakit',
                'status'     => 'aktif',
                'telepon'    => '0721-123456',
                'kontak'     => 'Dr. Budi Santoso, Sp.PK',
                'alamat'     => 'Jl. Dr. Rivai No.6, Penengahan',
                'kecamatan'  => 'Tanjung Karang Pusat',
                'desa'       => 'Penengahan',
                'kode_pos'   => '35112',
                'provinsi'   => 'Lampung',
                'tipe_rs'    => 'RS Umum Daerah',
                'no_izin'    => 'IZIN-RS-001/2020',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 5,
                'name'       => 'RS Bhayangkara Polda Lampung',
                'username'   => 'rs_bhayangkara',
                'email'      => 'bhayangkara@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'rumahsakit',
                'status'     => 'aktif',
                'telepon'    => '0721-654321',
                'kontak'     => 'Dr. Andi Pratama',
                'alamat'     => 'Jl. Pramuka No.88',
                'kecamatan'  => 'Rajabasa',
                'desa'       => 'Rajabasa',
                'kode_pos'   => '35144',
                'provinsi'   => 'Lampung',
                'tipe_rs'    => 'RS Kepolisian',
                'no_izin'    => 'IZIN-RS-002/2021',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 6,
                'name'       => 'RS Advent Bandar Lampung',
                'username'   => 'rs_advent',
                'email'      => 'advent@sidora.com',
                'password'   => $passwordHash,
                'role'       => 'rumahsakit',
                'status'     => 'pending',
                'telepon'    => '0721-703456',
                'kontak'     => 'Dr. Maria',
                'alamat'     => 'Jl. Teuku Umar No.48',
                'kecamatan'  => 'Kedaton',
                'desa'       => 'Kedaton',
                'kode_pos'   => '35141',
                'provinsi'   => 'Lampung',
                'tipe_rs'    => 'RS Swasta',
                'no_izin'    => 'IZIN-RS-003/2022',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        // Reset sequence for users in PostgreSQL
        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");

        // 2. Stok Darah
        $stok = [
            ['id' => 1, 'golongan_darah' => 'A',  'rhesus' => '+', 'jumlah' => 46, 'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'golongan_darah' => 'A',  'rhesus' => '-', 'jumlah' => 10, 'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'golongan_darah' => 'B',  'rhesus' => '+', 'jumlah' => 15, 'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'golongan_darah' => 'B',  'rhesus' => '-', 'jumlah' => 3,  'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'golongan_darah' => 'AB', 'rhesus' => '+', 'jumlah' => 41, 'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'golongan_darah' => 'AB', 'rhesus' => '-', 'jumlah' => 5,  'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'golongan_darah' => 'O',  'rhesus' => '+', 'jumlah' => 38, 'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'golongan_darah' => 'O',  'rhesus' => '-', 'jumlah' => 1,  'min_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('stok_darah')->insert($stok);
        DB::statement("SELECT setval('stok_darah_id_seq', (SELECT MAX(id) FROM stok_darah))");

        // 3. Pendonor
        $pendonor = [
            [
                'id'            => 1,
                'nama'          => 'Ahmad Sanusi',
                'nik'           => '1871012345678901',
                'telepon'       => '081234567890',
                'email'         => 'ahmad@gmail.com',
                'tanggal_lahir' => '1990-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Raden Intan No. 12',
                'kota'          => 'Bandar Lampung',
                'provinsi'      => 'Lampung',
                'pekerjaan'     => 'PNS',
                'golongan'      => 'A',
                'rhesus'        => '+',
                'status'        => 'aktif',
                'tgl_donor'     => '2026-06-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 2,
                'nama'          => 'Siti Aminah',
                'nik'           => '1871012345678902',
                'telepon'       => '081234567891',
                'email'         => 'siti@gmail.com',
                'tanggal_lahir' => '1995-08-20',
                'jenis_kelamin' => 'Perempuan',
                'alamat'        => 'Jl. Kartini No. 45',
                'kota'          => 'Bandar Lampung',
                'provinsi'      => 'Lampung',
                'pekerjaan'     => 'Karyawan Swasta',
                'golongan'      => 'O',
                'rhesus'        => '+',
                'status'        => 'aktif',
                'tgl_donor'     => '2026-06-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 3,
                'nama'          => 'Budi Santoso',
                'nik'           => '1871012345678903',
                'telepon'       => '081234567892',
                'email'         => 'budi@gmail.com',
                'tanggal_lahir' => '1988-11-10',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Teuku Umar No. 8',
                'kota'          => 'Bandar Lampung',
                'provinsi'      => 'Lampung',
                'pekerjaan'     => 'Wiraswasta',
                'golongan'      => 'B',
                'rhesus'        => '+',
                'status'        => 'aktif',
                'tgl_donor'     => '2026-06-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 4,
                'nama'          => 'Rina Wati',
                'nik'           => '1871012345678904',
                'telepon'       => '081234567893',
                'email'         => 'rina@gmail.com',
                'tanggal_lahir' => '1992-02-28',
                'jenis_kelamin' => 'Perempuan',
                'alamat'        => 'Jl. ZA Pagar Alam No. 19',
                'kota'          => 'Bandar Lampung',
                'provinsi'      => 'Lampung',
                'pekerjaan'     => 'Mahasiswa',
                'golongan'      => 'AB',
                'rhesus'        => '+',
                'status'        => 'aktif',
                'tgl_donor'     => '2026-06-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 5,
                'nama'          => 'Joko Susilo',
                'nik'           => '1871012345678905',
                'telepon'       => '081234567894',
                'email'         => 'joko@gmail.com',
                'tanggal_lahir' => '1985-07-12',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Sultan Agung No. 25',
                'kota'          => 'Bandar Lampung',
                'provinsi'      => 'Lampung',
                'pekerjaan'     => 'Guru',
                'golongan'      => 'O',
                'rhesus'        => '-',
                'status'        => 'aktif',
                'tgl_donor'     => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];
        DB::table('pendonor')->insert($pendonor);
        DB::statement("SELECT setval('pendonor_id_seq', (SELECT MAX(id) FROM pendonor))");

        // 4. Jadwal Donor
        $jadwal = [
            [
                'id'            => 1,
                'lokasi'        => 'PMI Cabang Bandar Lampung',
                'tanggal'       => '2026-10-20',
                'target'        => 100,
                'terdaftar'     => 85,
                'waktu_mulai'   => '08:00:00',
                'waktu_selesai' => '14:00:00',
                'status'        => 'Selesai',
                'catatan'       => 'Kegiatan rutin berjalan lancar',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 2,
                'lokasi'        => 'Universitas Lampung (Gedung Rektorat)',
                'tanggal'       => '2026-10-25',
                'target'        => 150,
                'terdaftar'     => 45,
                'waktu_mulai'   => '09:00:00',
                'waktu_selesai' => '15:00:00',
                'status'        => 'Aktif',
                'catatan'       => 'Kegiatan donor darah bersama BEM U KBM UNILA',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 3,
                'lokasi'        => 'Mall Boemi Kedaton',
                'tanggal'       => '2026-11-01',
                'target'        => 200,
                'terdaftar'     => 0,
                'waktu_mulai'   => '10:00:00',
                'waktu_selesai' => '16:00:00',
                'status'        => 'Akan Datang',
                'catatan'       => 'Event spesial donor darah di pusat perbelanjaan',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];
        DB::table('jadwal_donor')->insert($jadwal);
        DB::statement("SELECT setval('jadwal_donor_id_seq', (SELECT MAX(id) FROM jadwal_donor))");

        // 5. Riwayat Donasi
        $riwayat = [
            [
                'id'            => 1,
                'pendonor_id'   => 1,
                'golongan'      => 'A',
                'rhesus'        => '+',
                'jumlah'        => 1,
                'tekanan_darah' => '120/80',
                'status'        => 'Berhasil',
                'tanggal'       => '2026-10-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 2,
                'pendonor_id'   => 2,
                'golongan'      => 'O',
                'rhesus'        => '+',
                'jumlah'        => 1,
                'tekanan_darah' => '110/70',
                'status'        => 'Berhasil',
                'tanggal'       => '2026-10-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 3,
                'pendonor_id'   => 3,
                'golongan'      => 'B',
                'rhesus'        => '+',
                'jumlah'        => 0,
                'tekanan_darah' => '150/90',
                'status'        => 'Ditolak',
                'tanggal'       => '2026-10-01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];
        DB::table('riwayat_donasi')->insert($riwayat);
        DB::statement("SELECT setval('riwayat_donasi_id_seq', (SELECT MAX(id) FROM riwayat_donasi))");

        // 6. Permintaan Darah
        $permintaan = [
            [
                'id'            => 1,
                'user_id'       => 4,
                'status'        => 'Pending',
                'prioritas'     => 'Darurat',
                'keterangan'    => 'Tujuan: pasien. Pasien: Ny. Siti (No RM: RM-9871). Ruangan: ICU 03. Dokter: Dr. Hartono, Sp.An. Catatan: Pasien operasi darurat bedah toraks.',
                'alasan_tolak'  => null,
                'tanggal_tolak' => null,
                'kurir'         => null,
                'tanggal_kirim' => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 2,
                'user_id'       => 5,
                'status'        => 'Disetujui',
                'prioritas'     => 'Penting',
                'keterangan'    => 'Tujuan: stok. Keperluan: Penambahan Stok BDRS (Dropping). Catatan: Persiapan stok IGD',
                'alasan_tolak'  => null,
                'tanggal_tolak' => null,
                'kurir'         => null,
                'tanggal_kirim' => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 3,
                'user_id'       => 4,
                'status'        => 'Ditolak',
                'prioritas'     => 'Biasa',
                'keterangan'    => 'Tujuan: stok. Keperluan: Penambahan Stok BDRS (Dropping). Catatan: Permintaan cadangan',
                'alasan_tolak'  => 'Stok PMI untuk golongan darah AB- sedang di bawah batas minimum.',
                'tanggal_tolak' => now()->toDateString(),
                'kurir'         => null,
                'tanggal_kirim' => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id'            => 4,
                'user_id'       => 5,
                'status'        => 'Dikirim',
                'prioritas'     => 'Sangat Mendesak',
                'keterangan'    => 'Tujuan: pasien. Pasien: Tn. Andi (No RM: RM-5511). Ruangan: IGD Resusitasi. Dokter: Dr. Faisal, Sp.B. Catatan: Pasien trauma kecelakaan.',
                'alasan_tolak'  => null,
                'tanggal_tolak' => null,
                'kurir'         => 'Ambulans PMI Lampung - BE 1902 AZ',
                'tanggal_kirim' => now()->toDateString(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];
        DB::table('permintaan_darah')->insert($permintaan);
        DB::statement("SELECT setval('permintaan_darah_id_seq', (SELECT MAX(id) FROM permintaan_darah))");

        // 7. Permintaan Darah Detail
        $details = [
            ['id' => 1, 'permintaan_id' => 1, 'golongan' => 'O',  'rhesus' => '+', 'jumlah' => 3,  'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'permintaan_id' => 2, 'golongan' => 'A',  'rhesus' => '+', 'jumlah' => 4,  'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'permintaan_id' => 3, 'golongan' => 'AB', 'rhesus' => '-', 'jumlah' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'permintaan_id' => 4, 'golongan' => 'B',  'rhesus' => '+', 'jumlah' => 2,  'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('permintaan_darah_detail')->insert($details);
        DB::statement("SELECT setval('permintaan_darah_detail_id_seq', (SELECT MAX(id) FROM permintaan_darah_detail))");
    }
}
