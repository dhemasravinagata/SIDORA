<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - SIDORA Rumah Sakit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743554">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .modal-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }
        @media (max-width: 768px) {
            .modal-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <nav class="navbar dashboard-header">
            <div class="navbar-left">
                <button class="navbar-toggle" id="sidebarToggle"><i data-lucide="menu"></i></button>
                <a href="index.php" class="navbar-logo"><img src="assets/img/logo-sidora.png" alt="SIDORA" class="navbar-logo-img"></a>
            </div>

            <div class="navbar-right">
                <div class="navbar-user">
                    <div class="user-avatar">RS</div>
                    <span>Rumah Sakit</span>
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-dashboard" class="sidebar-menu-link">
                        <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">PERMINTAAN DARAH</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-permintaan" class="sidebar-menu-link">
                        <i data-lucide="file-plus"></i> <span>Ajukan Permintaan</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-history-permintaan" class="sidebar-menu-link">
                        <i data-lucide="file-text"></i> <span>Riwayat Permintaan</span>
                    </a>
                </li>

                <li class="sidebar-title">INFORMASI</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-stok-darah" class="sidebar-menu-link">
                        <i data-lucide="droplet"></i> <span>Lihat Stok Darah</span>
                    </a>
                </li>

                <li class="sidebar-title" id="pengaturanTitle">PENGATURAN</li>
                
                <li class="sidebar-menu-item" id="profilMenuItem">
                    <a href="index.php?page=rs-profil" class="sidebar-menu-link active">
                        <i data-lucide="user"></i> <span>Profil</span>
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
                    <a href="index.php?page=rs-dashboard">Dashboard</a>
                    <span>/</span>
                    <span>Profil</span>
                </div>

                <div class="page-title">
                    <h1>Profil Rumah Sakit</h1>
                </div>
            </div>

            <div class="card">
                <?php render_alerts(); ?>

                <div style="text-align: center; padding-top: var(--spacing-base);">
                    <h1 style="margin: 0 0 var(--spacing-xs) 0; font-size: 1.6rem;"><?= htmlspecialchars($user['name'] ?? 'RSUD Bandar Lampung') ?></h1>
                    <p style="margin: 0; color: var(--gray);">Rumah Sakit Umum Daerah</p>
                </div>

                <hr style="margin: var(--spacing-xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="margin-top: 0; margin-bottom: var(--spacing-lg);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Informasi Dasar</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Nama Rumah Sakit</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['name'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);">
                            <?php if (($user['status'] ?? '') === 'aktif'): ?>
                                <span style="background: #dcfce7; padding: 0.25rem 0.75rem; border-radius: 999px; color: #166534; font-weight: 600;">Aktif</span>
                            <?php else: ?>
                                <span style="background: #fef08a; padding: 0.25rem 0.75rem; border-radius: 999px; color: #854d0e; font-weight: 600; text-transform: capitalize;"><?= htmlspecialchars($user['status'] ?? 'Pending') ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tipe Rumah Sakit</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);">
                            <?= htmlspecialchars($user['tipe_rs'] ?? '-') ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">No. Izin Operasional</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);">
                            <?= htmlspecialchars($user['no_izin'] ?? '-') ?>
                        </p>
                    </div>
                </div>

                <hr style="margin: var(--spacing-xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="margin-top: 0; margin-bottom: var(--spacing-lg);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Informasi Kontak</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Alamat Lengkap</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['alamat'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Kelurahan / Desa</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['desa'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Kecamatan</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['kecamatan'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Provinsi</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['provinsi'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Kode Pos</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['kode_pos'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Telepon</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['telepon'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Email</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['email'] ?? '-') ?></p>
                    </div>
                </div>

                <hr style="margin: var(--spacing-xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="margin-top: 0; margin-bottom: var(--spacing-lg);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Informasi Akun</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Username</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['username'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Email Akun</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['email'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Nama Kontak / PIC</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars($user['kontak'] ?? '-') ?></p>
                    </div>
                    <div class="form-group">
                        <label style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tanggal Pendaftaran</label>
                        <p style="margin: 0; font-weight: 500; font-size: 1rem; color: var(--dark-gray);"><?= htmlspecialchars(date('d F Y', strtotime($user['created_at'] ?? 'now'))) ?></p>
                    </div>
                </div>

                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                    <button class="btn btn-outline-sidora" style="flex:1;" onclick="document.getElementById('editProfilModal').classList.add('active')"><i data-lucide="pencil"></i> <span>Edit Profil</span></button>
                    <button class="btn btn-outline-sidora" style="flex:1;" onclick="document.getElementById('ubahPasswordModal').classList.add('active')"><i data-lucide="key"></i> <span>Ubah Password</span></button>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="editProfilModal">
        <div class="modal-content modal-wide">
            <div class="modal-header">
                <h2>Edit Profil Rumah Sakit</h2>
                <button class="modal-close" onclick="document.getElementById('editProfilModal').classList.remove('active')">&times;</button>
            </div>
            <form action="index.php?page=rs-profil-update" method="POST">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Rumah Sakit</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($user['telepon'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Kontak / PIC</label>
                            <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($user['kontak'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kelurahan / Desa</label>
                            <input type="text" name="desa" class="form-control" value="<?= htmlspecialchars($user['desa'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($user['kecamatan'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="<?= htmlspecialchars($user['provinsi'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($user['kode_pos'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="document.getElementById('editProfilModal').classList.remove('active')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="ubahPasswordModal">
        <div class="modal-content modal-wide">
            <div class="modal-header">
                <h2>Ubah Password</h2>
                <button class="modal-close" onclick="document.getElementById('ubahPasswordModal').classList.remove('active')">&times;</button>
            </div>
            <form action="index.php?page=rs-ubah-password" method="POST" id="ubahPasswordForm">
                <div class="modal-body">
                    <div class="modal-form-grid">
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" minlength="8" required>
                        </div>
                    </div>
                    <div class="modal-form-grid">
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="8" required>
                            <small id="password-error" style="color: red; display: none; margin-top: 5px;">Konfirmasi password tidak cocok!</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="document.getElementById('ubahPasswordModal').classList.remove('active')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Password</span></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('ubahPasswordForm').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confPass) {
                e.preventDefault();
                document.getElementById('password-error').style.display = 'block';
                return;
            }
            
            document.getElementById('password-error').style.display = 'none';
        });
    </script>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
