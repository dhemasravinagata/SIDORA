<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Stok Darah - SIDORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>
        .stok-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-2xl);
        }

        .stok-item {
            background: white;
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            text-align: center;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--primary-color);
        }

        .blood-type {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: var(--spacing-md);
        }

        .stok-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: var(--spacing-sm);
        }

        .stok-status {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: var(--spacing-md);
        }



        @media (max-width: 1024px) {
            .stok-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stok-grid {
                grid-template-columns: 1fr;
            }
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
                    <a href="index.php?page=admin-jadwal-donor" class="sidebar-menu-link">
                        <i data-lucide="calendar"></i> <span>Jadwal Donor</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=admin-stok-darah" class="sidebar-menu-link active">
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
                    <span>Stok Darah</span>
                </div>
                <div class="page-title">
                    <h1>Monitoring Stok Darah</h1>
                </div>
            </div>

            <?php render_alerts(); ?>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Stok</h3>
                        <p class="stat-value"><?= isset($statistics['stok_total']) ? number_format(intval($statistics['stok_total'])) : '0' ?></p>
                        <div class="stat-change positive"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Kantong</div>
                    </div>
                    <div class="stat-icon primary"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Stok Aman</h3>
                        <p class="stat-value"><?= isset($statistics['stok_aman']) ? number_format(intval($statistics['stok_aman'])) : '0' ?></p>
                        <div class="stat-change positive"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Tipe</div>
                    </div>
                    <div class="stat-icon success"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Stok Rendah</h3>
                        <p class="stat-value"><?= isset($statistics['stok_rendah']) ? number_format(intval($statistics['stok_rendah'])) : '0' ?></p>
                        <div class="stat-change negative"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Alert</div>
                    </div>
                    <div class="stat-icon warning"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Stok Kritis</h3>
                        <p class="stat-value"><?= isset($statistics['stok_kritis']) ? number_format(intval($statistics['stok_kritis'])) : '0' ?></p>
                        <div class="stat-change negative"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Urgent</div>
                    </div>
                    <div class="stat-icon danger"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                </div>
            </div>

            <h3 style="margin-bottom: var(--spacing-lg);">Status Stok Semua Golongan Darah</h3>

            <div class="stok-grid">
                <?php 
                $bloodTypes = [
                    ['gol' => 'A', 'rh' => '+'],
                    ['gol' => 'A', 'rh' => '-'],
                    ['gol' => 'B', 'rh' => '+'],
                    ['gol' => 'B', 'rh' => '-'],
                    ['gol' => 'AB', 'rh' => '+'],
                    ['gol' => 'AB', 'rh' => '-'],
                    ['gol' => 'O', 'rh' => '+'],
                    ['gol' => 'O', 'rh' => '-'],
                ];

                $stockMap = [];
                if (!empty($stokList)) {
                    foreach ($stokList as $stok) {
                        $g = strtoupper($stok['golongan'] ?? $stok['golongan_darah'] ?? '');
                        $r = $stok['rhesus'] ?? $stok['rh'] ?? '';
                        $stockMap[$g . $r] = (int)($stok['quantity'] ?? $stok['jumlah'] ?? 0);
                    }
                }

                foreach ($bloodTypes as $bt): 
                    $gol = $bt['gol'];
                    $rh = $bt['rh'];
                    $key = $gol . $rh;
                    $qty = $stockMap[$key] ?? 0;
                    
                    $level = $qty >= 50 ? 'badge-success' : ($qty >= 30 ? 'badge-warning' : 'badge-danger');
                    
                    
                    $color = 'var(--primary-color)';
                    if($key == 'O-') $color = 'var(--danger-color)';
                    elseif($key == 'A+') $color = '#0284c7';
                    elseif($key == 'A-') $color = 'var(--warning-color)';
                    elseif($key == 'B+') $color = '#16a34a';
                    elseif($key == 'B-') $color = '#f59e0b';
                    elseif($key == 'AB+') $color = '#8b5cf6';
                    elseif($key == 'AB-') $color = '#ec4899';
                ?>
                <div class="stok-item" style="border-top-color: <?= $color ?>;">
                    <div class="blood-type" style="color: <?= $color ?>;"><?= $key ?></div>
                    <div style="margin-bottom: var(--spacing-sm);">
                        <span class="stok-number" style="display:inline-block; margin-bottom:0;"><?= $qty ?></span>
                        <span style="color: var(--gray); font-size:1rem; font-weight:500;">Kantong</span>
                    </div>
                    <div>
                        <span class="badge <?= $level ?>">
                            <?php if ($level === 'badge-success'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aman
                            <?php elseif ($level === 'badge-warning'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Rendah
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Kritis
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Riwayat Update Stok (7 Hari Terakhir)</h3>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Golongan Darah</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stok Akhir</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stokHistory)): foreach ($stokHistory as $history): 
                                $masuk = intval($history['masuk'] ?? 0);
                                $keluar = intval($history['keluar'] ?? 0);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($history['tanggal'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($history['golongan'] ?? '-') ?><?= htmlspecialchars($history['rhesus'] ?? '') ?></td>
                                <td style="<?= $masuk > 0 ? 'color: var(--success-color); font-weight: 600;' : '' ?>"><?= $masuk > 0 ? '+'.$masuk : $masuk ?></td>
                                <td style="<?= $keluar > 0 ? 'color: var(--danger-color); font-weight: 600;' : '' ?>"><?= $keluar > 0 ? '-'.$keluar : $keluar ?></td>
                                <td><?= htmlspecialchars($history['stok_akhir'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($history['catatan'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Belum ada riwayat update stok.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div class="modal" id="alertStokModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Kirim Alert Stok Kritis</h2>
                <button class="modal-close" onclick="closeModal('alertStokModal')">&times;</button>
            </div>
            <form action="index.php?page=admin-alert-stok" method="POST" id="alertStokForm">
                @csrf
                <div class="modal-body">
                    <p>Golongan Darah: <strong id="alert-gol"></strong></p>
                    <p>Sisa Stok: <strong id="alert-qty" style="color: red;"></strong> kantong</p>
                    <p style="margin-top:10px;">Anda yakin ingin mengirim notifikasi alert stok kritis ke seluruh petugas?</p>
                    <input type="hidden" name="golongan" id="alert-input-gol">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('alertStokModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-danger"><i data-lucide="send"></i> <span>Kirim Alert</span></button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="pesanStokModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Pesan Tambahan Stok</h2>
                <button class="modal-close" onclick="closeModal('pesanStokModal')">&times;</button>
            </div>
            <form action="index.php?page=admin-pesan-stok" method="POST" id="pesanStokForm">
                @csrf
                <div class="modal-body">
                    <p>Pesan tambahan untuk Golongan Darah: <strong id="pesan-gol"></strong> (Stok saat ini: <span id="pesan-qty"></span>)</p>
                    <input type="hidden" name="golongan" id="pesan-input-gol">
                    <div style="margin-top: 15px;">
                        <label style="display:block; margin-bottom: 5px;">Jumlah Dibutuhkan (Kantong)</label>
                        <input type="number" name="jumlah" class="form-input" min="1" required style="width:100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-top: 15px;">
                        <label style="display:block; margin-bottom: 5px;">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-input" rows="3" style="width:100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('pesanStokModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-warning"><i data-lucide="send"></i> <span>Pesan Stok</span></button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="detailStokModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Stok</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailStokModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailStokBody">
                
            </div>
        </div>
    </div>



    <script>
        function openAlertStokModal(gol, qty) {
            document.getElementById('alert-gol').textContent = gol;
            document.getElementById('alert-input-gol').value = gol;
            document.getElementById('alert-qty').textContent = qty;
            document.getElementById('alertStokModal').classList.add('active');
        }

        function openPesanStokModal(gol, qty) {
            document.getElementById('pesan-gol').textContent = gol;
            document.getElementById('pesan-input-gol').value = gol;
            document.getElementById('pesan-qty').textContent = qty;
            document.getElementById('pesanStokModal').classList.add('active');
        }

        function openDetailStokModal(gol, qty) {
            let statusText = '';
            let statusClass = '';
            if (qty >= 50) {
                statusText = 'Aman';
                statusClass = 'badge-success';
            } else if (qty >= 30) {
                statusText = 'Rendah';
                statusClass = 'badge-warning';
            } else {
                statusText = 'Kritis';
                statusClass = 'badge-danger';
            }

            let html = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${gol}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Stok Tersedia</p>
                        <p class="detail-value">${qty} kantong</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${statusClass}">${statusText}</span></p>
                    </div>
                </div>
                <p style="margin-top: 15px; font-size: 0.9em; color: var(--color-text-muted);">*Riwayat pergerakan stok dapat dilihat pada tabel di bawah grafik.</p>
            `;
            
            document.getElementById('detailStokBody').innerHTML = html;
            document.getElementById('detailStokModal').classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        document.getElementById('alertStokForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert("Notifikasi alert stok kritis berhasil dikirim!");
            this.submit();
        });

        document.getElementById('pesanStokForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert("Permintaan tambahan stok berhasil disimpan!");
            this.submit();
        });
    </script>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
