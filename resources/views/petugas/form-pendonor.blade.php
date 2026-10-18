<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Tambah Pendonor - SIDORA Petugas</title>
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
                    <a href="index.php?page=petugas-daftar-pendonor">Daftar Pendonor</a>
                    <span>/</span>
                    <span>Tambah Pendonor</span>
                </div>
                <div class="page-title">
                    <h1>Tambah Pendonor Baru</h1>
                </div>
            </div>

            <?php render_alerts(); ?>

            <form action="index.php?page=petugas-tambah-pendonor" method="POST" class="card">
                @csrf

                <h3 style="margin-top: 0; color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    <i data-lucide="user" style="display:inline-block;vertical-align:middle;width:20px;height:20px;margin-right:8px;"></i>Informasi Pribadi
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="namaPendonor" class="required">Nama Lengkap</label>
                        <input type="text" id="namaPendonor" name="nama" value="<?= htmlspecialchars(old('nama')) ?>" class="form-control" placeholder="Nama lengkap pendonor" required>
                        <?php if (has_error('nama')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('nama')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="noIdentitas" class="required">No. Identitas (KTP/NIK)</label>
                        <input type="text" id="noIdentitas" name="no_identitas" value="<?= htmlspecialchars(old('no_identitas')) ?>" class="form-control" placeholder="16 digit NIK pendonor" required>
                        <?php if (has_error('no_identitas')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('no_identitas')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="golDarah" class="required">Golongan Darah</label>
                        <select id="golDarah" name="golongan_darah" class="form-control" required onchange="syncRhesusFromGolDarah(this, 'rhesus')">
                            <option value="">-- Pilih Golongan Darah --</option>
                            <option value="A+" <?= old('golongan_darah') === 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= old('golongan_darah') === 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= old('golongan_darah') === 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= old('golongan_darah') === 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= old('golongan_darah') === 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= old('golongan_darah') === 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= old('golongan_darah') === 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= old('golongan_darah') === 'O-' ? 'selected' : '' ?>>O-</option>
                            <option value="A" <?= old('golongan_darah') === 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= old('golongan_darah') === 'B' ? 'selected' : '' ?>>B</option>
                            <option value="AB" <?= old('golongan_darah') === 'AB' ? 'selected' : '' ?>>AB</option>
                            <option value="O" <?= old('golongan_darah') === 'O' ? 'selected' : '' ?>>O</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rhesus" class="required">Rhesus</label>
                        <select id="rhesus" name="rhesus" class="form-control" required onchange="syncGolDarahFromRhesus(this, 'golDarah')">
                            <option value="+" <?= old('rhesus') === '+' ? 'selected' : '' ?>>Positif (+)</option>
                            <option value="-" <?= old('rhesus') === '-' ? 'selected' : '' ?>>Negatif (-)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalLahir" class="required">Tanggal Lahir</label>
                        <input type="date" id="tanggalLahir" name="tanggal_lahir" value="<?= htmlspecialchars(old('tanggal_lahir')) ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jenisKelamin" class="required">Jenis Kelamin</label>
                        <select id="jenisKelamin" name="jenis_kelamin" class="form-control" required>
                            <option value="Laki-laki" <?= old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nomorTelepon" class="required">No. Telepon / WhatsApp</label>
                        <input type="tel" id="nomorTelepon" name="telepon" value="<?= htmlspecialchars(old('telepon')) ?>" class="form-control" placeholder="08xxxxxxxxxx" required>
                        <?php if (has_error('telepon')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('telepon')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars(old('email')) ?>" class="form-control" placeholder="pendonor@email.com">
                        <?php if (has_error('email')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('email')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat" class="required">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="2" placeholder="Alamat domisili lengkap..." required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kota">Kota / Kabupaten</label>
                        <input type="text" id="kota" name="kota" class="form-control" placeholder="Kota domisili">
                    </div>
                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" class="form-control" placeholder="Provinsi">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" placeholder="Pekerjaan pendonor">
                    </div>
                    <div class="form-group form-check" style="display:flex; align-items:center; gap:8px; padding-top:25px;">
                        <input type="checkbox" id="statusAktif" name="status" value="aktif" checked style="width:18px; height:18px;">
                        <label for="statusAktif" style="margin:0; font-weight:600;">Jadikan Pendonor Aktif</label>
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    <i data-lucide="activity" style="display:inline-block;vertical-align:middle;width:20px;height:20px;margin-right:8px;"></i>Data Pemeriksaan Medis
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tekananDarah" class="required">Tekanan Darah (Sistol/Diastol)</label>
                        <input type="text" id="tekananDarah" name="tekanan_darah" value="120/80" placeholder="Misal: 120/80" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="detakJantung">Detak Jantung (bpm)</label>
                        <input type="text" id="detakJantung" name="detak_jantung" value="72" placeholder="Misal: 72" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hemoglobin">Hemoglobin (g/dL)</label>
                        <input type="text" id="hemoglobin" name="hemoglobin" value="14.5" placeholder="Misal: 14.5" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="suhuTubuh">Suhu Tubuh (°C)</label>
                        <input type="text" id="suhuTubuh" name="suhu_tubuh" value="36.5" placeholder="Misal: 36.5" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kesehatanUmum">Kesehatan Umum</label>
                        <select id="kesehatanUmum" name="kesehatan_umum" class="form-control">
                            <option value="Sehat Baik" selected>Sehat Baik</option>
                            <option value="Agak Sakit">Agak Sakit</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="catatanMedis">Catatan Medis</label>
                        <input type="text" id="catatanMedis" name="catatan" class="form-control" placeholder="Catatan kondisi fisik pendonor...">
                    </div>
                </div>

                <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                    <i data-lucide="droplet" style="display:inline-block;vertical-align:middle;width:20px;height:20px;margin-right:8px;"></i>Rincian Donasi Awal
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jumlahKantong" class="required">Jumlah Kantong</label>
                        <input type="number" id="jumlahKantong" name="jumlah" class="form-control" value="1" min="1" max="10" required>
                    </div>
                    <div class="form-group">
                        <label for="statusDonasi" class="required">Status Donasi Saat Ini</label>
                        <select id="statusDonasi" name="status_donasi" class="form-control" required>
                            <option value="Berhasil" selected>Berhasil (Stok Darah Bertambah)</option>
                            <option value="Ditolak">Ditolak (Stok Darah Tidak Bertambah)</option>
                        </select>
                    </div>
                </div>

                <div style="background: #f0fdf4; border-left: 4px solid var(--success-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin: var(--spacing-xl) 0;">
                    <h4 style="margin-top: 0; color: #166534; display:flex; align-items:center; gap:8px;">
                        <i data-lucide="check-circle" style="width:18px;height:18px;"></i> Checklist Kesiapan Donasi
                    </h4>
                    <ul style="margin: 0; padding-left: 20px; color: #166534; font-size: 0.95rem; line-height: 1.6;">
                        <li>Pendonor sudah beristirahat cukup dan makan makanan bergizi sebelum donasi.</li>
                        <li>Pemeriksaan fisik dan tekanan darah berada dalam batas normal pendonoran darah.</li>
                        <li>Pendaftaran ini secara otomatis akan mencatatkan riwayat donasi dan memperbarui stok darah.</li>
                    </ul>
                </div>

                <div style="display: flex; gap: var(--spacing-md); justify-content: flex-end;">
                    <a href="index.php?page=petugas-daftar-pendonor" class="btn btn-outline-gray" style="text-decoration:none;"><i data-lucide="arrow-left"></i> <span>Batal & Kembali</span></a>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Data Pendonor</span></button>
                </div>
            </form>
        </main>
    </div>

    <script src="assets/js/sidebar.js"></script>
    <script>
        function syncRhesusFromGolDarah(golSelect, rhesusSelectId) {
            const val = (golSelect.value || '').trim();
            const rhesusSelect = document.getElementById(rhesusSelectId);
            if (!rhesusSelect) return;
            if (val.endsWith('+')) {
                rhesusSelect.value = '+';
            } else if (val.endsWith('-')) {
                rhesusSelect.value = '-';
            }
        }

        function syncGolDarahFromRhesus(rhesusSelect, golSelectId) {
            const rh = (rhesusSelect.value || '').trim();
            const golSelect = document.getElementById(golSelectId);
            if (!golSelect) return;
            const curVal = golSelect.value || '';
            const base = curVal.replace(/[+-]/g, '').trim().toUpperCase();
            if (base && ['A', 'B', 'AB', 'O'].includes(base)) {
                const candidate = base + rh;
                for (let i = 0; i < golSelect.options.length; i++) {
                    if (golSelect.options[i].value === candidate) {
                        golSelect.value = candidate;
                        break;
                    }
                }
            }
        }
    </script>
    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
