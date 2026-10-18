<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Lihat Stok Darah - SIDORA Rumah Sakit</title>
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

        .stock-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg) 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stock-info h3 {
            margin: 0 0 var(--spacing-sm) 0;
            color: var(--dark-gray);
        }

        .stock-info p {
            margin: 0;
            color: var(--gray);
        }

        .stock-visual {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .blood-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .blood-o-plus { background: linear-gradient(135deg, #dc2626, #991b1b); }
        .blood-o-minus { background: linear-gradient(135deg, #ea580c, #c2410c); }
        .blood-a-plus { background: linear-gradient(135deg, #2563eb, #1e40af); }
        .blood-a-minus { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
        .blood-b-plus { background: linear-gradient(135deg, #059669, #047857); }
        .blood-b-minus { background: linear-gradient(135deg, #0891b2, #0e7490); }
        .blood-ab-plus { background: linear-gradient(135deg, #f97316, #ea580c); }
        .blood-ab-minus { background: linear-gradient(135deg, #d97706, #b45309); }



        @media (max-width: 768px) {
            .stock-card {
                flex-direction: column;
                text-align: center;
                gap: var(--spacing-lg);
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
                    <a href="index.php?page=rs-history-permintaan" class="sidebar-menu-link">
                        
                        
<i data-lucide="file-text"></i> <span>Riwayat Permintaan</span>
                    </a>
                </li>

                <li class="sidebar-title">INFORMASI</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-stok-darah" class="sidebar-menu-link active">
                        
                        
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
                    <span>Lihat Stok Darah</span>
                </div>

                <div class="page-title">
                    <h1>Ketersediaan Stok Darah</h1>
                </div>
            </div>

            <div style="background: #cffafe; border-left: 4px solid #0891b2; padding: var(--spacing-lg); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg);">
                <p style="margin: 0; color: #164e63; font-weight: 500;">
Data stok darah diperbarui secara real-time. Hubungi layanan donor untuk ketersediaan darah yang Anda butuhkan.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-2xl);">
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
                        $stockMap[$g . $r] = (int)($stok['jumlah'] ?? $stok['quantity'] ?? 0);
                    }
                }

                foreach ($bloodTypes as $bt):
                    $golongan = $bt['gol'];
                    $rh = $bt['rh'];
                    $key = $golongan . $rh;
                    $jumlah = $stockMap[$key] ?? 0;
                    
                    $bloodClass = 'blood-' . strtolower($golongan) . ($rh == '+' ? '-plus' : '-minus');
                    
                    
                    if ($jumlah >= 50) {
                        $statusClass = 'badge-aman';
                        $statusText = 'Aman';
                        $statusIcon = '<polyline points="20 6 9 17 4 12"/>';
                    } elseif ($jumlah >= 20) {
                        $statusClass = 'badge-rendah';
                        $statusText = 'Rendah';
                        $statusIcon = '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>';
                    } else {
                        $statusClass = 'badge-kritis';
                        $statusText = 'Kritis';
                        $statusIcon = '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>';
                    }
                ?>
                <div class="stock-card">
                    <div class="stock-info">
                        <h3><?= $golongan . $rh ?> (<?= $golongan ?> <?= $rh == '+' ? 'Positif' : 'Negatif' ?>)</h3>
                        <p>Stok Tersedia: <strong style="color: var(--dark-gray);"><?= $jumlah ?> kantong</strong></p>
                        <span class="badge badge-status <?= $statusClass ?>" style="margin-top: var(--spacing-md);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><?= $statusIcon ?></svg> <?= $statusText ?>
                        </span>
                    </div>
                    <div class="stock-visual">
                        <div class="blood-circle <?= $bloodClass ?>"><?= $golongan . $rh ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="background: white; padding: var(--spacing-lg); border-radius: var(--border-radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
                <h3 style="margin-top: 0;">Keterangan Status</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg);">
                    <div>
                        <p style="margin: 0 0 var(--spacing-sm) 0;"><span class="badge badge-status badge-aman"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aman</span></p>
                        <p style="margin: 0; color: var(--gray);">Stok tersedia cukup dan di atas minimum stock</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 var(--spacing-sm) 0;"><span class="badge badge-status badge-rendah"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Rendah</span></p>
                        <p style="margin: 0; color: var(--gray);">Stok mulai menipis, perlu perhatian</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 var(--spacing-sm) 0;"><span class="badge badge-status badge-kritis"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Kritis</span></p>
                        <p style="margin: 0; color: var(--gray);">Stok di bawah minimum, segera ajukan permintaan</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
