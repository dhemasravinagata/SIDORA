<?php
$isDebug = (bool)app_config('debug', false);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server | SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=<?= time() ?>">
    <style>
        body.error-page-body {
            background-color: #F3F4F6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            font-family: 'Poppins', sans-serif;
            color: #1F2937;
        }
        .error-card {
            background: #FFFFFF;
            max-width: <?= $isDebug && isset($exception) ? '900px' : '580px' ?>;
            width: 100%;
            border-radius: 16px;
            padding: 44px 36px;
            text-align: center;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
            border-top: 6px solid #B91C1C;
            position: relative;
            transition: max-width 0.3s ease;
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
            background-color: #FEE2E2;
            color: #991B1B;
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
            background: #FEF2F2;
            color: #B91C1C;
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
            margin-bottom: 28px;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-bottom: 20px;
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
            cursor: pointer;
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

        /* Debug Section */
        .debug-container {
            margin-top: 28px;
            text-align: left;
            border: 1px solid #FCA5A5;
            background: #FFF5F5;
            border-radius: 10px;
            overflow: hidden;
            font-size: 0.86rem;
        }
        .debug-summary {
            padding: 12px 18px;
            background: #FEE2E2;
            color: #991B1B;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        .debug-content {
            padding: 16px 18px;
            max-height: 400px;
            overflow-y: auto;
        }
        .debug-meta {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #FECACA;
            color: #374151;
        }
        .debug-meta strong {
            color: #111827;
        }
        .debug-trace {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.78rem;
            background: #181111;
            color: #F3F4F6;
            padding: 14px;
            border-radius: 6px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.5;
        }
        .error-footer-text {
            margin-top: 24px;
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

        <div class="error-code-badge">KODE ERROR 500</div>

        <div class="error-icon-wrapper">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>

        <h1 class="error-title">Terjadi Kesalahan pada Server</h1>

        <p class="error-description">
            <?= htmlspecialchars($message ?? 'Sistem mendeteksi kendala internal saat memproses permintaan Anda. Log insiden telah dicatat secara otomatis oleh sistem pencatat.') ?>
        </p>

        <div class="error-actions">
            <?php
                $isDebug = $isDebug ?? (function_exists('config') ? config('app.debug', false) : false);
                $dashboardUrl = 'index.php?page=login';
                if (!empty($_SESSION['user']['role'])) {
                    $dashboardUrl = match($_SESSION['user']['role']) {
                        'admin' => 'index.php?page=admin-dashboard',
                        'petugas' => 'index.php?page=petugas-dashboard',
                        'rumahsakit' => 'index.php?page=rs-dashboard',
                        default => 'index.php',
                    };
                }
            ?>
            <a href="javascript:location.reload()" class="btn-error-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                Coba Muat Ulang
            </a>
            <a href="<?= $dashboardUrl ?>" class="btn-error-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Dashboard
            </a>
        </div>

        <?php if ($isDebug && isset($exception)): ?>
            <details class="debug-container" open>
                <summary class="debug-summary">
                    <span>Informasi Diagnostik Pengembang (APP_DEBUG=true)</span>
                    <span style="font-size:0.75rem;background:#991B1B;color:#FFF;padding:2px 8px;border-radius:4px;">DEVELOPER MODE</span>
                </summary>
                <div class="debug-content">
                    <div class="debug-meta">
                        <div><strong>Exception:</strong> <?= get_class($exception) ?></div>
                        <div><strong>Pesan:</strong> <?= htmlspecialchars($exception->getMessage()) ?></div>
                        <div><strong>Lokasi:</strong> <?= htmlspecialchars($exception->getFile()) ?> : baris <?= $exception->getLine() ?></div>
                    </div>
                    <div class="debug-trace"><?= htmlspecialchars($exception->getTraceAsString()) ?></div>
                </div>
            </details>
        <?php endif; ?>

        <div class="error-footer-text">
            SIDORA &copy; <?= date('Y') ?> &bull; Sistem Informasi Donor Darah
        </div>
    </div>

</body>
</html>
