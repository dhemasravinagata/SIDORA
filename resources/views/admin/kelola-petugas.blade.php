<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Kelola User - SIDORA Admin</title>
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
                    <span>Kelola User</span>
                </div>

                <div class="page-title">
                    <h1>Kelola User</h1>
                    <a href="index.php?page=admin-form-petugas" class="btn btn-primary-sidora" style="text-decoration:none;">
                        <i data-lucide="plus"></i> <span>Tambah Petugas</span>
                    </a>
                </div>
            </div>

            <?php render_alerts(); ?>

            <div id="filterFormUser" class="card filter-card" style="margin-bottom: var(--spacing-xl);">
                <div class="filter-group">
                    <label for="filterTarget">Tipe User</label>
                    <select id="filterTarget">
                        <option value="semua">Semua Tipe User</option>
                        <option value="rumahsakit">Rumah Sakit</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>

                <div class="filter-group" style="flex: 2; min-width: 200px;">
                    <label for="searchUser">Cari User</label>
                    <input type="text" id="searchUser" placeholder="Nama, email, telepon, atau alamat...">
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif / Terverifikasi</option>
                        <option value="menunggu">Menunggu Verifikasi</option>
                        <option value="nonaktif">Non-aktif / Ditolak</option>
                    </select>
                </div>

                <button type="button" class="btn btn-outline-gray" id="resetUserFilter"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container" id="containerRumahSakit">
                <div class="table-header">
                    <h3>Verifikasi Rumah Sakit</h3>
                    <div class="table-actions">
                        <button type="button" class="btn btn-outline btn-small" onclick="exportTableToCSV('tabelRumahSakit','Rumah_Sakit_SIDORA.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabelRumahSakit">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Rumah Sakit</th>
                                <th>Email</th>
                                <th>Tipe Rumah Sakit</th>
                                <th>No. Izin Operasional</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rumahSakitList)): $noRs = 1; foreach ($rumahSakitList as $rs): ?>
                                <?php
                                    $rsStatus = strtolower($rs['status'] ?? 'pending');
                                    $rsBadgeClass = 'badge-warning';
                                    $rsLabel = 'Menunggu Verifikasi';
                                    if ($rsStatus === 'aktif') {
                                        $rsBadgeClass = 'badge-success';
                                        $rsLabel = 'Terverifikasi';
                                    } elseif ($rsStatus === 'ditolak') {
                                        $rsBadgeClass = 'badge-danger';
                                        $rsLabel = 'Ditolak';
                                    } elseif ($rsStatus === 'nonaktif') {
                                        $rsBadgeClass = 'badge-danger';
                                        $rsLabel = 'Non-aktif';
                                    }
                                    $alamatParts = array_filter([
                                        $rs['alamat'] ?? '',
                                        $rs['desa'] ?? '',
                                        $rs['kecamatan'] ?? '',
                                        $rs['provinsi'] ?? '',
                                        $rs['kode_pos'] ?? '',
                                    ]);
                                    $alamatLengkap = $alamatParts ? implode(', ', $alamatParts) : '-';
                                ?>
                                <tr data-status="<?= htmlspecialchars($rsStatus) ?>" data-label="<?= htmlspecialchars(strtolower($rsLabel)) ?>">
                                    <td><?= $noRs++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($rs['name'] ?? '-') ?></strong><br>
                                        <span class="text-muted"><?= htmlspecialchars($rs['username'] ?? '-') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($rs['email'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($rs['tipe_rs'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($rs['no_izin'] ?? '-') ?></td>
                                    <td>
                                        <?= htmlspecialchars($rs['telepon'] ?? '-') ?><br>
                                        <span class="text-muted"><?= htmlspecialchars($rs['kontak'] ?? '-') ?></span>
                                    </td>
                                    <td style="max-width: 340px; white-space: normal;"><?= htmlspecialchars($alamatLengkap) ?></td>
                                    <td><span class="badge <?= $rsBadgeClass ?>"><?= htmlspecialchars($rsLabel) ?></span></td>
                                    <td><?= htmlspecialchars($rs['created_at'] ?? '-') ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($rsStatus === 'aktif'): ?>
                                                <form method="POST" action="index.php?page=admin-hapus-rs" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="<?= (int)$rs['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Hapus akun rumah sakit ini?');">
                                                        <i data-lucide="trash-2"></i> <span>Hapus</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form method="POST" action="index.php?page=admin-approve-rs" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="<?= (int)$rs['id'] ?>">
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit" class="btn btn-success btn-small" onclick="return confirm('Verifikasi dan aktifkan rumah sakit ini?');">
                                                        <i data-lucide="check"></i> <span>Verifikasi</span>
                                                    </button>
                                                </form>
                                                <form method="POST" action="index.php?page=admin-approve-rs" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="<?= (int)$rs['id'] ?>">
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Tolak verifikasi rumah sakit ini?');">
                                                        <i data-lucide="x"></i> <span>Tolak</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="10" style="text-align: center;">Belum ada rumah sakit yang mendaftar.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container" id="containerPetugas">
                <div class="table-header">
                    <h3>Daftar Petugas</h3>
                    <div class="table-actions">
                        <button type="button" class="btn btn-outline btn-small" onclick="exportTableToCSV('tabelPetugas','Petugas_SIDORA.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabelPetugas">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($petugasList)): $no = 1; foreach ($petugasList as $user): ?>
                                <tr data-status="<?= htmlspecialchars(strtolower($user['status'] ?? 'aktif')) ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['telepon'] ?? '-') ?></td>
                                    <td><span class="badge <?= ($user['status'] ?? '') === 'aktif' ? 'badge-success' : 'badge-warning' ?>"><?php if (($user['status'] ?? '') === 'aktif'): ?><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aktif<?php else: ?><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Non-aktif<?php endif; ?></span></td>
                                    <td><?= htmlspecialchars($user['created_at'] ?? '-') ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-outline-sidora btn-small" onclick='openEditPetugasModal(<?= htmlspecialchars(json_encode($user), ENT_QUOTES, "UTF-8") ?>)'>
                                                <i data-lucide="pencil"></i> <span>Edit</span>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-small" onclick="openHapusModal('hapusPetugasModal', <?= $user['id'] ?>)">
                                                <i data-lucide="trash-2"></i> <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="7" style="text-align: center;">Belum ada petugas terdaftar.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>



    
    <div class="modal" id="editPetugasModal">
        <div class="modal-content" style="max-width: 850px;">
            <div class="modal-header">
                <h2>Edit Petugas</h2>
                <button type="button" class="modal-close" onclick="closeModal('editPetugasModal')">&times;</button>
            </div>
            <form method="POST" action="index.php?page=admin-edit-petugas">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" id="editNama" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="editEmail" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="tel" name="telepon" id="editTelepon">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" id="editUsername" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="editPassword" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="editStatus">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('editPetugasModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="hapusPetugasModal">
        <div class="modal-content" style="max-width:400px;">
            <div class="modal-header">
                <h2>Hapus Petugas</h2>
                <button type="button" class="modal-close" onclick="closeModal('hapusPetugasModal')">&times;</button>
            </div>
            <form method="POST" action="index.php?page=admin-hapus-petugas">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="hapusPetugasId">
                    <p>Apakah Anda yakin ingin menghapus petugas ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('hapusPetugasModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-danger"><i data-lucide="trash-2"></i> <span>Ya, Hapus</span></button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>
    <script>

        // Override openEditPetugasModal untuk isi field yang benar
        function openEditPetugasModal(data) {
            document.getElementById('editId').value    = data.id || '';
            document.getElementById('editNama').value  = data.name || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editTelepon').value = data.telepon || '';
            document.getElementById('editUsername').value = data.username || '';
            document.getElementById('editPassword').value = '';
            document.getElementById('editStatus').value  = data.status || 'aktif';
            openModal('editPetugasModal');
        }

        // Hapus modal — set ID
        function openHapusModal(modalId, id) {
            document.getElementById('hapusPetugasId').value = id;
            openModal('hapusPetugasModal');
        }

        const statusOptions = {
            semua: [
                { value: '', label: 'Semua Status' },
                { value: 'aktif', label: 'Aktif / Terverifikasi' },
                { value: 'menunggu', label: 'Menunggu Verifikasi' },
                { value: 'nonaktif', label: 'Non-aktif / Ditolak' },
            ],
            rumahsakit: [
                { value: '', label: 'Semua Status' },
                { value: 'aktif', label: 'Terverifikasi (Aktif)' },
                { value: 'menunggu', label: 'Menunggu Verifikasi (Pending)' },
                { value: 'ditolak', label: 'Ditolak' },
                { value: 'nonaktif', label: 'Non-aktif' },
            ],
            petugas: [
                { value: '', label: 'Semua Status' },
                { value: 'aktif', label: 'Aktif' },
                { value: 'nonaktif', label: 'Non-aktif' },
            ],
        };

        function updateUserFilterLabels() {
            const targetSelect = document.getElementById('filterTarget');
            const target = (targetSelect ? targetSelect.value : 'semua') || 'semua';
            const searchInput = document.getElementById('searchUser');
            const statusSelect = document.getElementById('filterStatus');

            if (searchInput) {
                if (target === 'rumahsakit') {
                    searchInput.placeholder = 'Nama rumah sakit, email, kontak, alamat...';
                } else if (target === 'petugas') {
                    searchInput.placeholder = 'Nama petugas, email, username, no telepon...';
                } else {
                    searchInput.placeholder = 'Nama, email, telepon, atau alamat...';
                }
            }

            if (statusSelect && statusOptions[target]) {
                const currentVal = statusSelect.value;
                statusSelect.innerHTML = statusOptions[target]
                    .map(opt => `<option value="${opt.value}">${opt.label}</option>`)
                    .join('');
                if (statusOptions[target].some(opt => opt.value === currentVal)) {
                    statusSelect.value = currentVal;
                } else {
                    statusSelect.value = '';
                }
            }
        }

        function filterUserTable() {
            const targetSelect = document.getElementById('filterTarget');
            const target = targetSelect ? targetSelect.value : 'semua';
            const searchInput = document.getElementById('searchUser');
            const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const statusSelect = document.getElementById('filterStatus');
            const statusVal = statusSelect ? statusSelect.value.toLowerCase().trim() : '';

            const containerRS = document.getElementById('containerRumahSakit');
            const containerPetugas = document.getElementById('containerPetugas');

            // Switch container display
            if (target === 'rumahsakit') {
                if (containerRS) containerRS.style.display = '';
                if (containerPetugas) containerPetugas.style.display = 'none';
            } else if (target === 'petugas') {
                if (containerRS) containerRS.style.display = 'none';
                if (containerPetugas) containerPetugas.style.display = '';
            } else {
                if (containerRS) containerRS.style.display = '';
                if (containerPetugas) containerPetugas.style.display = '';
            }

            // Filter Rumah Sakit Table
            if (containerRS && containerRS.style.display !== 'none') {
                document.querySelectorAll('#tabelRumahSakit tbody tr').forEach(row => {
                    if (row.cells.length <= 1) return;

                    const text = row.textContent.toLowerCase();
                    const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();
                    const rowLabel = (row.getAttribute('data-label') || '').toLowerCase().trim();

                    const matchSearch = searchVal === '' || text.includes(searchVal);
                    
                    let matchStatus = false;
                    if (!statusVal) {
                        matchStatus = true;
                    } else if (statusVal === 'aktif' || statusVal === 'terverifikasi') {
                        matchStatus = (rowStatus === 'aktif' || rowLabel.includes('terverifikasi'));
                    } else if (statusVal === 'menunggu' || statusVal === 'pending') {
                        matchStatus = (rowStatus === 'pending' || rowLabel.includes('menunggu'));
                    } else if (statusVal === 'ditolak') {
                        matchStatus = (rowStatus === 'ditolak' || rowLabel.includes('ditolak'));
                    } else if (statusVal === 'nonaktif' || statusVal === 'non-aktif') {
                        matchStatus = (rowStatus === 'nonaktif' || rowStatus === 'non-aktif' || rowLabel.includes('non-aktif') || rowLabel.includes('ditolak'));
                    } else {
                        matchStatus = (rowStatus === statusVal || rowLabel.includes(statusVal));
                    }

                    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
                });
            }

            // Filter Petugas Table
            if (containerPetugas && containerPetugas.style.display !== 'none') {
                document.querySelectorAll('#tabelPetugas tbody tr').forEach(row => {
                    if (row.cells.length <= 1) return;

                    const text = row.textContent.toLowerCase();
                    const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();

                    const matchSearch = searchVal === '' || text.includes(searchVal);
                    
                    let matchStatus = false;
                    if (!statusVal) {
                        matchStatus = true;
                    } else if (statusVal === 'aktif' || statusVal === 'terverifikasi') {
                        matchStatus = (rowStatus === 'aktif');
                    } else if (statusVal === 'nonaktif' || statusVal === 'non-aktif') {
                        matchStatus = (rowStatus === 'nonaktif' || rowStatus === 'non-aktif');
                    } else {
                        matchStatus = false;
                    }

                    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const targetSelect = document.getElementById('filterTarget');
            const searchInput = document.getElementById('searchUser');
            const statusSelect = document.getElementById('filterStatus');
            const resetBtn = document.getElementById('resetUserFilter');

            if (targetSelect) {
                targetSelect.addEventListener('change', function() {
                    updateUserFilterLabels();
                    filterUserTable();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterUserTable);
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterUserTable();
                    }
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', filterUserTable);
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (targetSelect) targetSelect.value = 'semua';
                    if (searchInput) searchInput.value = '';
                    updateUserFilterLabels();
                    if (statusSelect) statusSelect.value = '';
                    filterUserTable();
                });
            }

            updateUserFilterLabels();
            filterUserTable();
        });
    </script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
