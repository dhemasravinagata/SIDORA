<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Lihat Permintaan Darah - SIDORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>


        .priority-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            min-width: 80px;
        }

        .priority-biasa { background-color: #F3F4F6; color: #374151; }
        .priority-segera { background-color: #FEF3C7; color: #92400E; }
        .priority-darurat { background-color: #FEE2E2; color: #991B1B; }

        .priority-darurat { background-color: #FEE2E2; color: #991B1B; }
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
                    <a href="index.php?page=admin-permintaan-darah" class="sidebar-menu-link active">
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
                    <span>Lihat Permintaan Darah</span>
                </div>
                <div class="page-title">
                    <h1>Permintaan Darah dari Rumah Sakit</h1>
                </div>
            </div>

            <div class="card filter-card">
                <div class="filter-group" style="flex: 2;">
                    <label for="searchPermintaan">Cari Permintaan</label>
                    <input type="text" id="searchPermintaan" placeholder="Nama RS, Pasien, No Permintaan...">
                </div>
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="Ditinjau">Ditinjau</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterPrioritas">Prioritas</label>
                    <select id="filterPrioritas">
                        <option value="">Semua Prioritas</option>
                        <option value="biasa">Biasa</option>
                        <option value="segera">Segera</option>
                        <option value="darurat">Darurat</option>
                    </select>
                </div>
                <button class="btn btn-outline-gray" id="resetBtn"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Daftar Permintaan</h3>
                    <button class="btn btn-outline btn-outline-sidora" id="exportBtn"><i data-lucide="file-output"></i> <span>Export</span></button>
                </div>

                <div class="table-wrapper">
                    <table id="permintaanTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Rumah Sakit</th>
                                <th>Gol. Darah</th>
                                <th>Jumlah</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($permintaan)): $no = 1; foreach ($permintaan as $item): ?>
                                <?php 
                                    $stat = strtolower($item['status'] ?? 'pending');
                                    
                                    $stat_display = $stat === 'pending' ? 'ditinjau' : $stat;
                                    $rawPrio = strtolower(trim($item['prioritas'] ?? 'biasa'));
                                    if ($rawPrio == 'darurat' || $rawPrio == 'tinggi') {
                                        $displayPrio = 'Darurat';
                                        $prio_class = 'priority-darurat';
                                    } elseif ($rawPrio == 'segera' || $rawPrio == 'sedang') {
                                        $displayPrio = 'Segera';
                                        $prio_class = 'priority-segera';
                                    } else {
                                        $displayPrio = 'Biasa';
                                        $prio_class = 'priority-biasa';
                                    }
                                    $stat_class = ($stat == 'disetujui') ? 'badge-disetujui' : (($stat == 'ditolak') ? 'badge-ditolak' : (($stat == 'dikirim') ? 'badge-dikirim' : (($stat == 'diterima') ? 'badge-diterima' : 'badge-ditinjau')));
                                ?>
                                <tr data-status="<?= htmlspecialchars($stat_display) ?>" data-prioritas="<?= htmlspecialchars(strtolower($displayPrio)) ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($item['rumah_sakit'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars(($item['golongan'] ?? '-') . ($item['rhesus'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($item['detail_jumlah'] ?? '1') ?> Kantong</td>
                                    <td><span class="priority-badge <?= $prio_class ?>"><?= $displayPrio ?></span></td>
                                    <td><span class="badge <?= $stat_class ?>"><?php if($stat == 'disetujui'): ?><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg><?php elseif($stat == 'ditolak'): ?><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg><?php elseif($stat == 'dikirim'): ?><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg><?php elseif($stat == 'diterima'): ?><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><?php else: ?><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?php endif; ?> <?= ucfirst($stat_display) ?></span></td>
                                    <td><?= htmlspecialchars($item['tanggal'] ?? date('Y-m-d')) ?></td>
                                    <td>
                                        <div class="action-buttons action-buttons-fixed">
                                            <?php if($stat == 'ditinjau' || $stat == 'pending'): ?>
                                                <a href="index.php?page=admin-terima-permintaan&id=<?= $item['id'] ?>" class="btn btn-success btn-small" onclick="return confirm('Terima permintaan ini?');"><i data-lucide="check"></i> <span>Terima</span></a>
                                                <button type="button" class="btn btn-danger btn-small" onclick="openTolakModal(<?= $item['id'] ?>)"><i data-lucide="x"></i> <span>Tolak</span></button>
                                            <?php elseif($stat == 'disetujui'): ?>
                                                <button type="button" class="btn btn-primary-sidora btn-small" onclick="openKirimModal(<?= $item['id'] ?>)"><i data-lucide="truck"></i> <span>Kirim</span></button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, "UTF-8") ?>)'><i data-lucide="eye"></i> <span>Detail</span></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="9" style="text-align:center;">Belum ada permintaan darah.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php render_alerts(); ?>

    <div id="tolakModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tolak Permintaan</h2>
                <button type="button" class="modal-close" onclick="closeModal('tolakModal')">&times;</button>
            </div>
            <form action="index.php?page=admin-tolak-permintaan" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="permintaan_id" id="tolakId">
                    <div class="form-group">
                        <label for="alasan">Alasan Penolakan</label>
                        <textarea name="alasan" id="alasan" required placeholder="Masukkan alasan menolak permintaan ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('tolakModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-danger"><i data-lucide="x-circle"></i> <span>Tolak Permintaan</span></button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="kirimModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Konfirmasi Pengiriman Darah</h2>
                <button class="modal-close" onclick="closeModal('kirimModal')">&times;</button>
            </div>
            <form action="index.php?page=admin-kirim-permintaan" method="POST">
                @csrf
                <input type="hidden" name="permintaan_id" id="kirimPermintaanId" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="namaKurir">Nama Kurir / Petugas Pengantar</label>
                        <input type="text" id="namaKurir" name="kurir" placeholder="Masukkan nama kurir atau petugas pengantar..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('kirimModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Konfirmasi</span></button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Permintaan</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                
            </div>
        </div>
    </div>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>
    <script>
        function openModal(id, permintaanId = null) {
            document.getElementById(id).classList.add('active');
            if(permintaanId) {
                const modal = document.getElementById(id);
                const input = modal.querySelector('input[name="permintaan_id"]');
                if(input) input.value = permintaanId;
            }
        }
        function openTolakModal(id) {
            document.getElementById('tolakId').value = id;
            openModal('tolakModal');
        }
        function openKirimModal(id) {
            document.getElementById('kirimPermintaanId').value = id;
            openModal('kirimModal');
        }
        function openDetailModal(data) {
            let status = data.status || 'Ditinjau';
            let statusClass = 'badge-info';
            if (status.toLowerCase() === 'ditolak') statusClass = 'badge-danger';
            else if (status.toLowerCase() === 'dikirim') statusClass = 'badge-success';
            else if (status.toLowerCase() === 'diterima') statusClass = 'badge-diterima';
            else if (status.toLowerCase() === 'ditinjau' || status.toLowerCase() === 'pending') statusClass = 'badge-warning';

            let html = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">ID Permintaan</p>
                        <p class="detail-value">#REQ-${data.id || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Rumah Sakit</p>
                        <p class="detail-value">${data.rumah_sakit || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Nama Pasien / Keterangan Medis</p>
                        <p class="detail-value">${data.keterangan || 'Belum diatur'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${data.golongan || '-'}${data.rhesus || ''}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jumlah Kantong</p>
                        <p class="detail-value">${data.detail_jumlah || data.jumlah || 0} Kantong</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Prioritas</p>
                        <p class="detail-value" id="detailPrioritas">${data.prioritas || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${statusClass}">${status}</span></p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Dibuat</p>
                        <p class="detail-value">${data.tanggal || data.created_at || '-'}</p>
                    </div>
            `;
            
            if (status.toLowerCase() === 'ditolak') {
                html += `
                    <div class="detail-item full-width">
                        <p class="detail-label">Alasan Penolakan</p>
                        <p class="detail-value" style="color: var(--color-danger);">${data.alasan_tolak || data.catatan || 'Belum diatur'}</p>
                    </div>
                `;
            } else if (status.toLowerCase() === 'dikirim' || status.toLowerCase() === 'disetujui') {
                html += `
                    <div class="detail-item">
                        <p class="detail-label">Kurir Pengantar</p>
                        <p class="detail-value">${data.kurir || 'Belum diatur'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Kirim</p>
                        <p class="detail-value">${data.tanggal_kirim || 'Belum diatur'}</p>
                    </div>
                `;
            } else if (status.toLowerCase() === 'diterima') {
                html += `
                    <div class="detail-item">
                        <p class="detail-label">Kurir Pengantar</p>
                        <p class="detail-value">${data.kurir || 'Belum diatur'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Kirim</p>
                        <p class="detail-value">${data.tanggal_kirim || 'Belum diatur'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Diterima RS</p>
                        <p class="detail-value">${data.tanggal_terima || 'Sudah diterima'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Penerima di RS</p>
                        <p class="detail-value">${data.penerima || 'Pihak RS'}</p>
                    </div>
                `;
            }
            
            html += `</div>`;
            document.getElementById('detailBody').innerHTML = html;

            const rawPrio = (data.prioritas || 'biasa').toLowerCase().trim();
            let displayPrio = 'Biasa';
            if(rawPrio === 'darurat' || rawPrio === 'tinggi') displayPrio = 'Darurat';
            else if(rawPrio === 'segera' || rawPrio === 'sedang') displayPrio = 'Segera';
            document.getElementById('detailPrioritas').textContent = displayPrio;

            document.getElementById('detailModal').classList.add('active');
        }

        window.onclick = (event) => {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPermintaan');
            const statusSelect = document.getElementById('filterStatus');
            const prioSelect = document.getElementById('filterPrioritas');
            const resetBtn = document.getElementById('resetBtn');
            const exportBtn = document.getElementById('exportBtn');
            const tableBody = document.querySelector('#permintaanTable tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            function filterTable() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const statusFilter = statusSelect ? statusSelect.value.toLowerCase().trim() : '';
                const prioFilter = prioSelect ? prioSelect.value.toLowerCase().trim() : '';
                
                rows.forEach(row => {
                    if(row.cells.length <= 1) return; 

                    const text = row.textContent.toLowerCase();
                    const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();
                    const rowPrio = (row.getAttribute('data-prioritas') || '').toLowerCase().trim();
                    
                    const statusBadge = row.querySelector('.badge');
                    const prioBadge = row.querySelector('.priority-badge');
                    
                    const statusText = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
                    const prioText = prioBadge ? prioBadge.textContent.toLowerCase().trim() : '';
                    
                    const matchSearch = searchTerm === '' || text.includes(searchTerm);
                    const matchStatus = statusFilter === '' || rowStatus === statusFilter || statusText.includes(statusFilter) || (statusFilter === 'ditinjau' && (rowStatus === 'pending' || rowStatus === 'ditinjau'));
                    const matchPrio = prioFilter === '' || rowPrio === prioFilter || prioText === prioFilter;
                    
                    row.style.display = (matchSearch && matchStatus && matchPrio) ? '' : 'none';
                });
            }
            
            if(searchInput) {
                searchInput.addEventListener('input', filterTable);
                searchInput.addEventListener('keydown', function(e) {
                    if(e.key === 'Enter') {
                        e.preventDefault();
                        filterTable();
                    }
                });
            }
            if(statusSelect) statusSelect.addEventListener('change', filterTable);
            if(prioSelect) prioSelect.addEventListener('change', filterTable);
            
            if(resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchInput.value = '';
                    statusSelect.value = '';
                    prioSelect.value = '';
                    filterTable();
                });
            }

            if(exportBtn) {
                exportBtn.addEventListener('click', function() {
                    if(typeof exportTableToCSV === 'function') {
                        exportTableToCSV('permintaanTable', 'Data_Permintaan_Darah.csv');
                    } else {
                        alert('Fungsi export belum dimuat.');
                    }
                });
            }
        });
    </script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
