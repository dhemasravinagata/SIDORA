<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Dashboard Rumah Sakit - SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>


        .stat-icon.danger {
            background-color: rgba(220, 38, 38, 0.1);
            color: var(--danger-color);
        }

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .mb-3 {
            margin-bottom: var(--spacing-lg);
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
                    <a href="index.php?page=rs-dashboard" class="sidebar-menu-link active">
                        
                        
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
                    <span>Beranda</span>
                </div>

                <div class="page-title">
                    <h1>Selamat Datang, Rumah Sakit! </h1>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Total Permintaan</h3>
                        <p class="stat-value"><?= number_format((int)($statistics['total_permintaan'] ?? 0)) ?></p>
                        <div class="stat-change positive">
Semua Waktu
                        </div>
                    </div>
                    <div class="stat-icon primary"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/><polyline points="9 8 10 9 12 7"/></svg></div>
                </div>

               
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Permintaan Ditinjau</h3>
                        <p class="stat-value"><?= number_format((int)($statistics['permintaan_ditinjau'] ?? 0)) ?></p>
                        <div class="stat-change negative">
Menunggu Persetujuan
                        </div>
                    </div>
                    <div class="stat-icon warning"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Disetujui</h3>
                        <p class="stat-value"><?= number_format((int)($statistics['disetujui'] ?? 0)) ?></p>
                        <div class="stat-change positive">
Berhasil
                        </div>
                    </div>
                    <div class="stat-icon success"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                </div>

                <div class="stat-card">
                    <div class="stat-content">
                        <h3>Permintaan Ditolak</h3>
                        <p class="stat-value"><?= number_format((int)($statistics['permintaan_ditolak'] ?? 0)) ?></p>
                        <div class="stat-change negative">
Tidak Tersedia
                        </div>
                    </div>
                    <div class="stat-icon danger"><svg width="24" height="24" style="display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); margin-bottom: var(--spacing-2xl);">
                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=rs-permintaan'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="file-plus" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:var(--primary-color);"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Ajukan Permintaan</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Buat permintaan darah</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=rs-history-permintaan'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="file-text" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#d97706;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Riwayat Permintaan</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Cek status pengajuan</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=rs-stok-darah'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="droplet" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#dc2626;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Stok Darah</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Lihat ketersediaan</p>
                </div>

                <div class="card" style="text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow)'" onclick="window.location.href='index.php?page=rs-profil'">
                    <div style="font-size: 2.5rem; margin-bottom: var(--spacing-md);"><i data-lucide="user" style="width: 36px; height: 36px; display:inline-block; vertical-align:middle; flex-shrink:0; color:#16a34a;"></i></div>
                    <h3 style="margin: 0; font-size: 1.1rem;">Profil RS</h3>
                    <p style="color: var(--text-muted); margin: var(--spacing-sm) 0 0 0;">Kelola data instansi</p>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Permintaan Darah Terbaru</h3>
                    <a href="index.php?page=rs-history-permintaan" class="btn btn-outline btn-small"><span>Lihat Semua</span> <i data-lucide="arrow-right"></i></a>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Golongan Darah</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($permintaanList)): $no=1; $limit = array_slice($permintaanList, 0, 5); foreach ($limit as $item): ?>
                                <?php 
                                    $status = $item['status'] ?? 'Pending';
                                    $statusClass = 'badge-warning';
                                    if ($status == 'Disetujui' || $status == 'Dikirim') $statusClass = 'badge-success';
                                    elseif ($status == 'Diterima') $statusClass = 'badge-diterima';
                                    elseif ($status == 'Ditolak') $statusClass = 'badge-danger';
                                    
                                    $detailPermintaan = [
                                        'id' => $item['id'] ?? '',
                                        'tanggal' => $item['created_at'] ?? '-',
                                        'golongan' => $item['golongan'] ?? '-',
                                        'rhesus' => $item['rhesus'] ?? '',
                                        'jumlah' => $item['detail_jumlah'] ?? $item['jumlah'] ?? 0,
                                        'status' => $status,
                                        'keterangan' => $item['keterangan'] ?? '-',
                                        'prioritas' => $item['prioritas'] ?? '-',
                                        'alasan_tolak' => $item['alasan_tolak'] ?? '-',
                                        'tanggal_tolak' => $item['tanggal_tolak'] ?? '-',
                                        'kurir' => $item['kurir'] ?? '-',
                                        'tanggal_kirim' => $item['tanggal_kirim'] ?? '-',
                                        'tanggal_terima' => $item['tanggal_terima'] ?? '-',
                                        'penerima' => $item['penerima'] ?? '-',
                                    ];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars(date('d M Y', strtotime($item['created_at']))) ?></td>
                                    <td><strong><?= htmlspecialchars($item['golongan'] ?? '-') ?> <?= htmlspecialchars($item['rhesus'] ?? '') ?></strong></td>
                                    <td><?= htmlspecialchars($item['detail_jumlah'] ?? $item['jumlah'] ?? '0') ?> Kantong</td>
                                    <td>
                                        <span class="badge <?= $statusClass ?>">
                                            <?php if ($status === 'Disetujui'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                                            <?php elseif ($status === 'Ditolak'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            <?php elseif ($status === 'Dikirim'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                            <?php elseif ($status === 'Diterima'): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($status === 'Dikirim'): ?>
                                                <a href="index.php?page=rs-history-permintaan" class="btn btn-success btn-small" title="Konfirmasi penerimaan darah di Riwayat Permintaan">
                                                    <i data-lucide="check-circle"></i> <span>Konfirmasi</span>
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailPermintaanModal(<?= htmlspecialchars(json_encode($detailPermintaan), ENT_QUOTES, "UTF-8") ?>)'>
                                                <i data-lucide="eye"></i> <span>Detail</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" style="text-align: center;">Belum ada permintaan darah terbaru.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card" style="background: linear-gradient(135deg, #dbeafe 0%, #cffafe 100%); border: none;">
                <h3 style="color: var(--primary-color); margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Informasi Penting</h3>
                <ul style="color: var(--dark-gray); margin: 0; padding-left: 1.5rem;">
                    <li>Permintaan darah akan diproses maksimal 24 jam setelah pengajuan</li>
                    <li>Stok darah dapat dilihat secara real-time untuk perencanaan yang lebih baik</li>
                    <li>Hubungi tim support jika ada pertanyaan atau kendala</li>
                </ul>
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
        function openDetailPermintaanModal(data) {
            let statusClass = 'badge-warning';
            if(data.status === 'Disetujui' || data.status === 'Dikirim') statusClass = 'badge-success';
            else if(data.status === 'Diterima') statusClass = 'badge-diterima';
            else if(data.status === 'Ditolak') statusClass = 'badge-danger';

            const rawPrio = (data.prioritas || 'biasa').toLowerCase().trim();
            let displayPrio = 'Biasa';
            if(rawPrio === 'darurat' || rawPrio === 'tinggi') displayPrio = 'Darurat';
            else if(rawPrio === 'segera' || rawPrio === 'sedang') displayPrio = 'Segera';

            let html = `
                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value">${data.golongan || '-'} ${data.rhesus || ''}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jumlah Kantong</p>
                        <p class="detail-value">${data.jumlah || 0} Kantong</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${statusClass}">${data.status || 'Pending'}</span></p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Keterangan / Prioritas</p>
                        <p class="detail-value">${data.keterangan || '-'} (Prioritas: ${displayPrio})</p>
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
                        <p class="detail-label">Kurir / Pengantar</p>
                        <p class="detail-value">${data.kurir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Kirim</p>
                        <p class="detail-value">${data.tanggal_kirim || '-'}</p>
                    </div>
                `;
            } else if (data.status === 'Diterima') {
                html += `
                    <div class="detail-item">
                        <p class="detail-label">Kurir Pengantar</p>
                        <p class="detail-value">${data.kurir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Dikirim</p>
                        <p class="detail-value">${data.tanggal_kirim || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Diterima</p>
                        <p class="detail-value">${data.tanggal_terima || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Penerima di RS</p>
                        <p class="detail-value">${data.penerima || '-'}</p>
                    </div>
                `;
            }
            html += `</div>`;

            if (data.status === 'Dikirim') {
                html += `
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end;">
                        <a href="index.php?page=rs-history-permintaan" class="btn btn-success">
                            <i data-lucide="check-circle"></i> <span>Konfirmasi Penerimaan Darah</span>
                        </a>
                    </div>
                `;
            }
            
            document.getElementById('detailBodyRS').innerHTML = html;
            document.getElementById('detailPermintaanModal').classList.add('active');
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
