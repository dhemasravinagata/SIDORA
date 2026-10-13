<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Donor - SIDORA Petugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .schedule-status-badge svg {
            width: 14px;
            height: 14px;
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
                    <a href="index.php?page=petugas-dashboard" class="sidebar-menu-link">
                        
                        
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
                    <a href="index.php?page=petugas-jadwal-donor" class="sidebar-menu-link active">
                        
                        
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
                    <span>Jadwal Donor</span>
                </div>
                <div class="page-title">
                    <h1>Jadwal Kegiatan Donor Darah</h1>
                </div>
            </div>

            <div class="table-container" style="margin-top: var(--spacing-lg);">
                <div class="table-header">
                    <h3>Jadwal Donor Mendatang</h3>
                    <button class="btn btn-outline btn-outline-sidora" onclick="exportTableToCSV('tabelJadwal', 'Jadwal_Donor.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
                </div>

                <div class="table-wrapper">
                    <table id="tabelJadwal">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Target</th>
                                <th>Terdaftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jadwalList)): $no=1; foreach ($jadwalList as $jadwal): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($jadwal['tanggal'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($jadwal['waktu_mulai'] ?? '-') ?> - <?= htmlspecialchars($jadwal['waktu_selesai'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($jadwal['lokasi'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($jadwal['target'] ?? '-') ?> orang</td>
                                <td><?= htmlspecialchars($jadwal['terdaftar'] ?? '0') ?> Peserta</td>
                                <td><span class="badge badge-warning schedule-status-badge"><i data-lucide="calendar"></i> Terjadwal</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline-sidora btn-small" onclick='openDetailJadwalModal(<?= htmlspecialchars(json_encode($jadwal), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="eye"></i> <span>Detail</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="8" style="text-align: center;">Belum ada jadwal donor.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card" style="margin-top: var(--spacing-2xl); background: linear-gradient(135deg, #dbeafe 0%, #cffafe 100%); border: none; padding: var(--spacing-lg); border-radius: var(--border-radius);">
                <h4 style="color: var(--primary-color); margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Informasi Penting</h4>
                <ul style="color: var(--dark-gray); margin: 0; padding-left: 1.5rem;">
                    <li>Pastikan semua data pendonor terdaftar sebelum kegiatan donor dimulai</li>
                    <li>Siapkan peralatan dan formulir donor dengan lengkap</li>
                    <li>Catat riwayat donasi setiap pendonor secara terperinci</li>
                    <li>Lapor ke admin jika ada kendala atau perubahan jadwal</li>
                </ul>
            </div>
        </main>
    </div>

    
    <div class="modal" id="detailJadwalModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Jadwal Donor</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailJadwalModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailJadwalBody">
                
            </div>
        </div>
    </div>



    <script>
        function openDetailJadwalModal(data) {
            const html = `
                <div class="detail-grid">
                    <div class="detail-item full-width">
                        <p class="detail-label">Lokasi Penyelenggaraan / Instansi</p>
                        <p class="detail-value">${data.lokasi || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Pelaksanaan</p>
                        <p class="detail-value">${data.tanggal || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Waktu</p>
                        <p class="detail-value">${(data.waktu_mulai || '-').substring(0,5)} s.d. ${(data.waktu_selesai || '-').substring(0,5)}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Target Pendonor</p>
                        <p class="detail-value">${data.target || 0} orang</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Pendaftar Terdaftar</p>
                        <p class="detail-value">${data.terdaftar || 0} orang</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Catatan Tambahan</p>
                        <p class="detail-value">${data.catatan || 'Belum diatur'}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailJadwalBody').innerHTML = html;
            document.getElementById('detailJadwalModal').classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
