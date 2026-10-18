<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Riwayat Donasi - SIDORA Petugas</title>
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
                    <a href="index.php?page=petugas-riwayat-donasi" class="sidebar-menu-link active">
                        
                        
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
                    <span>Riwayat Donasi</span>
                </div>
                <div class="page-title">
                    <h1>Riwayat Donasi</h1>
                    <a href="index.php?page=petugas-form-riwayat" class="btn btn-primary-sidora" style="text-decoration:none;"><i data-lucide="plus"></i> <span>Catat Donasi</span></a>
                </div>
            </div>

            <div class="card filter-card">
                <div class="filter-group">
                    <label for="filterStatus">Status Donasi</label>
                    <select id="filterStatus" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="berhasil">Berhasil</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterTanggal">Tanggal Donasi</label>
                    <input type="date" id="filterTanggal" class="form-control">
                </div>
                <button type="button" id="resetBtn" class="btn btn-outline-gray"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Daftar Riwayat Donasi</h3>
                    <div style="display:flex;gap:var(--spacing-sm);align-items:center;">
                        <button type="button" class="btn btn-outline btn-outline-sidora" onclick="exportTableToCSV('tabelRiwayat','Riwayat_Donasi.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
                    </div>
                </div>
                <?php render_alerts(); ?>
                <div class="table-wrapper">
                    <table id="tabelRiwayat">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pendonor</th>
                                <th>Golongan Darah</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($riwayatList)): $no=1; foreach ($riwayatList as $riwayat): ?>
                            <?php 
                                $status = $riwayat['status'] ?? 'Berhasil';
                                $badgeClass = $status === 'Berhasil' ? 'badge-success' : 'badge-danger';
                                $icon = $status === 'Berhasil' 
                                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:4px;"><polyline points="20 6 9 17 4 12"/></svg>'
                                    : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:4px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($riwayat['tanggal'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($riwayat['nama_pendonor'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($riwayat['golongan'] ?? '-') ?> <?= htmlspecialchars($riwayat['rhesus'] ?? '') ?></td>
                                <td><?= htmlspecialchars($riwayat['jumlah'] ?? '1') ?> kantong</td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $icon ?><?= htmlspecialchars($status) ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                    <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailRiwayatModal(<?= htmlspecialchars(json_encode($riwayat), ENT_QUOTES, "UTF-8") ?>)'>
                                        <i data-lucide="eye"></i> <span>Detail</span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-small" onclick="if(confirm('Hapus riwayat ini? Stok darah akan dikurangi jika status Berhasil.')){ document.getElementById('hapusRiwayatId').value=<?= $riwayat['id'] ?>;document.getElementById('hapusRiwayatForm').submit(); }">
<i data-lucide="trash-2"></i> <span>Hapus</span>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="8" style="text-align: center;">Belum ada riwayat donasi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="detailRiwayatModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Riwayat Donasi</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailRiwayatModal')">&times;</button>
            </div>
            <div class="modal-body" id="mdl-content">
                
            </div>
        </div>
    </div>
    
    <form id="hapusRiwayatForm" action="index.php?page=petugas-hapus-riwayat" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="id" id="hapusRiwayatId">
    </form>

    <script>
        function openDetailRiwayatModal(data) {
            if (typeof data !== 'string') {
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
                            <h4 style="margin: 0; color: var(--primary-color); border-bottom: 1px solid var(--border-soft); padding-bottom: 5px;">Data Pemeriksaan Medis</h4>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Tekanan Darah</p>
                            <p class="detail-value"><strong>${data.tekanan_darah || '120/80'}</strong> mmHg</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Detak Jantung</p>
                            <p class="detail-value">${data.detak_jantung || '72'} bpm</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Hemoglobin</p>
                            <p class="detail-value">${data.hemoglobin || '14.5'} g/dL</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Suhu Tubuh</p>
                            <p class="detail-value">${data.suhu_tubuh || '36.5'} °C</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Kesehatan Umum</p>
                            <p class="detail-value"><span class="badge badge-success">${data.kesehatan_umum || 'Sehat Baik'}</span></p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Catatan Medis</p>
                            <p class="detail-value">${data.catatan || 'Kondisi fisik normal & sehat'}</p>
                        </div>

                        <div class="detail-item full-width" style="background: transparent; border: none; padding: 0; margin-top: 10px;">
                            <h4 style="margin: 0; color: var(--primary-color); border-bottom: 1px solid var(--border-soft); padding-bottom: 5px;">Detail Donasi</h4>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Tanggal Donasi</p>
                            <p class="detail-value">${data.tanggal || '-'}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Golongan Darah</p>
                            <p class="detail-value">${(data.golongan||'-') + (data.rhesus||'')}</p>
                        </div>
                        <div class="detail-item">
                            <p class="detail-label">Jumlah</p>
                            <p class="detail-value">${data.jumlah || 0} Kantong (${((parseInt(data.jumlah)||1)*450)} ml)</p>
                        </div>
                        <div class="detail-item full-width">
                            <p class="detail-label">Status</p>
                            <p class="detail-value"><span style="font-weight: 600; color: ${statusColor}">${data.status || '-'}</span></p>
                        </div>
                    </div>
                `;
                document.getElementById('mdl-content').innerHTML = html;
            }
            document.getElementById('detailRiwayatModal').classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('filterStatus');
            const tanggalInput = document.getElementById('filterTanggal');
            const resetBtn = document.getElementById('resetBtn');
            const tableBody = document.querySelector('#tabelRiwayat tbody');
            const rows = tableBody.querySelectorAll('tr');

            function filterTable() {
                const statusVal = statusSelect.value.toLowerCase();
                const tanggalVal = tanggalInput.value;

                rows.forEach(row => {
                    if(row.cells.length <= 1) return;

                    const tglCell = row.cells[1];
                    const tglText = tglCell ? tglCell.textContent.trim() : '';

                    const statusBadge = row.querySelector('.badge');
                    const statusText = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';

                    const matchStatus = statusVal === '' || statusText === statusVal;
                    const matchTanggal = tanggalVal === '' || tglText === tanggalVal;

                    row.style.display = (matchStatus && matchTanggal) ? '' : 'none';
                });
            }

            if(statusSelect) statusSelect.addEventListener('change', filterTable);
            if(tanggalInput) tanggalInput.addEventListener('change', filterTable);

            if(resetBtn) {
                resetBtn.addEventListener('click', function() {
                    statusSelect.value = '';
                    tanggalInput.value = '';
                    filterTable();
                });
            }
        });

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/table-actions.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
