<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Stok Darah - SIDORA Petugas</title>
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
                    <a href="index.php?page=petugas-jadwal-donor" class="sidebar-menu-link">
                        
                        
<i data-lucide="calendar"></i> <span>Jadwal Donor</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-stok-darah" class="sidebar-menu-link active">
                        
                        
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
                    <span>Stok Darah</span>
                </div>
                <div class="page-title">
                    <h1>Stok Darah Terkini</h1>
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
            
        </main>
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

    <style>

    .badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
    </style>

    <script>
        function openDetailStokModal(gol, qty) {
            let statusText = '';
            let statusClass = '';
            if (qty >= 80) { 
                statusText = 'Aman';
                statusClass = 'badge-success';
            } else if (qty >= 50) {
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
            `;
            
            document.getElementById('detailStokBody').innerHTML = html;
            document.getElementById('detailStokModal').classList.add('active');
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
