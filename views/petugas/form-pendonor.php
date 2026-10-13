<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pendonor - SIDORA Petugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
</head>
<body>
    <div class="dashboard-layout">
        <nav class="navbar dashboard-header">
            <div class="navbar-left">
                <button class="navbar-toggle" id="sidebarToggle"></button>
                <a href="index.php" class="navbar-logo"><img src="assets/img/logo-sidora.png" alt="SIDORA" class="navbar-logo-img"></a>
            </div>
            <div class="navbar-right">
                <div class="navbar-user">
                    <div class="user-avatar">PT</div>
                    <span>Petugas</span>
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-dashboard" class="sidebar-menu-link">
                        <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-title">MANAJEMEN DATA</li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-daftar-pendonor" class="sidebar-menu-link active">
                        <i data-lucide="users"></i> <span>Daftar Pendonor</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-riwayat-donasi" class="sidebar-menu-link">
                        <i data-lucide="file-text"></i> <span>Riwayat Donasi</span>
                    </a>
                </li>
                <li class="sidebar-title">INFORMASI</li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-jadwal-donor" class="sidebar-menu-link">
                        <i data-lucide="calendar"></i> <span>Jadwal Donor</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-stok-darah" class="sidebar-menu-link">
                        <i data-lucide="droplet"></i> <span>Stok Darah</span>
                    </a>
                </li>
                <li class="sidebar-divider" style="margin: 0;"></li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=logout" class="sidebar-menu-link">
                        <i data-lucide="log-out"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div class="breadcrumb">
                    <a href="index.php?page=petugas-dashboard">Dashboard</a>
                    <span>/</span>
                    <a href="index.php?page=petugas-daftar-pendonor">Daftar Pendonor</a>
                    <span>/</span>
                    <span>Tambah Pendonor</span>
                </div>
                <div class="page-title">
                    <h1>Tambah Pendonor Baru</h1>
                </div>
            </div>

            <form action="index.php?page=petugas-tambah-pendonor" method="POST" class="card">
                <h3 style="margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>Informasi Pribadi</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="namaPendonor" class="required">Nama Lengkap</label>
                        <input type="text" id="namaPendonor" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="noIdentitas" class="required">No. Identitas (KTP/NIK)</label>
                        <input type="text" id="noIdentitas" name="no_identitas" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="golDarah" class="required">Golongan Darah</label>
                        <select id="golDarah" name="golongan_darah" class="form-control" required>
                            <option value="">-- Pilih Golongan Darah --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rhesus" class="required">Rhesus</label>
                        <select id="rhesus" name="rhesus" class="form-control" required>
                            <option value="">-- Pilih Rhesus --</option>
                            <option value="+">Positif (+)</option>
                            <option value="-">Negatif (-)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalLahir" class="required">Tanggal Lahir</label>
                        <input type="date" id="tanggalLahir" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jenisKelamin" class="required">Jenis Kelamin</label>
                        <select id="jenisKelamin" name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tekananDarah">Tekanan Darah Awal</label>
                        <input type="text" id="tekananDarah" name="tekanan_darah" value="120/80" placeholder="Misal: 120/80" class="form-control">
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Kontak & Alamat</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nomorTelepon" class="required">No. Telepon</label>
                        <input type="tel" id="nomorTelepon" name="telepon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat" class="required">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kota">Kota/Kabupaten</label>
                        <input type="text" id="kota" name="kota" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" class="form-control">
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Status Registrasi</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="statusDonasi" class="required">Status Donasi Saat Ini</label>
                        <select id="statusDonasi" name="status_donasi" class="form-control" required>
                            <option value="Berhasil">Berhasil (Stok akan bertambah)</option>
                            <option value="Ditolak">Ditolak (Stok tidak bertambah)</option>
                        </select>
                    </div>
                    <div class="form-group form-check" style="display:flex; align-items:center; height: 100%; padding-top: 25px;">
                        <input type="checkbox" id="statusAktif" name="status" value="aktif" checked style="width:18px; height:18px; margin-right: 8px;">
                        <label for="statusAktif" style="margin:0; font-weight:600;">Jadikan Pendonor Aktif</label>
                    </div>
                </div>

                <div style="background: #f0fdf4; border-left: 4px solid var(--success-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin: var(--spacing-xl) 0;">
                    <h4 style="margin-top: 0; color: var(--dark-gray); display:flex; align-items:center; gap:8px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Perhatian</h4>
                    <ul style="margin: 0; padding-left: 20px; color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                        <li>Pastikan identitas (NIK/KTP) sesuai dengan dokumen asli pendonor.</li>
                        <li>Pendaftaran ini sekaligus akan otomatis mencatat riwayat donasi perdana bagi pendonor terkait.</li>
                    </ul>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <a href="index.php?page=petugas-daftar-pendonor" class="btn btn-outline-gray" style="text-decoration:none;"><i data-lucide="arrow-left"></i> <span>Batal & Kembali</span></a>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Data Pendonor</span></button>
                </div>
            </form>
        </main>
    </div>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
