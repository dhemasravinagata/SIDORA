<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDORA</title>
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
                <div class="auth-logo" aria-label="SIDORA" style="margin-bottom: 8px;">
                    <span class="auth-logo-mask"></span>
                </div>
                <div style="text-align: center; font-size: 14px; color: #4B5563; margin-bottom: 24px; font-weight: 500; font-family: 'Poppins', sans-serif;">
                    Sistem Informasi Donor Darah
                </div>
            </div>

            <form class="login-form" id="loginForm" method="POST" action="index.php?page=login-process">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="email" class="required">Username atau Email</label>
                    <input type="text" id="email" name="email" value="<?= htmlspecialchars(old('email')) ?>" placeholder="Masukkan username atau email" required autofocus>
                    <?php if (has_error('email')): ?>
                        <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;"><?= htmlspecialchars(error('email')) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="required">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                            <i data-lucide="eye" id="iconEye"></i>
                            <i data-lucide="eye-off" id="iconEyeOff" style="display:none;"></i>
                        </button>
                    </div>
                    <?php if (has_error('password')): ?>
                        <span class="error" style="color:#DC2626;font-size:0.8rem;display:block;margin-top:4px;"><?= htmlspecialchars(error('password')) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" id="rememberMe" name="remember_me">
                    <label for="rememberMe" style="margin-bottom:0;font-weight:normal;">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary-sidora btn-login" style="width: 100%;">
                    <i data-lucide="log-in"></i> <span>Login</span>
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-muted">Belum punya akun? <a href="index.php?page=register-rs">Daftar sebagai Rumah Sakit</a></p>
            </div>
        </div>
    </div>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>
        if (window.lucide) {
            lucide.createIcons();
        }

        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('password');
        const iconEye = document.getElementById('iconEye');
        const iconEyeOff = document.getElementById('iconEyeOff');

        if (toggleBtn && pwdInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = pwdInput.type === 'password';
                pwdInput.type = isPassword ? 'text' : 'password';
                if (iconEye && iconEyeOff) {
                    iconEye.style.display = isPassword ? 'none' : 'inline-block';
                    iconEyeOff.style.display = isPassword ? 'inline-block' : 'none';
                }
            });
        }
    </script>
</body>
</html>
