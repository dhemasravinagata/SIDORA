<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Form Riwayat Donasi - SIDORA Petugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
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
                    <a href="index.php?page=petugas-riwayat-donasi">Riwayat Donasi</a>
                    <span>/</span>
                    <span>Form Donasi</span>
                </div>
                <div class="page-title">
                    <h1>Catat Riwayat Donasi Baru</h1>
                </div>
            </div>

            <?php render_alerts(); ?>

            <form action="index.php?page=petugas-form-riwayat-process" method="POST" class="card">
                @csrf
                <h3 style="margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Informasi Pendonor</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="selectPendonor">Pilih Pendonor Terdaftar *</label>
                        <select name="pendonor_id" id="selectPendonor" class="form-control" required onchange="handlePendonorChange(this)">
                            <option value="">-- Pilih Pendonor --</option>
                            <?php if (!empty($pendonorList)): foreach ($pendonorList as $p): ?>
                                <option value="<?= $p->id ?>" 
                                    data-golongan="<?= htmlspecialchars($p->golongan ?? '') ?>" 
                                    data-rhesus="<?= htmlspecialchars($p->rhesus ?? '+') ?>"
                                    data-tekanan="<?= htmlspecialchars($p->tekanan_darah ?? '120/80') ?>"
                                    data-detak="<?= htmlspecialchars($p->detak_jantung ?? '72') ?>"
                                    data-hb="<?= htmlspecialchars($p->hemoglobin ?? '14.5') ?>"
                                    data-suhu="<?= htmlspecialchars($p->suhu_tubuh ?? '36.5') ?>"
                                    data-kesehatan="<?= htmlspecialchars($p->kesehatan_umum ?? 'Sehat Baik') ?>">
                                    #<?= $p->id ?> - <?= htmlspecialchars($p->nama) ?> (NIK: <?= htmlspecialchars($p->nik) ?>) [<?= htmlspecialchars($p->golongan . $p->rhesus) ?>]
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                        <small style="color: var(--gray);">Nomor ID Pendonor otomatis terisi dari data yang terdaftar</small>
                    </div>
                    <div class="form-group">
                        <label>Golongan Darah *</label>
                        <input type="text" name="golongan" id="inputGolongan" class="form-control" placeholder="Contoh: O" required readonly style="background:#f8fafc;">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Rhesus *</label>
                        <select name="rhesus" id="selectRhesus" class="form-control" required>
                            <option value="+">Positif (+)</option>
                            <option value="-">Negatif (-)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Donasi *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/><line x1="12" y1="6" x2="12" y2="10"/><line x1="10" y1="8" x2="14" y2="8"/></svg> Data Medis</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tekanan Darah (Sistol/Diastol)</label>
                        <input type="text" name="tekanan_darah" id="inputTekanan" class="form-control" placeholder="Misal: 120/80" value="120/80">
                    </div>
                    <div class="form-group">
                        <label>Detak Jantung (bpm)</label>
                        <input type="text" name="detak_jantung" id="inputDetak" class="form-control" placeholder="Detak jantung per menit" value="72">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Hemoglobin (g/dL)</label>
                        <input type="text" name="hemoglobin" id="inputHb" class="form-control" placeholder="Kadar hemoglobin" value="14.5">
                    </div>
                    <div class="form-group">
                        <label>Suhu Tubuh (°C)</label>
                        <input type="text" name="suhu_tubuh" id="inputSuhu" class="form-control" placeholder="Suhu tubuh" value="36.5">
                    </div>
                </div>

                <div class="form-group">
                    <label>Kesehatan Umum</label>
                    <select name="kesehatan_umum" id="selectKesehatan" class="form-control">
                        <option value="Sehat Baik" selected>Sehat Baik</option>
                        <option value="Agak Sakit">Agak Sakit</option>
                        <option value="Sakit">Sakit</option>
                    </select>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg> Rincian Donasi</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jumlah (Kantong) *</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="Jumlah kantong (misal: 1)" value="1" required min="1" max="10">
                    </div>
                    <div class="form-group">
                        <label>Status Donasi *</label>
                        <select name="status" class="form-control" required>
                            <option value="Berhasil" selected>Berhasil</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan / Keterangan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan penting atau kondisi khusus pendonor..."></textarea>
                </div>

                <div style="background: #f0fdf4; border-left: 4px solid var(--success-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin-bottom: var(--spacing-lg);">
                    <p style="margin: 0; color: #166534;"><strong><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Checklist Donasi</strong></p>
                    <ul style="color: #166534; margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                        <li>Pendonor sudah istirahat 10-15 menit sebelum donasi</li>
                        <li>Dua jam sebelum donasi sudah makan bergizi</li>
                        <li>Kondisi fisik baik dan siap mendonor</li>
                        <li>Data medis telah dicatat lengkap</li>
                    </ul>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <a href="index.php?page=petugas-riwayat-donasi" class="btn btn-outline-gray" style="text-decoration:none;"><i data-lucide="arrow-left"></i> <span>Batal</span></a>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Riwayat</span></button>
                </div>
            </form>
        </main>
    </div>
    <script src="assets/js/sidebar.js"></script>

    <script>
        function handlePendonorChange(sel) {
            const opt = sel.options[sel.selectedIndex];
            if (!opt || !opt.value) return;

            const gol = opt.getAttribute('data-golongan') || '';
            const rh = opt.getAttribute('data-rhesus') || '+';
            const td = opt.getAttribute('data-tekanan') || '120/80';
            const dj = opt.getAttribute('data-detak') || '72';
            const hb = opt.getAttribute('data-hb') || '14.5';
            const st = opt.getAttribute('data-suhu') || '36.5';
            const ku = opt.getAttribute('data-kesehatan') || 'Sehat Baik';

            document.getElementById('inputGolongan').value = gol;
            document.getElementById('selectRhesus').value = rh;
            document.getElementById('inputTekanan').value = td;
            document.getElementById('inputDetak').value = dj;
            document.getElementById('inputHb').value = hb;
            document.getElementById('inputSuhu').value = st;
            document.getElementById('selectKesehatan').value = ku;
        }
    </script>
    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
