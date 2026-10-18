<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Lihat Daftar Pendonor - SIDORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>


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
                    <a href="index.php?page=admin-daftar-pendonor" class="sidebar-menu-link active">
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
                    <span>Lihat Daftar Pendonor</span>
                </div>

                <div class="page-title">
                    <h1>Daftar Pendonor</h1>
                </div>
            </div>

            <div class="card filter-card">
                <div class="filter-group" style="flex: 2; min-width: 200px;">
                    <label for="searchPendonor">Cari Pendonor</label>
                    <input type="text" id="searchPendonor" placeholder="Nama, No. Identitas, No. Telepon...">
                </div>

                <div class="filter-group">
                    <label for="filterGolDarah">Golongan Darah</label>
                    <select id="filterGolDarah">
                        <option value="">Semua Golongan Darah</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>

                <button class="btn btn-outline-gray" id="resetBtn"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Data Pendonor Terdaftar</h3>
                    <div class="table-actions">
                        <button class="btn btn-outline btn-outline-sidora" id="exportBtn"><i data-lucide="file-output"></i> <span>Export</span></button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabelPendonor">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Gol. Darah</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendonorList) && count($pendonorList) > 0): $no = 1; foreach ($pendonorList as $pendonor): ?>
                                <?php 
                                    $gol = $pendonor['golongan'] ?? '';
                                    $darah = strtoupper(trim(($pendonor['golongan'] ?? '') . ($pendonor['rhesus'] ?? '')));
                                ?>
                                <tr data-golongan="<?= htmlspecialchars($gol) ?>"
                                    data-darah="<?= htmlspecialchars($darah) ?>"
                                    data-status="<?= htmlspecialchars(strtolower($pendonor['status'] ?? 'aktif')) ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars(!empty($pendonor['nama']) ? $pendonor['nama'] : '-') ?></td>
                                    <td><?= htmlspecialchars((!empty($pendonor['golongan']) ? $pendonor['golongan'] : '-') . (!empty($pendonor['rhesus']) ? $pendonor['rhesus'] : '')) ?></td>
                                    <td><?= htmlspecialchars(!empty($pendonor['telepon']) ? $pendonor['telepon'] : '-') ?></td>
                                    <td>
                                        <?php if((!empty($pendonor['status']) && $pendonor['status']=='aktif') || (!isset($pendonor['status']))): ?>
                                            <span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Non-aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-outline-sidora btn-small" onclick='openDetailPendonorModal(<?= htmlspecialchars(json_encode($pendonor), ENT_QUOTES, "UTF-8") ?>)'>
                                                <i data-lucide="eye"></i> <span>Detail</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="9" style="text-align:center;">Belum ada data pendonor.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="detailPendonorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Pendonor</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailPendonorModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="pendonorDetailContent">
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailPendonorModal(data) {
            const html = `
                <div class="detail-grid">
                    <div class="detail-item full-width">
                        <p class="detail-label">Nama Lengkap</p>
                        <p class="detail-value">${data.nama || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">No. Identitas (NIK)</p>
                        <p class="detail-value">${data.nik || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jenis Kelamin</p>
                        <p class="detail-value">${data.jenis_kelamin || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Lahir</p>
                        <p class="detail-value">${data.tanggal_lahir || data.tgl_lahir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${data.golongan || '-'}${data.rhesus || ''}</p>
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
                        <p class="detail-label">Alamat Lengkap</p>
                        <p class="detail-value">${data.alamat || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${(!data.status || data.status === 'aktif') ? 'badge-success' : 'badge-warning'}">${data.status || 'Aktif'}</span></p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Terakhir Donor</p>
                        <p class="detail-value">${data.terakhir_donor || data.tgl_donor || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Riwayat Donor Tambahan</p>
                        <p class="detail-value">${data.riwayat_donor || 'Belum ada catatan'}</p>
                    </div>
                </div>
            `;
            document.getElementById('pendonorDetailContent').innerHTML = html;
            document.getElementById('detailPendonorModal').classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPendonor');
            const golSelect = document.getElementById('filterGolDarah');
            const statusSelect = document.getElementById('filterStatus');
            const resetBtn = document.getElementById('resetBtn');
            const exportBtn = document.getElementById('exportBtn');
            const tableBody = document.querySelector('#tabelPendonor tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            function filterTable() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const golVal = golSelect.value.toUpperCase().trim();
                const statusVal = statusSelect.value.toLowerCase().trim();
                
                rows.forEach(row => {
                    if(row.cells.length <= 1) return;
                    
                    const text = row.textContent.toLowerCase();
                    const rowDarah = (row.getAttribute('data-darah') || '').toUpperCase().trim();
                    const rowGol = (row.getAttribute('data-golongan') || '').toUpperCase().trim();
                    const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();
                    
                    const matchSearch = searchVal === '' || text.includes(searchVal);
                    
                    let matchGol = false;
                    if (!golVal) {
                        matchGol = true;
                    } else if (golVal === rowDarah) {
                        matchGol = true;
                    } else if (golVal === rowGol) {
                        matchGol = true;
                    }

                    let matchStatus = false;
                    if (!statusVal) {
                        matchStatus = true;
                    } else if (statusVal === rowStatus) {
                        matchStatus = true;
                    } else if (statusVal === 'nonaktif' && (rowStatus === 'non-aktif' || rowStatus === 'nonaktif')) {
                        matchStatus = true;
                    }
                    
                    row.style.display = (matchSearch && matchGol && matchStatus) ? '' : 'none';
                });
            }
            
            if(searchInput) {
                searchInput.addEventListener('input', filterTable);
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterTable();
                    }
                });
            }
            if(golSelect) golSelect.addEventListener('change', filterTable);
            if(statusSelect) statusSelect.addEventListener('change', filterTable);
            
            if(resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchInput.value = '';
                    golSelect.value = '';
                    statusSelect.value = '';
                    filterTable();
                });
            }

            if(exportBtn) {
                exportBtn.addEventListener('click', function() {
                    if(typeof exportTableToCSV === 'function') {
                        exportTableToCSV('tabelPendonor', 'Data_Daftar_Pendonor.csv');
                    } else {
                        alert('Fungsi export belum dimuat. Pastikan file table-actions.js terhubung.');
                    }
                });
            }
        });
    </script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
