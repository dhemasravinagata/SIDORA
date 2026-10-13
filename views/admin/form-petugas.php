<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Petugas - SIDORA Admin</title>
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
                <div class="navbar-user" id="userMenuToggle">
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
                    <a href="index.php?page=admin-kelola-petugas" class="sidebar-menu-link active">
                        <i data-lucide="users"></i> <span>Kelola User</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-jadwal-donor" class="sidebar-menu-link">
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
                    <a href="index.php?page=admin-kelola-petugas">Kelola User</a>
                    <span>/</span>
                    <span>Tambah Petugas</span>
                </div>
                <div class="page-title">
                    <h1>Tambah Petugas Baru</h1>
                </div>
            </div>

            <form action="index.php?page=admin-tambah-petugas" method="POST" class="card">
                <h3 style="margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Kredensial Akun</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="usernamePetugas" class="required">Username</label>
                        <input type="text" id="usernamePetugas" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordPetugas" class="required">Password</label>
                        <input type="password" id="passwordPetugas" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="emailPetugas" class="required">Email</label>
                        <input type="email" id="emailPetugas" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="statusPetugas" class="required">Status Akun</label>
                        <select id="statusPetugas" name="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-aktif</option>
                        </select>
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Informasi Profil</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="namaPetugas" class="required">Nama Lengkap</label>
                        <input type="text" id="namaPetugas" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nomorPetugas" class="required">No. Telepon</label>
                        <input type="tel" id="nomorPetugas" name="telepon" class="form-control" required>
                    </div>
                </div>

                <div style="background: #e0f2fe; border-left: 4px solid var(--primary-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin: var(--spacing-xl) 0;">
                    <h4 style="margin-top: 0; color: var(--dark-gray); display:flex; align-items:center; gap:8px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg> Informasi Penting</h4>
                    <ul style="margin: 0; padding-left: 20px; color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                        <li>Pastikan username unik dan mudah diingat oleh petugas bersangkutan.</li>
                        <li>Petugas akan menggunakan email atau username beserta password yang didaftarkan untuk login ke dalam sistem SIDORA-k.</li>
                    </ul>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <a href="index.php?page=admin-kelola-petugas" class="btn btn-outline-gray" style="text-decoration:none;"><i data-lucide="arrow-left"></i> <span>Batal & Kembali</span></a>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Petugas</span></button>
                </div>
            </form>
        </main>
    </div>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
