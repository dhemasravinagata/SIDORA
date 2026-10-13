<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Jadwal Donor - SIDORA Admin</title>
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
                    <div class="user-avatar">AD</div>
                    <span>Admin</span>
                    
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-dashboard" class="sidebar-menu-link">
                        <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-title">MANAJEMEN DATA</li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-kelola-petugas" class="sidebar-menu-link">
                        <i data-lucide="users"></i> <span>Kelola User</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-jadwal-donor" class="sidebar-menu-link active">
                        <i data-lucide="calendar"></i> <span>Jadwal Donor</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-stok-darah" class="sidebar-menu-link">
                        <i data-lucide="droplet"></i> <span>Stok Darah</span>
                    </a>
                </li>
                <li class="sidebar-title">PERMINTAAN DARAH</li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-permintaan-darah" class="sidebar-menu-link">
                        <i data-lucide="file-text"></i> <span>Lihat Permintaan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-daftar-pendonor" class="sidebar-menu-link">
                        <i data-lucide="users"></i> <span>Lihat Daftar Pendonor</span>
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
                    <a href="index.php?page=admin-dashboard">Dashboard</a>
                    <span>/</span>
                    <a href="index.php?page=admin-jadwal-donor">Jadwal Donor</a>
                    <span>/</span>
                    <span>Form Jadwal</span>
                </div>
                <div class="page-title">
                    <h1>Buat Jadwal Donor Baru</h1>
                </div>
            </div>

            <form action="index.php?page=admin-form-jadwal-process" method="POST" class="card">
                <h3 style="margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Informasi Jadwal Donor</h3>

                <div class="form-group">
                    <label>Judul Kegiatan Donor *</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Donor Darah Rutin Bulan April" required>
                </div>

                <div class="form-group">
                    <label>Status Kegiatan *</label>
                    <select name="status" class="form-control" required>
                        <option value="Akan Datang">Akan Datang</option>
                        <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi / Catatan Kegiatan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Deskripsi lengkap atau catatan tentang kegiatan donor darah..."></textarea>
                </div>

                <div class="form-group">
                    <label>Target Kantong Darah *</label>
                    <input type="number" name="target" class="form-control" placeholder="Contoh: 100" min="1" required>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Lokasi Kegiatan</h3>

                <div class="form-group">
                    <label>Nama Lokasi *</label>
                    <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Gedung Olahraga, Terminal, Rumah Sakit, dll" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Alamat Lengkap *</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Jalan, kelurahan, kecamatan">
                    </div>
                    <div class="form-group">
                        <label>Kota/Kabupaten *</label>
                        <input type="text" name="kota" class="form-control" placeholder="Kota atau Kabupaten">
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Waktu Kegiatan</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Mulai *</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai *</label>
                        <input type="time" name="jam_mulai" class="form-control" value="08:00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai *</label>
                        <input type="time" name="jam_selesai" class="form-control" value="14:00">
                    </div>
                </div>
                
                <input type="hidden" name="waktu" value="08:00 - 14:00">

                <div style="background: #dbeafe; border-left: 4px solid var(--info-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin: var(--spacing-lg) 0;">
                    <p style="margin: 0; color: var(--primary-color);"><strong><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Informasi</strong></p>
                    <p style="margin: 0.5rem 0 0 0; color: var(--primary-color);">Data jadwal donor akan diteruskan ke sistem.</p>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <button type="button" onclick="window.history.back()" class="btn btn-outline-gray"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Jadwal</span></button>
                </div>
            </form>
        </main>
    </div>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
