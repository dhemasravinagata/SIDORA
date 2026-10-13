<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Donor - SIDORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .jadwal-card {
            background: white;
            border-left: 4px solid var(--primary-color);
            padding: var(--spacing-lg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: var(--spacing-lg);
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: var(--spacing-lg);
        }

        .jadwal-info h4 {
            margin: 0 0 var(--spacing-sm) 0;
            color: var(--dark-gray);
        }

        .jadwal-info p {
            margin: 0 0 var(--spacing-sm) 0;
            color: var(--gray);
            font-size: 0.95rem;
        }

        @media (max-width: 1024px) {
            .jadwal-card {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .jadwal-card {
                grid-template-columns: 1fr;
            }
        }

        .jadwal-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .edit-jadwal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-md); }
        @media (max-width: 768px) {
            .edit-jadwal-grid { grid-template-columns: 1fr; }
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
                    <span>Jadwal Donor</span>
                </div>
                <div class="page-title">
                    <h1>Kelola Jadwal Donor</h1>
                    <a href="index.php?page=admin-form-jadwal" class="btn btn-primary-sidora">
                        <i data-lucide="plus"></i> <span>Buat Jadwal</span>
                    </a>
                </div>
            </div>

            <div class="dashboard-grid grid-3-cols">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Jadwal</h3>
                        <p class="stat-value"><?= isset($statistics['jadwal']) ? number_format(intval($statistics['jadwal'])) : '0' ?></p>
                        <div class="stat-change positive"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aktif</div>
                    </div>
                    <div class="stat-icon primary"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Jadwal Bulan Ini</h3>
                        <p class="stat-value"><?= isset($statistics['jadwal_bulan']) ? number_format(intval($statistics['jadwal_bulan'])) : '0' ?></p>
                        <div class="stat-change positive"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> Terjadwal</div>
                    </div>
                    <div class="stat-icon success"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Peserta</h3>
                        <p class="stat-value"><?= isset($statistics['peserta']) ? number_format(intval($statistics['peserta'])) : '0' ?></p>
                        <div class="stat-change positive"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Mendaftar</div>
                    </div>
                    <div class="stat-icon warning"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                </div>
            </div>



                <?php if (!empty($jadwalList)): foreach ($jadwalList as $jadwal): ?>
                <div class="jadwal-card">
                    <div class="jadwal-info">
                        <h4><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <?= htmlspecialchars($jadwal['lokasi'] ?? 'Lokasi') ?></h4>
                        <p><strong><?= htmlspecialchars($jadwal['tanggal'] ?? '-') ?></strong></p>
                        <p>Waktu Pelaksanaan: <?= !empty($jadwal['waktu_mulai']) ? substr($jadwal['waktu_mulai'], 0, 5) : '-' ?> - <?= !empty($jadwal['waktu_selesai']) ? substr($jadwal['waktu_selesai'], 0, 5) : '-' ?></p>
                        <p>Lokasi: <?= htmlspecialchars($jadwal['lokasi_detail'] ?? $jadwal['lokasi'] ?? '-') ?></p>
                    </div>
                    <div class="jadwal-info">
                        <h4><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Peserta</h4>
                        <p>Target: <?= htmlspecialchars($jadwal['target'] ?? '0') ?> orang</p>
                        <p>Terdaftar: <?= htmlspecialchars($jadwal['terdaftar'] ?? '0') ?> orang</p>
                    </div>
                    <div class="jadwal-actions" style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openEditJadwalModal(<?= htmlspecialchars(json_encode($jadwal), ENT_QUOTES, "UTF-8") ?>)'>
                            <i data-lucide="pencil"></i> <span>Edit</span>
                        </button>
                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailJadwalModal(<?= htmlspecialchars(json_encode($jadwal), ENT_QUOTES, "UTF-8") ?>)'>
                            <i data-lucide="eye"></i> <span>Detail</span>
                        </button>
                        <button type="button" class="btn btn-danger btn-small" onclick="if(confirm('Hapus jadwal ini?')) { document.getElementById('hapusJadwalId').value=<?= $jadwal['id'] ?>; document.getElementById('hapusJadwalForm').submit(); }">
                            <i data-lucide="trash-2"></i> <span>Hapus</span>
                        </button>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="card">
                    <p style="text-align: center;">Belum ada jadwal donor.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    
    <form id="hapusJadwalForm" method="POST" action="index.php?page=admin-hapus-jadwal" style="display:none;">
        <input type="hidden" name="id" id="hapusJadwalId">
    </form>

    
    <div class="modal" id="detailJadwalModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Jadwal Donor</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailJadwalModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailJadwalBody"></div>
        </div>
    </div>

    
    <div class="modal" id="editJadwalModal">
        <div class="modal-content modal-wide">
            <div class="modal-header">
                <h2>Edit Jadwal Donor</h2>
                <button type="button" class="modal-close" onclick="closeModal('editJadwalModal')">&times;</button>
            </div>
            <form method="POST" action="index.php?page=admin-edit-jadwal">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editJadwalId">
                    <div class="edit-jadwal-grid">
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" id="editJadwalLokasi" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" id="editJadwalTanggal" required>
                        </div>
                    </div>
                    <div class="edit-jadwal-grid">
                        <div class="form-group">
                            <label>Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="editJadwalWaktuMulai" required>
                        </div>
                        <div class="form-group">
                            <label>Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="editJadwalWaktuSelesai" required>
                        </div>
                    </div>
                    <div class="edit-jadwal-grid">
                        <div class="form-group">
                            <label>Target Pendonor</label>
                            <input type="number" name="target" id="editJadwalTarget" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Status Kegiatan</label>
                            <select name="status" id="editJadwalStatus" class="form-control" required>
                                <option value="Akan Datang">Akan Datang</option>
                                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" id="editJadwalCatatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('editJadwalModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>

    <?php render_alerts(); ?>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script>
        function openEditJadwalModal(data) {
            document.getElementById('editJadwalId').value     = data.id || '';
            document.getElementById('editJadwalLokasi').value  = data.lokasi || '';
            document.getElementById('editJadwalTanggal').value = data.tanggal || '';
            document.getElementById('editJadwalWaktuMulai').value = data.waktu_mulai || '';
            document.getElementById('editJadwalWaktuSelesai').value = data.waktu_selesai || '';
            document.getElementById('editJadwalTarget').value  = data.target || '';
            document.getElementById('editJadwalStatus').value  = data.status || 'Akan Datang';
            document.getElementById('editJadwalCatatan').value = data.catatan || '';
            openModal('editJadwalModal');
        }
        function openDetailJadwalModal(data) {
            let status = data.status || 'Akan Datang';
            let statusClass = 'badge-info';
            if (status.toLowerCase() === 'selesai') statusClass = 'badge-success';
            else if (status.toLowerCase() === 'dibatalkan') statusClass = 'badge-danger';
            else if (status.toLowerCase() === 'sedang berlangsung') statusClass = 'badge-warning';

            const html = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">Lokasi Penyelenggaraan</p>
                        <p class="detail-value">${data.lokasi || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Pelaksanaan</p>
                        <p class="detail-value">${data.tanggal || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Waktu</p>
                        <p class="detail-value">${data.waktu_mulai ? data.waktu_mulai.substring(0,5) : '-'} s.d. ${data.waktu_selesai ? data.waktu_selesai.substring(0,5) : '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Target Pendonor</p>
                        <p class="detail-value">${data.target || 0} orang</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jumlah Pendaftar</p>
                        <p class="detail-value">${data.terdaftar || 0} orang</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status Kegiatan</p>
                        <p class="detail-value"><span class="badge ${statusClass}">${status}</span></p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Deskripsi / Lokasi Detail</p>
                        <p class="detail-value">${data.lokasi_detail || data.lokasi || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Catatan</p>
                        <p class="detail-value">${data.catatan || 'Belum diatur'}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailJadwalBody').innerHTML = html;
            openModal('detailJadwalModal');
        }
    </script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
