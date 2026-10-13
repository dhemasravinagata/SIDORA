<?php

// Collect flash messages
$flashes = [];

// Laravel session flash support
if (function_exists('session')) {
    if ($s = session('success')) {
        $flashes[] = ['type' => 'success', 'message' => is_array($s) ? implode(', ', $s) : $s];
    }
    if ($e = session('error')) {
        $flashes[] = ['type' => 'error', 'message' => is_array($e) ? implode(', ', $e) : $e];
    }
    if ($w = session('warning')) {
        $flashes[] = ['type' => 'warning', 'message' => is_array($w) ? implode(', ', $w) : $w];
    }
    if ($i = session('info')) {
        $flashes[] = ['type' => 'info', 'message' => is_array($i) ? implode(', ', $i) : $i];
    }
}

// Modern flash system
if (!empty($_SESSION['flash'])) {
    foreach ($_SESSION['flash'] as $type => $messages) {
        foreach ((array)$messages as $msg) {
            $flashes[] = ['type' => $type, 'message' => $msg];
        }
    }
    unset($_SESSION['flash']);
}

// Backward-compatible flash keys
if (!empty($_SESSION['success'])) {
    $flashes[] = ['type' => 'success', 'message' => $_SESSION['success']];
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    $flashes[] = ['type' => 'error', 'message' => $_SESSION['error']];
    unset($_SESSION['error']);
}
if (!empty($_SESSION['warning'])) {
    $flashes[] = ['type' => 'warning', 'message' => $_SESSION['warning']];
    unset($_SESSION['warning']);
}
if (!empty($_SESSION['info'])) {
    $flashes[] = ['type' => 'info', 'message' => $_SESSION['info']];
    unset($_SESSION['info']);
}

// Collect validation errors summary
$validationErrors = [];
if (isset($errors) && $errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any()) {
    $validationErrors = $errors->all();
} elseif (!empty($_SESSION['errors'])) {
    $validationErrors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
?>

<?php if (!empty($flashes) || !empty($validationErrors)): ?>
<div class="sidora-toast-container" id="sidoraToastContainer" aria-live="polite" aria-atomic="true">
    <?php foreach ($flashes as $item): ?>
        <?php
            $type = htmlspecialchars($item['type']);
            $msg = htmlspecialchars($item['message']);
            $title = match($type) {
                'success' => 'Berhasil',
                'error'   => 'Kesalahan',
                'warning' => 'Peringatan',
                default   => 'Informasi',
            };
        ?>
        <div class="sidora-toast sidora-toast-<?= $type ?>" role="alert">
            <div class="sidora-toast-icon">
                <?php if ($type === 'success'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                <?php elseif ($type === 'warning'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <?php elseif ($type === 'error'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidora-toast-body">
                <div class="sidora-toast-title"><?= $title ?></div>
                <div class="sidora-toast-message"><?= $msg ?></div>
            </div>
            <button type="button" class="sidora-toast-close" onclick="closeSidoraToast(this)" aria-label="Tutup">&times;</button>
            <div class="sidora-toast-progress"></div>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($validationErrors)): ?>
        <div class="sidora-toast sidora-toast-error" role="alert">
            <div class="sidora-toast-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div class="sidora-toast-body">
                <div class="sidora-toast-title">Periksa Input Anda</div>
                <ul class="sidora-toast-error-list">
                    <?php foreach ($validationErrors as $fieldErrors): ?>
                        <?php foreach ((array)$fieldErrors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button type="button" class="sidora-toast-close" onclick="closeSidoraToast(this)" aria-label="Tutup">&times;</button>
            <div class="sidora-toast-progress"></div>
        </div>
    <?php endif; ?>
</div>

<style>
.sidora-toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 420px;
    width: calc(100vw - 48px);
    pointer-events: none;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.sidora-toast {
    position: relative;
    pointer-events: auto;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 18px 18px 16px;
    border-radius: 10px;
    background: #FFFFFF;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border-left: 5px solid #991B1B;
    overflow: hidden;
    animation: sidoraSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: transform 0.25s ease, opacity 0.25s ease;
}
.sidora-toast.sidora-toast-hiding {
    animation: sidoraSlideOut 0.3s ease forwards;
}
.sidora-toast-success {
    border-left-color: #10B981;
}
.sidora-toast-success .sidora-toast-icon {
    color: #10B981;
    background: #ECFDF5;
}
.sidora-toast-error {
    border-left-color: #991B1B;
}
.sidora-toast-error .sidora-toast-icon {
    color: #991B1B;
    background: #FEE2E2;
}
.sidora-toast-warning {
    border-left-color: #F59E0B;
}
.sidora-toast-warning .sidora-toast-icon {
    color: #D97706;
    background: #FEF3C7;
}
.sidora-toast-info {
    border-left-color: #3B82F6;
}
.sidora-toast-info .sidora-toast-icon {
    color: #2563EB;
    background: #EFF6FF;
}
.sidora-toast-icon {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sidora-toast-body {
    flex: 1;
    min-width: 0;
}
.sidora-toast-title {
    font-size: 0.92rem;
    font-weight: 600;
    color: #111827;
    line-height: 1.3;
    margin-bottom: 3px;
}
.sidora-toast-message {
    font-size: 0.85rem;
    color: #4B5563;
    line-height: 1.45;
    word-break: break-word;
}
.sidora-toast-error-list {
    margin: 4px 0 0 18px;
    padding: 0;
    font-size: 0.82rem;
    color: #6B7280;
    line-height: 1.4;
}
.sidora-toast-error-list li {
    margin-bottom: 3px;
}
.sidora-toast-close {
    flex-shrink: 0;
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #9CA3AF;
    cursor: pointer;
    padding: 0 4px;
    border-radius: 4px;
    transition: color 0.15s, background-color 0.15s;
}
.sidora-toast-close:hover {
    color: #111827;
    background-color: #F3F4F6;
}
.sidora-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: currentColor;
    opacity: 0.25;
    transform-origin: left;
    animation: sidoraProgress 5s linear forwards;
}
.sidora-toast-success .sidora-toast-progress { color: #10B981; }
.sidora-toast-error .sidora-toast-progress   { color: #991B1B; }
.sidora-toast-warning .sidora-toast-progress { color: #F59E0B; }
.sidora-toast-info .sidora-toast-progress    { color: #3B82F6; }

@keyframes sidoraSlideIn {
    from {
        opacity: 0;
        transform: translateX(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}
@keyframes sidoraSlideOut {
    from {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateX(50px) scale(0.95);
    }
}
@keyframes sidoraProgress {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}
</style>

<script>
function closeSidoraToast(button) {
    const toast = button.closest('.sidora-toast');
    if (!toast) return;
    toast.classList.add('sidora-toast-hiding');
    setTimeout(() => {
        toast.remove();
        const container = document.getElementById('sidoraToastContainer');
        if (container && !container.children.length) {
            container.remove();
        }
    }, 300);
}

document.addEventListener('DOMContentLoaded', function () {
    const toasts = document.querySelectorAll('.sidora-toast');
    toasts.forEach(toast => {
        let timer = setTimeout(() => {
            const closeBtn = toast.querySelector('.sidora-toast-close');
            if (closeBtn) closeSidoraToast(closeBtn);
        }, 5000);

        // Pause on mouse hover
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            const progress = toast.querySelector('.sidora-toast-progress');
            if (progress) progress.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', () => {
            const progress = toast.querySelector('.sidora-toast-progress');
            if (progress) progress.style.animationPlayState = 'running';
            timer = setTimeout(() => {
                const closeBtn = toast.querySelector('.sidora-toast-close');
                if (closeBtn) closeSidoraToast(closeBtn);
            }, 2500);
        });
    });
});
</script>
<?php endif; ?>
