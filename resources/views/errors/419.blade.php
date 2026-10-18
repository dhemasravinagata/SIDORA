<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>419 - Sesi Berakhir | SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=<?= time() ?>">
    <style>
        body.error-page-body {
            background-color: #F3F4F6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: 'Poppins', sans-serif;
            color: #1F2937;
        }
        .error-card {
            background: #FFFFFF;
            max-width: 560px;
            width: 100%;
            border-radius: 16px;
            padding: 44px 36px;
            text-align: center;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
            border-top: 6px solid #D97706;
            position: relative;
        }
        .error-logo {
            margin-bottom: 24px;
        }
        .error-logo img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }
        .error-code-badge {
            display: inline-block;
            background-color: #FEF3C7;
            color: #92400E;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .error-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #FFFBEB;
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }
        .error-title {
            font-size: 1.55rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .error-description {
            font-size: 0.95rem;
            color: #4B5563;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }
        .btn-error-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #991B1B;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 11px 22px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        .btn-error-primary:hover {
            background: #7F1D1D;
            transform: translateY(-1px);
        }
        .btn-error-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F3F4F6;
            color: #374151;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 11px 22px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #D1D5DB;
        }
        .btn-error-secondary:hover {
            background: #E5E7EB;
            color: #111827;
        }
        .error-footer-text {
            margin-top: 32px;
            font-size: 0.82rem;
            color: #9CA3AF;
        }
    </style>
</head>
<body class="error-page-body">

    <div class="error-card">
        <div class="error-logo">
            <img src="assets/img/logo-sidora.png" alt="SIDORA Logo" onerror="this.style.display='none'">
        </div>

        <div class="error-code-badge">KODE ERROR 419</div>

        <div class="error-icon-wrapper">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>

        <h1 class="error-title">Sesi Halaman Berakhir</h1>

        <p class="error-description">
            <?= htmlspecialchars($message ?? 'Sesi keamanan atau formulir Anda telah kadaluarsa karena tidak ada aktivitas dalam waktu lama. Silakan muat ulang halaman atau login kembali.') ?>
        </p>

        <div class="error-actions">
            <a href="javascript:location.reload()" class="btn-error-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                Muat Ulang Halaman
            </a>
            <a href="index.php?page=login" class="btn-error-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Halaman Login
            </a>
        </div>

        <div class="error-footer-text">
            SIDORA &copy; <?= date('Y') ?> &bull; Sistem Informasi Donor Darah
        </div>
    </div>

</body>
</html>
