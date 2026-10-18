<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Daftar Rumah Sakit - SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= time() ?>">
</head>
<body class="auth-page">

    <?php render_alerts(); ?>

    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <div style="text-align: center; font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 8px; font-family: 'Poppins', sans-serif;">Daftar Rumah Sakit</div>
                <div style="text-align: center; font-size: 14px; color: #4B5563; margin-bottom: 24px; font-weight: 500; font-family: 'Poppins', sans-serif;">
                    Buat akun rumah sakit untuk mengakses permintaan darah.
                </div>
            </div>

            <form method="POST" action="{{ request()->has('page') ? 'index.php?page=register-rs-process' : route('register.rs.post') }}" class="register-form">
                <?= csrf_field() ?>
                <input type="hidden" name="page" value="register-rs-process">
                
                <div class="form-row" style="display: grid; gap: var(--spacing-md);">
                    <div class="form-group">
                        <label class="required">Nama Rumah Sakit</label>
                        <input type="text" name="nama_rs" value="<?= htmlspecialchars(old('nama_rs')) ?>" placeholder="Contoh: RSUD Dr. H. Abdul Moeloek" required>
                        <?php if (has_error('nama_rs')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('nama_rs')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars(old('username')) ?>" placeholder="Username untuk login (opsional)">
                        <?php if (has_error('username')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('username')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row" style="display: grid; gap: var(--spacing-md);">
                    <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars(old('email')) ?>" placeholder="Contoh: rs.abdulmoeloek@gmail.com" required>
                        <?php if (has_error('email')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;">
                                <?= htmlspecialchars(error('email')) ?>
                            </span>
                        <?php endif; ?>
                        <small style="color: #64748B; font-size: 0.78rem; display: block; margin-top: 4px;">Pastikan email aktif dan belum pernah didaftarkan pada akun SIDORA mana pun.</small>
                    </div>
                    <div class="form-group">
                        <label class="required">Password</label>
                        <input type="password" name="password" placeholder="Password akun (min. 6 karakter)" required>
                        <?php if (has_error('password')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;font-weight:500;"><?= htmlspecialchars(error('password')) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row" style="display: grid; gap: var(--spacing-md);">
                    <div class="form-group">
                        <label>Telepon RS</label>
                        <input type="text" name="telepon" value="<?= htmlspecialchars(old('telepon')) ?>" placeholder="Contoh: 0721-123456">
                        <?php if (has_error('telepon')): ?>
                            <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;"><?= htmlspecialchars(error('telepon')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Nama Kontak / PIC</label>
                        <input type="text" name="kontak" value="<?= htmlspecialchars(old('kontak')) ?>" placeholder="Nama penanggung jawab">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: var(--spacing-md);">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" placeholder="Jalan, RT/RW, dsb."><?= htmlspecialchars(old('alamat')) ?></textarea>
                </div>

                <div class="form-row" style="display: grid; gap: var(--spacing-md);">
                    <div class="form-group">
                        <label>Kelurahan / Desa</label>
                        <input type="text" name="desa" value="<?= htmlspecialchars(old('desa')) ?>" placeholder="Kelurahan/Desa">
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" value="<?= htmlspecialchars(old('kecamatan')) ?>" placeholder="Kecamatan">
                    </div>
                </div>

                <div class="form-row" style="display: grid; gap: var(--spacing-md); margin-bottom: var(--spacing-md);">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" value="<?= htmlspecialchars(old('provinsi', 'Lampung')) ?>" placeholder="Contoh: Lampung">
                    </div>
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="kode_pos" value="<?= htmlspecialchars(old('kode_pos')) ?>" placeholder="Contoh: 35145">
                    </div>
                </div>
                
                <div class="form-row" style="display: grid; gap: var(--spacing-md); margin-bottom: var(--spacing-lg);">
                    <div class="form-group">
                        <label class="required">Tipe Rumah Sakit</label>
                        <input type="text" name="tipe_rs" value="<?= htmlspecialchars(old('tipe_rs')) ?>" placeholder="Contoh: RS Umum Daerah" required>
                    </div>
                    <div class="form-group">
                        <label class="required">No. Izin Operasional</label>
                        <input type="text" name="no_izin" value="<?= htmlspecialchars(old('no_izin')) ?>" placeholder="Contoh: 123/IZN/RSUD/2022" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-sidora btn-login" style="width: 100%;">
                    <i data-lucide="user-plus"></i> <span>Daftarkan Rumah Sakit</span>
                </button>
            </form>
            <div class="auth-footer">
                <p>Sudah punya akun? <a href="index.php?page=login">Login di sini</a></p>
            </div>
        </div>
        <div class="auth-side">
            <div class="side-content">
                <h2>Selamat Bergabung di<br>SIDORA</h2>
                <p>Daftarkan instansi Anda untuk kemudahan akses permintaan dan monitoring stok darah secara terintegrasi.</p>
                <div class="features">
                    <div class="feature-item">
                        <span class="feature-icon"><i data-lucide="check"></i></span>
                        <span>Akses prioritas permintaan darah</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon"><i data-lucide="check"></i></span>
                        <span>Monitoring stok darah real-time</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon"><i data-lucide="check"></i></span>
                        <span>Proses pengajuan yang efisien</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon"><i data-lucide="check"></i></span>
                        <span>Riwayat distribusi terorganisir</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>
</html>
