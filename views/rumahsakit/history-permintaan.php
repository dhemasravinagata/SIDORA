<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Permintaan - SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .filter-section {
            background: white;
            padding: var(--spacing-lg);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-lg);
            display: flex;
            gap: var(--spacing-md);
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            margin-bottom: var(--spacing-sm);
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
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
                    <a href="index.php?page=rs-history-permintaan" class="sidebar-menu-link active">
                        
                        
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
                    <a href="index.php?page=rs-profil" class="sidebar-menu-link">
                        
                        
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
                    <span>Riwayat Permintaan</span>
                </div>
                <div class="page-title">
                    <h1>Riwayat Permintaan Darah</h1>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-group" style="flex: 2; min-width: 200px;">
                    <label for="searchHistory">Cari Permintaan</label>
                    <input type="text" id="searchHistory" placeholder="Pasien, keperluan, golongan darah..." class="form-control">
                </div>
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterTanggal">Periode</label>
                    <input type="date" id="filterTanggal" class="form-control">
                </div>
                <button type="button" class="btn btn-outline-gray" id="resetHistoryFilter"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Riwayat Permintaan Lengkap</h3>
                    <button type="button" class="btn btn-outline btn-outline-sidora" onclick="exportTableToCSV('tabelHistoryPermintaan','Riwayat_Permintaan_RS.csv')"><i data-lucide="file-output"></i> <span>Export</span></button>
                </div>

                <div class="table-wrapper">
                    <table id="tabelHistoryPermintaan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Pasien / Keperluan</th>
                                <th>Darah</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($permintaanList)): $no=1; foreach ($permintaanList as $permintaan): 
                                $statusClass = 'badge-warning';
                                $statusIcon = '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>';
                                if (strtolower($permintaan['status']) == 'disetujui' || strtolower($permintaan['status']) == 'approved') {
                                    $statusClass = 'badge-success';
                                    $statusIcon = '<polyline points="20 6 9 17 4 12"/>';
                                } elseif (strtolower($permintaan['status']) == 'ditolak' || strtolower($permintaan['status']) == 'rejected') {
                                    $statusClass = 'badge-danger';
                                    $statusIcon = '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
                                } elseif (strtolower($permintaan['status']) == 'dikirim' || strtolower($permintaan['status']) == 'sent') {
                                    $statusClass = 'badge-success';
                                    $statusIcon = '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>';
                                }

                                $keterangan = $permintaan['keterangan'] ?? '';
                                $tujuan = 'pasien';
                                $jenisPermintaan = 'Darah Pasien';
                                $pasienAtauKeperluan = '-';
                                $noPasien = '-';
                                $ruangan = '-';
                                $dokter = '-';
                                $catatan = '-';
                                
                                if (preg_match('/Tujuan:\s*(pasien|stok)/i', $keterangan, $matches)) {
                                    $tujuan = strtolower($matches[1]);
                                } elseif (stripos($keterangan, 'Buffer Stok RS') !== false) {
                                    $tujuan = 'stok';
                                }

                                if ($tujuan === 'stok') {
                                    $jenisPermintaan = 'Stok BDRS';
                                    $pasienAtauKeperluan = 'Penambahan Stok BDRS';
                                    if (preg_match('/Keperluan:\s*(.*?)(?:\.\s*Catatan:|$)/i', $keterangan, $matches)) {
                                        $pasienAtauKeperluan = trim($matches[1]);
                                    } elseif (stripos($keterangan, 'Buffer Stok RS') !== false) {
                                        $pasienAtauKeperluan = 'Penambahan Stok BDRS';
                                    }
                                } else {
                                    if (preg_match('/Pasien:\s*(.*?)\s*\((?:No RM:\s*)?(.*?)\)/i', $keterangan, $matches)) {
                                        $pasienAtauKeperluan = trim($matches[1]);
                                        $noPasien = trim($matches[2]);
                                    }
                                    if (preg_match('/Ruangan:\s*(.*?)(?:\.\s*Dokter:|$)/i', $keterangan, $matches)) {
                                        $ruangan = trim($matches[1]);
                                    }
                                    if (preg_match('/Dokter:\s*(.*?)(?:\.\s*Catatan:|$)/i', $keterangan, $matches)) {
                                        $dokter = trim($matches[1]);
                                    }
                                }

                                if (preg_match('/Catatan:\s*(.*)$/i', $keterangan, $matches)) {
                                    $catatan = trim($matches[1]);
                                }

                                $detailPermintaan = $permintaan;
                                $detailPermintaan['tujuan'] = $tujuan;
                                $detailPermintaan['jenis_permintaan'] = $jenisPermintaan;
                                $detailPermintaan['pasien_keperluan'] = $pasienAtauKeperluan;
                                $detailPermintaan['no_pasien'] = $noPasien;
                                $detailPermintaan['ruangan'] = $ruangan;
                                $detailPermintaan['dokter'] = $dokter;
                                $detailPermintaan['catatan'] = $catatan;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($permintaan['created_at'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($jenisPermintaan) ?></td>
                                <td><?= htmlspecialchars($pasienAtauKeperluan) ?></td>
                                <td><?= htmlspecialchars($permintaan['golongan'] ?? '-') ?> <?= htmlspecialchars($permintaan['rhesus'] ?? '') ?></td>
                                <td><?= htmlspecialchars($permintaan['detail_jumlah'] ?? $permintaan['jumlah'] ?? '0') ?> kantong</td>
                                <td>
                                    <span class="badge <?= $statusClass ?>">
                                        <?php if ($permintaan['status'] === 'Disetujui'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                                        <?php elseif ($permintaan['status'] === 'Ditolak'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        <?php elseif ($permintaan['status'] === 'Dikirim'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($permintaan['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline btn-small" onclick='openDetailPermintaanRsModal(<?= htmlspecialchars(json_encode($detailPermintaan), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="eye"></i> Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="9" style="text-align: center;">Belum ada riwayat permintaan darah.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="detailPermintaanModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Permintaan Darah</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailPermintaanModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailBodyRS">
                
            </div>
        </div>
    </div>


    <script>
        function openDetailPermintaanRsModal(data) {
            let statusClass = 'badge-warning';
            if(data.status === 'Disetujui' || data.status === 'Dikirim') statusClass = 'badge-success';
            else if(data.status === 'Ditolak') statusClass = 'badge-danger';

            const rawPrio = (data.prioritas || 'biasa').toLowerCase().trim();
            let displayPrio = 'Biasa';
            if(rawPrio === 'darurat' || rawPrio === 'tinggi') displayPrio = 'Darurat';
            else if(rawPrio === 'segera' || rawPrio === 'sedang') displayPrio = 'Segera';

            let html = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">Jenis Permintaan</p>
                        <p class="detail-value">${data.jenis_permintaan || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">${data.tujuan === 'stok' ? 'Keperluan' : 'Nama Pasien'}</p>
                        <p class="detail-value">${data.pasien_keperluan || '-'}</p>
                    </div>
            `;

            if (data.tujuan !== 'stok') {
                html += `
                    <div class="detail-item">
                        <p class="detail-label">No. Pasien / Rekam Medis</p>
                        <p class="detail-value">${data.no_pasien || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Ruangan / Dept.</p>
                        <p class="detail-value">${data.ruangan || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Dokter Penanggungjawab</p>
                        <p class="detail-value">${data.dokter || '-'}</p>
                    </div>
                `;
            }

            html += `
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${data.golongan || '-'} ${data.rhesus || ''}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jumlah Kantong</p>
                        <p class="detail-value">${data.detail_jumlah || data.jumlah || 0} Kantong</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Prioritas</p>
                        <p class="detail-value">${displayPrio}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${statusClass}">${data.status || 'Pending'}</span></p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Catatan</p>
                        <p class="detail-value">${data.catatan || '-'}</p>
                    </div>
            `;
            
            if (data.status === 'Ditolak') {
                html += `
                    <div class="detail-item full-width">
                        <p class="detail-label">Alasan Penolakan</p>
                        <p class="detail-value">${data.alasan_tolak || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Penolakan</p>
                        <p class="detail-value">${data.tanggal_tolak || '-'}</p>
                    </div>
                `;
            } else if (data.status === 'Dikirim') {
                html += `
                    <div class="detail-item">
                        <p class="detail-label">Kurir</p>
                        <p class="detail-value">${data.kurir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Kirim</p>
                        <p class="detail-value">${data.tanggal_kirim || '-'}</p>
                    </div>
                `;
            }
            
            html += `</div>`;
            
            document.getElementById('detailBodyRS').innerHTML = html;
            document.getElementById('detailPermintaanModal').classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function filterHistoryTable() {
            const search = document.getElementById('searchHistory').value.toLowerCase().trim();
            const status = document.getElementById('filterStatus').value.toLowerCase().trim();
            const tanggal = document.getElementById('filterTanggal').value;

            document.querySelectorAll('#tabelHistoryPermintaan tbody tr').forEach(row => {
                if (row.cells.length <= 1) return;
                const text = row.textContent.toLowerCase();
                const rowStatus = row.cells[6]?.textContent.toLowerCase().trim() || '';
                const rowTanggal = row.cells[1]?.textContent.trim() || '';
                const matchSearch = !search || text.includes(search);
                const matchStatus = !status || rowStatus.includes(status);
                const matchTanggal = !tanggal || rowTanggal.startsWith(tanggal);
                row.style.display = matchSearch && matchStatus && matchTanggal ? '' : 'none';
            });
        }

        document.getElementById('searchHistory').addEventListener('input', filterHistoryTable);
        document.getElementById('filterStatus').addEventListener('change', filterHistoryTable);
        document.getElementById('filterTanggal').addEventListener('change', filterHistoryTable);
        document.getElementById('resetHistoryFilter').addEventListener('click', function() {
            document.getElementById('searchHistory').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterTanggal').value = '';
            filterHistoryTable();
        });
    </script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
