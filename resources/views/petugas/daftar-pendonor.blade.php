<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pendonor - SIDORA Petugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>




        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
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

        .info {
            background: white;
            padding: var(--spacing-lg);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--info-color);
            margin-bottom: var(--spacing-lg);
        }

        .btn-info {
            background-color: var(--info-color);
            color: white;
        }

        .btn-info:hover:not(:disabled) {
            background-color: #0270cc;
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
                    <a href="index.php?page=petugas-daftar-pendonor" class="sidebar-menu-link active">
                        
                        
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
                    <span>Daftar Pendonor</span>
                </div>

                <div class="page-title">
                    <h1>Daftar Pendonor</h1>
                    <a href="index.php?page=petugas-form-pendonor" class="btn btn-primary-sidora" style="text-decoration:none;"><i data-lucide="plus"></i> <span>Tambah Pendonor</span></a>
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
                        <option value="">Semua</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
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

                <button class="btn btn-outline-gray"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Data Pendonor Terdaftar</h3>
                    <div class="table-actions">
                        <button type="button" class="btn btn-outline btn-outline-sidora" onclick="exportTableToCSV('tabelPendonor','Pendonor_SIDORA.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
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
                            <?php if (!empty($pendonorList) && count($pendonorList) > 0): $no=1; foreach ($pendonorList as $pendonor): 
                                $gol = $pendonor['golongan'] ?? '';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars(!empty($pendonor['nama']) ? $pendonor['nama'] : '-') ?></td>
                                <td><?= htmlspecialchars((!empty($pendonor['golongan']) ? $pendonor['golongan'] : '-') . (!empty($pendonor['rhesus']) ? $pendonor['rhesus'] : '')) ?></td>
                                <td><?= htmlspecialchars(!empty($pendonor['telepon']) ? $pendonor['telepon'] : '-') ?></td>
                                <td>
                                    <?php $s=$pendonor['status']??'aktif'; ?>
                                    <span class="badge <?= $s === 'aktif' ? 'badge-success' : 'badge-warning' ?>">
                                        <?php if ($s === 'aktif'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aktif
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Non-aktif
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openEditPendonorModal(<?= htmlspecialchars(json_encode($pendonor), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="pencil"></i> <span>Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailPendonorModal(<?= htmlspecialchars(json_encode($pendonor), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="eye"></i> <span>Detail</span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-small" onclick="hapusPendonor(<?= $pendonor['id'] ?>)">
<i data-lucide="trash-2"></i> <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="7" style="text-align: center;">Belum ada data pendonor.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>



    
    <div class="modal" id="editPendonorModal">
        <div class="modal-content" style="max-width: 850px;">
            <div class="modal-header">
                <h2>Edit Pendonor</h2>
                <button type="button" class="modal-close" onclick="closeModal('editPendonorModal')">&times;</button>
            </div>

            <form id="editPendonorForm" action="index.php?page=petugas-edit-pendonor" method="POST">
                <input type="hidden" id="editPendonorId" name="id">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editNamaPendonor" class="required">Nama Lengkap</label>
                            <input type="text" id="editNamaPendonor" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoIdentitas" class="required">No. Identitas (KTP/NIK)</label>
                            <input type="text" id="editNoIdentitas" name="nik" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editGolDarah" class="required">Golongan Darah</label>
                            <select id="editGolDarah" name="golongan_darah" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editRhesus" class="required">Rhesus</label>
                            <select id="editRhesus" name="rhesus" required>
                                <option value="+">Positif (+)</option>
                                <option value="-">Negatif (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editNomorTelepon" class="required">No. Telepon</label>
                            <input type="tel" id="editNomorTelepon" name="telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editTanggalLahir" class="required">Tanggal Lahir</label>
                            <input type="date" id="editTanggalLahir" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="editJenisKelamin" class="required">Jenis Kelamin</label>
                            <select id="editJenisKelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editAlamat" class="required">Alamat</label>
                        <textarea id="editAlamat" name="alamat" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editKota">Kota</label>
                            <input type="text" id="editKota" name="kota">
                        </div>
                        <div class="form-group">
                            <label for="editProvinsi">Provinsi</label>
                            <input type="text" id="editProvinsi" name="provinsi">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editPekerjaan">Pekerjaan</label>
                            <input type="text" id="editPekerjaan" name="pekerjaan">
                        </div>
                        <div class="form-group form-check" style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="editStatusAktif" name="status" value="aktif">
                            <label for="editStatusAktif" style="margin:0;">Pendonor Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('editPendonorModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="detailPendonorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Pendonor</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailPendonorModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailPendonorBody"></div>
        </div>
    </div>

    
    <form id="hapusPendonorForm" method="POST" action="index.php?page=petugas-hapus-pendonor" style="display:none;">
        <input type="hidden" name="id" id="hapusPendonorId">
    </form>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>
    <script>

        function hapusPendonor(id) {
            if (confirm('Hapus pendonor ini? Data riwayat donasi terkait mungkin juga terpengaruh.')) {
                document.getElementById('hapusPendonorId').value = id;
                document.getElementById('hapusPendonorForm').submit();
            }
        }

        function openEditPendonorModal(data) {
            document.getElementById('editPendonorId').value = data.id || '';
            document.getElementById('editNamaPendonor').value = data.nama || '';
            document.getElementById('editNoIdentitas').value = data.nik || '';
            document.getElementById('editGolDarah').value = data.golongan || 'A';
            document.getElementById('editRhesus').value = data.rhesus || '+';
            document.getElementById('editNomorTelepon').value = data.telepon || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editTanggalLahir').value = data.tanggal_lahir || data.tgl_lahir || '';
            document.getElementById('editJenisKelamin').value = data.jenis_kelamin || 'Laki-laki';
            document.getElementById('editAlamat').value = data.alamat || '';
            document.getElementById('editKota').value = data.kota || '';
            document.getElementById('editProvinsi').value = data.provinsi || '';
            document.getElementById('editPekerjaan').value = data.pekerjaan || '';
            document.getElementById('editStatusAktif').checked = (data.status === 'aktif' || !data.status);
            
            document.getElementById('editPendonorModal').classList.add('active');
        }

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
            document.getElementById('detailPendonorBody').innerHTML = html;
            document.getElementById('detailPendonorModal').classList.add('active');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPendonor');
            const golSelect = document.getElementById('filterGolDarah');
            const statusSelect = document.getElementById('filterStatus');
            const tableBody = document.querySelector('#tabelPendonor tbody');
            const rows = tableBody.querySelectorAll('tr');
            const resetBtn = document.querySelector('.filter-section .btn-outline-gray');
            
            function filterTable() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const golVal = golSelect.value.toLowerCase();
                const statusVal = statusSelect.value.toLowerCase();
                
                rows.forEach(row => {
                    if(row.cells.length <= 1) return;
                    
                    const text = row.textContent.toLowerCase();
                    const golCell = row.cells[2];
                    const golText = golCell ? golCell.textContent.toLowerCase().trim() : '';
                    
                    const statusBadge = row.querySelector('.badge');
                    const statusText = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';
                    
                    const matchSearch = searchVal === '' || text.includes(searchVal);
                    const matchGol = golVal === '' || golText === golVal;
                    const matchStatus = statusVal === '' || statusText === statusVal || (statusVal === 'nonaktif' && statusText === 'non-aktif');
                    
                    row.style.display = (matchSearch && matchGol && matchStatus) ? '' : 'none';
                });
            }
            
            if(searchInput) searchInput.addEventListener('input', filterTable);
            if(golSelect) golSelect.addEventListener('change', filterTable);
            if(statusSelect) statusSelect.addEventListener('change', filterTable);
            
            if(resetBtn) {
                resetBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    golSelect.value = '';
                    statusSelect.value = '';
                    filterTable();
                });
            }
        });
    </script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
