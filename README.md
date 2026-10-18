# SIDORA — Sistem Informasi Donor Darah

SIDORA adalah aplikasi web manajemen donor darah terintegrasi berbasis Laravel 12 dengan arsitektur MVC dan database PostgreSQL. Aplikasi ini mempertahankan desain antarmuka asli (UI/UX) serta menyediakan backend dengan validasi server-side, penanganan error terpusat, keamanan akses berdasarkan peran, dan pengelolaan stok darah yang aman terhadap transaksi bersamaan.

## 🚀 Fitur Utama Berdasarkan Peran

### 1. Administrator Utama

- **Dashboard Ringkasan Operasional**: Menampilkan statistik stok darah, permintaan pending, jumlah pendonor, jadwal aktif, dan jumlah pengguna.
- **Manajemen Pengguna**: CRUD akun Petugas PMI serta verifikasi dan persetujuan pendaftaran akun Rumah Sakit dengan status aktif, pending, nonaktif, atau ditolak.
- **Manajemen Jadwal Donor**: Mengelola jadwal donor darah mobile atau lapangan, target partisipan, dan waktu kegiatan.
- **Monitoring Stok Darah**: Menampilkan status stok (Aman, Rendah, Kritis), riwayat mutasi tujuh hari terakhir, dan kebutuhan stok tambahan.
- **Persetujuan Permintaan Darah**:
  - Validasi ketersediaan stok berdasarkan golongan darah dan rhesus.
  - Pengurangan stok menggunakan transaksi database (`DB::transaction` dan `lockForUpdate`) untuk mencegah race condition dan stok negatif.
  - Penolakan permintaan dengan alasan yang transparan.
  - Konfirmasi pengiriman beserta informasi kurir atau ambulans.
- **Ekspor Laporan**: Mengekspor data operasional dan permintaan ke format CSV dengan UTF-8 BOM agar kompatibel dengan Microsoft Excel.

### 2. Petugas PMI

- **Dashboard Petugas**: Menampilkan informasi stok darah terkini, donasi hari ini, dan jadwal donor.
- **Manajemen Pendonor**:
  - Pendaftaran pendonor baru dengan validasi keunikan NIK dan format identitas.
  - Penambahan stok darah otomatis ketika donasi dinyatakan berhasil.
  - Pembaruan dan penghapusan data pendonor.
- **Pencatatan Riwayat Donasi**:
  - Mencatat donasi pendonor terdaftar, termasuk tekanan darah dan status kelayakan donor.
  - Menyesuaikan stok secara otomatis ketika riwayat donasi diedit atau dibatalkan.
- **Akses Jadwal dan Stok Darah**: Melihat kalender kegiatan donor dan ketersediaan darah di UTD.

### 3. Rumah Sakit

- **Portal Rumah Sakit**: Membatasi akses berdasarkan akun sehingga rumah sakit hanya dapat mengakses data permintaannya sendiri melalui `user_id`.
- **Pengajuan Permintaan Darah**:
  - Formulir permintaan darah untuk pasien darurat yang mencakup nama pasien, nomor rekam medis, ruangan, dan dokter.
  - Pengajuan dropping stok untuk bank darah BDRS.
  - Penentuan prioritas permintaan: Biasa, Penting, Darurat, dan Sangat Mendesak.
- **Pelacakan Status Permintaan**: Memantau status permintaan, meliputi Pending, Disetujui, Ditolak, dan Dikirim.
- **Katalog Stok Darah**: Memantau ketersediaan darah di PMI sebelum mengajukan permintaan.
- **Manajemen Profil dan Keamanan**: Memperbarui profil rumah sakit dan mengganti kata sandi yang tersimpan dalam bentuk hash.

---

## 🛠️ Persyaratan Sistem

- **PHP**: Versi 8.2 atau lebih baru.
- **Ekstensi PHP**: `pdo`, `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, dan `fileinfo`.
- **Database**: PostgreSQL 14, 15, atau 16.
- **Dependency Manager**: Composer 2.x.
- **Web Server**: Laragon, Apache, Nginx, atau PHP CLI melalui `php artisan serve`.

---

## 📦 Panduan Instalasi dan Menjalankan Proyek

### 1. Clone Repository

Jalankan perintah berikut pada terminal:

```bash
git clone https://github.com/ketrinpertiwi/SIDORA.git
cd SIDORA
```

### 2. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Sesuaikan konfigurasi aplikasi dan PostgreSQL pada file `.env`:

```env
APP_NAME=SIDORA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sidora
DB_USERNAME=postgres
DB_PASSWORD=
```

> Catatan: Gunakan `APP_ENV=production` dan `APP_DEBUG=false` ketika aplikasi sudah diterapkan di lingkungan produksi. Jangan membagikan file `.env` atau kredensial database.

### 3. Install Dependensi

Jalankan perintah:

```bash
composer install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Setup Database PostgreSQL

Buat database dengan nama `sidora` melalui pgAdmin atau terminal PostgreSQL.

Contoh menggunakan `psql`:

```bash
createdb -U postgres sidora
```

Setelah konfigurasi database selesai, jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

**Perhatian:** Perintah migrasi akan membuat atau memperbarui struktur tabel sesuai migrasi yang tersedia. Seeder dapat menambahkan data awal. Pastikan memahami isi seeder sebelum menjalankannya pada database yang sudah berisi data penting.

### 6. Akun Pengguna Bawaan

Menurut konfigurasi seeder proyek, akun berikut disediakan untuk pengujian. Seluruh akun menggunakan kata sandi awal `password`.

| Peran | Email | Username | Status |
|---|---|---|---|
| Administrator | `admin@sidora.com` | `admin` | Aktif |
| Petugas PMI | `petugas1@sidora.com` | `petugas1` | Aktif |
| Petugas PMI 2 | `petugas2@sidora.com` | `petugas2` | Aktif |
| Rumah Sakit (RSUD) | `rsud@sidora.com` | `rsud_abdulmoeloek` | Aktif |
| Rumah Sakit (Bhayangkara) | `bhayangkara@sidora.com` | `rs_bhayangkara` | Aktif |
| Rumah Sakit (Pending Approval) | `advent@sidora.com` | `rs_advent` | Pending |

> Catatan: Pastikan akun dan kata sandi tersebut sesuai dengan seeder terbaru. Ganti kata sandi bawaan sebelum penggunaan di lingkungan produksi.

### 7. Menjalankan Aplikasi

Jalankan server pengembangan Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://localhost:8000
```

Jika menggunakan Laragon atau Apache, letakkan proyek di direktori web server yang sesuai, kemudian akses melalui URL lokal sesuai konfigurasi virtual host atau document root.

---

## 🧪 Pengujian Otomatis (Automated Testing)

Proyek ini dilengkapi dengan pengujian otomatis menggunakan PHPUnit dan Laravel Testing. Cakupan pengujian meliputi:

- **Autentikasi dan Otorisasi**: Login berdasarkan tiga peran, penolakan akun pending atau nonaktif, serta pembatasan akses lintas peran.
- **CRUD dan Validasi**: Pengelolaan pendonor, pencegahan NIK ganda, pengelolaan jadwal donor, dan pengelolaan petugas.
- **Workflow Permintaan Darah**: Pengajuan oleh rumah sakit, isolasi data antarrumah sakit, persetujuan administrator, pemotongan stok otomatis, penolakan dengan alasan, dan konfirmasi pengiriman.
- **Integritas Stok dan Konkurensi**: Pencegahan pemotongan stok ganda serta pencegahan stok negatif ketika permintaan melebihi ketersediaan.
- **Error Handling dan Empty State**: Pengujian halaman 404, ID yang tidak ditemukan, dan tabel tanpa data.

Jalankan seluruh pengujian dengan perintah:

```bash
php artisan test
```

### Hasil Pengujian

Hasil pengujian yang tercatat pada proyek:

```text
PASS  Tests\Feature\AdminManagementTest (6 tests)
PASS  Tests\Feature\AuthenticationTest (8 tests)
PASS  Tests\Feature\AuthorizationRoleTest (4 tests)
PASS  Tests\Feature\DemonstrationWalkthroughTest (5 tests)
PASS  Tests\Feature\ErrorHandlingAndEmptyStateTest (5 tests)
PASS  Tests\Feature\PermintaanDarahWorkflowTest (7 tests)
PASS  Tests\Feature\PetugasDonorCrudTest (5 tests)
PASS  Tests\Unit\StokDarahTest (2 tests)

Tests:    42 passed (169 assertions)
Duration: 12.61s
```

> Catatan: Hasil di atas merupakan hasil pengujian yang tercatat sebelumnya, bukan jaminan hasil pada lingkungan terbaru. Jalankan ulang `php artisan test` untuk memverifikasi kondisi saat ini.

---

## 🔒 Keamanan dan Penanganan Error

1. **Hashing Password**: Menggunakan mekanisme hashing Laravel untuk menyimpan kata sandi.
2. **Proteksi CSRF**: Formulir dilindungi token CSRF melalui mekanisme Laravel seperti `@csrf`.
3. **Pencegahan SQL Injection**: Kueri menggunakan Eloquent ORM dan parameter binding melalui PDO.
4. **Konfigurasi Produksi**: `APP_DEBUG=false` mencegah detail error teknis ditampilkan kepada pengguna akhir.
5. **Logging Terpusat**: Error dan exception dicatat melalui sistem logging Laravel pada direktori `storage/logs`.
6. **Halaman Error Kustom**: Menyediakan tampilan penanganan untuk HTTP 403 (Forbidden), 404 (Not Found), 419 (Page Expired), dan 500 (Internal Server Error).



Hak Cipta © 2026 PMI Kota Bandar Lampung & SIDORA.
