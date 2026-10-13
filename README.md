# SIDORA — Sistem Informasi Donor Darah

SIDORA adalah aplikasi web manajemen donor darah terintegrasi berbasis **Laravel 12 MVC** dengan database **PostgreSQL**. Aplikasi ini mempertahankan 100% desain antarmuka asli (UI/UX) dengan backend modern, keamanan enterprise, penanganan konkurensi stok darah, validasi server-side lengkap, dan exception handling terpusat.

---

## 🚀 Fitur Utama Berdasarkan Peran

### 1. Administrator Utama
- **Dashboard Ringkasan Operasional**: Statistik real-time (total stok kantong darah, permintaan pending, total pendonor, jadwal aktif, jumlah user).
- **Manajemen Pengguna (User)**: CRUD akun Petugas PMI serta verifikasi & persetujuan pendaftaran akun Rumah Sakit (`aktif`, `pending`, `nonaktif`, `ditolak`).
- **Kelola Jadwal Donor**: CRUD jadwal donor darah mobile/lapangan, target partisipan, dan waktu kegiatan.
- **Monitoring & Kontrol Stok Darah**: Status stok (Aman, Rendah, Kritis), riwayat mutasi 7 hari terakhir, dan pencatatan kebutuhan stok tambahan.
- **Persetujuan Permintaan Darah**:
  - Validasi ketersediaan stok darah per golongan dan rhesus.
  - Pengurangan stok aman berbasis transaksi database (`DB::transaction` + `lockForUpdate`) untuk mencegah race condition dan stok negatif.
  - Penolakan permintaan dengan alasan transparan untuk Rumah Sakit.
  - Konfirmasi pengiriman dengan data nama kurir / ambulans pengantar.
- **Ekspor Laporan**: Ekspor seluruh operasional dan data permintaan ke format CSV (UTF-8 BOM kompatibel Microsoft Excel).

### 2. Petugas PMI
- **Dashboard Petugas**: Informasi stok darah terkini, donasi hari ini, dan jadwal donor.
- **Manajemen Pendonor**:
  - Pendaftaran pendonor baru dengan validasi keunikan NIK dan format identitas.
  - Sinkronisasi instan penambahan stok darah jika donasi dinyatakan berstatus *Berhasil*.
  - Pembaruan dan penghapusan data pendonor.
- **Pencatatan Riwayat Donasi**:
  - Input donasi darah pendonor terdaftar lengkap dengan tekanan darah dan status kelayakan donor.
  - Penyesuaian saldo stok otomatis saat riwayat donasi diedit atau dibatalkan.
- **Akses Jadwal Donor & Stok Darah**: Melihat kalender kegiatan dan ketersediaan darah di UTD.

### 3. Rumah Sakit
- **Portal Rumah Sakit Terisolasi**: Hak akses dibatasi secara ketat hanya ke data permintaan milik rumah sakit yang bersangkutan (`user_id`).
- **Pengajuan Permintaan Darah**:
  - Formulir permintaan darah untuk pasien darurat (Nama Pasien, No RM, Ruangan, Dokter) atau dropping stok bank darah BDRS.
  - Penentuan tingkat prioritas (Biasa, Penting, Darurat, Sangat Mendesak).
- **Pelacakan Status Permintaan**: Status real-time (*Pending*, *Disetujui*, *Ditolak*, *Dikirim*).
- **Katalog Stok Darah Real-time**: Memantau ketersediaan golongan darah di PMI sebelum mengajukan permintaan.
- **Manajemen Profil & Keamanan**: Perubahan profil rumah sakit dan pergantian password terenkripsi.

---

## 🛠️ Persyaratan Sistem

- **PHP**: Versi 8.2 atau lebih baru
- **Ekstensi PHP**: `pdo`, `pdo_pgsql`, `pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`
- **Database**: PostgreSQL 14 / 15 / 16
- **Dependency Manager**: Composer 2.x
- **Web Server**: Laragon / Apache / Nginx / PHP CLI (`php artisan serve`)

---

## 📦 Panduan Instalasi & Menjalankan Proyek

### 1. Clone Repository
```bash
git clone https://github.com/ketrinpertiwi/SIDORA.git
cd SIDORA
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi PostgreSQL di file `.env`:
```env
APP_NAME=SIDORA
APP_ENV=production
APP_KEY=base64:wHk9+WfW7pQjC9Wq2tJ0o1YvK4zN7m6L5rT8sP3vA1E=
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sidora
DB_USERNAME=postgres
DB_PASSWORD=
```

### 3. Install Dependensi (Jika Diperlukan)
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Setup Database PostgreSQL
Buat database `sidora` di server PostgreSQL:
```bash
# Melalui psql:
createdb -U postgres sidora
```

Jalankan migrasi tabel dan data awal (seeder):
```bash
php artisan migrate --seed
```

### 5. Akun Pengguna Bawaan (Default Seeders)
Semua akun default menggunakan password: `password`

| Peran | Email | Username | Status |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@sidora.com` | `admin` | Aktif |
| **Petugas PMI** | `petugas1@sidora.com` | `petugas1` | Aktif |
| **Petugas PMI 2** | `petugas2@sidora.com` | `petugas2` | Aktif |
| **Rumah Sakit (RSUD)** | `rsud@sidora.com` | `rsud_abdulmoeloek` | Aktif |
| **Rumah Sakit (Bhayangkara)** | `bhayangkara@sidora.com` | `rs_bhayangkara` | Aktif |
| **Rumah Sakit (Pending Approval)**| `advent@sidora.com` | `rs_advent` | Pending |

### 6. Menjalankan Aplikasi
Jalankan server pengembangan bawaan:
```bash
php artisan serve
```
Aplikasi dapat dibuka melalui browser pada alamat:
`http://localhost:8000`

Jika menggunakan Laragon/Apache, letakkan proyek di direktori `C:\laragon\www\SIDORA-k` dan akses langsung melalui:
`http://localhost/SIDORA-k` atau `http://localhost/SIDORA-k/public`

---

## 🧪 Pengujian Otomatis (Automated Testing)

Proyek ini dilengkapi dengan rangkaian pengujian otomatis lengkap menggunakan PHPUnit & Laravel Testing yang mencakup:
- **Autentikasi & Otorisasi**: Login 3 role, penolakan akun pending/nonaktif, blokir rute lintas role (HTTP 403).
- **CRUD & Validasi**: Form pendonor, pencegahan NIK ganda, CRUD jadwal donor, dan CRUD petugas.
- **Workflow Permintaan Darah**: Pengajuan oleh RS, isolasi data per RS, persetujuan admin dengan pemotongan stok otomatis, penolakan dengan alasan, serta pengiriman via kurir.
- **Integritas Stok & Konkurensi**: Pencegahan pemotongan ganda (idempotency) dan pencegahan stok negatif saat permintaan melebihi stok yang ada.
- **Error Handling & Empty State**: Uji rute 404, ID tidak ditemukan, dan tampilan tabel kosong.

Jalankan test suite dengan perintah:
```bash
php artisan test
```

Hasil verifikasi:
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

---

## 🔒 Keamanan & Penanganan Error

1. **Hashing Password**: Menggunakan implementasi hashing bawaan Laravel (`Bcrypt`).
2. **Proteksi CSRF**: Semua form dilindungi token CSRF bawaan Laravel (`@csrf` / `csrf_field()`).
3. **Pencegahan SQL Injection**: Seluruh kueri menggunakan Eloquent ORM dan PDO prepared statements.
4. **Keamanan Produksi (`APP_DEBUG=false`)**: Detail teknis dan database query tidak pernah ditampilkan ke pengguna akhir.
5. **Logging Terpusat**: Seluruh error teknis, percakapan log audit, dan exception dicatat secara aman pada `storage/logs/laravel-YYYY-MM-DD.log`.
6. **Halaman Error Kustom**: Menyediakan tampilan ramah pengguna untuk HTTP 403 (*Forbidden*), 404 (*Not Found*), 419 (*Page Expired*), dan 500 (*Internal Server Error*).

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PMI Kota Bandar Lampung & SIDORA.
