<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Dashboard Petugas - SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
    </style>
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
                    <a href="index.php?page=petugas-dashboard" class="sidebar-menu-link active">
                        
                        
                        <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">MANAJEMEN DATA</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-daftar-pendonor" class="sidebar-menu-link">
                        
                        
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
                    <span>Beranda</span>
                </div>

                <div class="page-title">
                    <h1>Selamat Datang, Petugas! </h1>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Pendonor</h3>
                        <p class="stat-value"><?= isset($statistics['pendonor']) ? number_format($statistics['pendonor']) : '0' ?></p>
                        <div class="stat-change positive">
Terdaftar
                        </div>
                    </div>
                    <div class="stat-icon primary"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Donasi Hari Ini</h3>
                        <p class="stat-value"><?= isset($statistics['donasi_hari_ini']) ? number_format($statistics['donasi_hari_ini']) : '0' ?></p>
                        <div class="stat-change positive">
Selesai
                        </div>
                    </div>
                    <div class="stat-icon success"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Stok Darah</h3>
                        <p class="stat-value"><?= isset($statistics['stok_total']) ? number_format($statistics['stok_total']) : '0' ?></p>
                        <div class="stat-change positive">
Kantong
                        </div>
                    </div>
                    <div class="stat-icon primary"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg></div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Jadwal Donor Aktif</h3>
                        <p class="stat-value"><?= isset($statistics['jadwal']) ? number_format($statistics['jadwal']) : '0' ?></p>
                        <div class="stat-change positive">
Terjadwal
                        </div>
                    </div>
                    <div class="stat-icon warning"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); margin-bottom: var(--spacing-2xl);">
                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=petugas-daftar-pendonor'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="users" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:var(--primary-color);"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Daftar Pendonor</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Kelola data pendonor</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=petugas-riwayat-donasi'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="file-text" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#16a34a;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Riwayat Donasi</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Catat riwayat donasi</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=petugas-jadwal-donor'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="calendar" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#d97706;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Jadwal Donor</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Lihat jadwal kegiatan</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=petugas-stok-darah'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="droplet" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#dc2626;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Stok Darah</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Monitor ketersediaan</p>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Jadwal Donor Mendatang</h3>
                    <a href="index.php?page=petugas-jadwal-donor" class="btn btn-outline btn-small"><span>Lihat Semua</span> <i data-lucide="arrow-right"></i></a>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Terdaftar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jadwalList)): $limit = array_slice($jadwalList, 0, 5); foreach ($limit as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['tanggal'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['waktu_mulai'] ?? '-') ?> - <?= htmlspecialchars($item['waktu_selesai'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['lokasi'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['kuota'] ?? '0') ?> Peserta</td>
                                    <td><span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Terjadwal</span></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" style="text-align: center;">Belum ada jadwal.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Riwayat Donasi Terbaru</h3>
                    <a href="index.php?page=petugas-riwayat-donasi" class="btn btn-outline btn-small"><span>Lihat Semua</span> <i data-lucide="arrow-right"></i></a>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pendonor</th>
                                <th>Golongan Darah</th>
                                <th>Jumlah Kantong</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($riwayatList)): $noRiwayat=1; $limitRiwayat = array_slice($riwayatList, 0, 5); foreach ($limitRiwayat as $item): ?>
                                <tr>
                                    <td><?= $noRiwayat++ ?></td>
                                    <td><?= htmlspecialchars($item['tanggal'] ?? $item['created_at'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['nama_pendonor'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($item['golongan'] ?? '-') ?> <?= htmlspecialchars($item['rhesus'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['jumlah'] ?? '1') ?> kantong</td>
                                    <td><span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Berhasil</span></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" style="text-align: center;">Belum ada riwayat donasi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Riwayat Donasi</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                
            </div>
        </div>
    </div>
    


    <script>
        function openDetailModal(data) {
            const statusColor = data.status === 'Berhasil' ? '#16a34a' : '#dc2626';
            const html = `
                <div class="detail-grid">
                    <div class="detail-item full-width" style="background: transparent; border: none; padding: 0;">
                        <h4 style="margin: 0; color: var(--primary-color); border-bottom: 1px solid var(--border-soft); padding-bottom: 5px;">Data Pendonor</h4>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Nama Lengkap</p>
                        <p class="detail-value">${data.nama_pendonor || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">No. Identitas</p>
                        <p class="detail-value">${data.nik || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jenis Kelamin</p>
                        <p class="detail-value">${data.jenis_kelamin || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Lahir</p>
                        <p class="detail-value">${data.tanggal_lahir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">No. Telepon</p>
                        <p class="detail-value">${data.telepon || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Pekerjaan</p>
                        <p class="detail-value">${data.pekerjaan || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Alamat</p>
                        <p class="detail-value">${data.alamat || '-'}</p>
                    </div>
                    
                    <div class="detail-item full-width" style="background: transparent; border: none; padding: 0; margin-top: 10px;">
                        <h4 style="margin: 0; color: var(--primary-color); border-bottom: 1px solid var(--border-soft); padding-bottom: 5px;">Detail Donasi</h4>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Donasi</p>
                        <p class="detail-value">${data.tanggal || data.created_at || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${(data.golongan||'-') + (data.rhesus||'')}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tekanan Darah</p>
                        <p class="detail-value">${data.tekanan_darah || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jumlah</p>
                        <p class="detail-value">${data.jumlah || 0} Kantong (${((parseInt(data.jumlah)||1)*450)} ml)</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span style="font-weight: 600; color: ${statusColor}">${data.status || 'Berhasil'}</span></p>
                    </div>
                </div>
            `;
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
