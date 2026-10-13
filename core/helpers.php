<?php

use Core\Exceptions\AccessDeniedHttpException;
use Core\Exceptions\HttpException;
use Core\Exceptions\NotFoundHttpException;
use Core\Exceptions\SessionExpiredException;

if (!function_exists('app_config')) {
    function app_config(?string $key = null, mixed $default = null): mixed
    {
        static $config = null;
        if ($config === null) {
            $config = @include __DIR__ . '/../config/app.php';
            if (!is_array($config)) $config = [];
        }

        if ($key === null) return $config;
        return $config[$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['flash'][$type][] = $message;
        // Keep compatibility with legacy single key access
        $_SESSION[$type] = $message;
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        return $_SESSION['errors'][$key][0] ?? null;
    }
}

if (!function_exists('has_error')) {
    function has_error(string $key): bool
    {
        return !empty($_SESSION['errors'][$key]);
    }
}

if (!function_exists('errors')) {
    function errors(): array
    {
        return $_SESSION['errors'] ?? [];
    }
}

if (!function_exists('abort')) {
    function abort(int $statusCode, string $message = ''): void
    {
        match ($statusCode) {
            404 => throw new NotFoundHttpException($message ?: 'Halaman tidak ditemukan'),
            403 => throw new AccessDeniedHttpException($message ?: 'Akses ditolak: Anda tidak memiliki izin untuk halaman ini'),
            419 => throw new SessionExpiredException($message ?: 'Sesi keamanan Anda telah berakhir'),
            default => throw new HttpException($statusCode, $message ?: 'Terjadi kesalahan pada server'),
        };
    }
}

if (!function_exists('abort_if')) {
    function abort_if(bool $condition, int $statusCode, string $message = ''): void
    {
        if ($condition) {
            abort($statusCode, $message);
        }
    }
}

if (!function_exists('abort_unless')) {
    function abort_unless(bool $condition, int $statusCode, string $message = ''): void
    {
        if (!$condition) {
            abort($statusCode, $message);
        }
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('back')) {
    function back(?string $fallback = 'index.php'): void
    {
        $target = $_SERVER['HTTP_REFERER'] ?? $fallback;
        redirect($target);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
    }
}

if (!function_exists('validate_csrf')) {
    function validate_csrf(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return true;
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return !empty($token) && hash_equals($_SESSION['_csrf_token'] ?? '', $token);
    }
}

if (!function_exists('render_alerts')) {
    function render_alerts(): void
    {
        static $rendered = false;
        if ($rendered) return;
        $rendered = true;

        $componentPath = __DIR__ . '/../views/components/alerts.php';
        if (file_exists($componentPath)) {
            include $componentPath;
        }
    }
}
