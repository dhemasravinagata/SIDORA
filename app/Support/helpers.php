<?php

use Illuminate\Support\Facades\View;

if (!function_exists('app_config')) {
    function app_config(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return config('app');
        return config("app.{$key}", $default);
    }
}

if (!function_exists('flash')) {
    function flash(string $type, string $message): void
    {
        if (function_exists('session')) {
            session()->flash($type, $message);
        }
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['flash'][$type][] = $message;
        $_SESSION[$type] = $message;
    }
}

if (!function_exists('has_error')) {
    function has_error(string $key): bool
    {
        if (function_exists('session')) {
            $errors = session('errors');
            if ($errors instanceof \Illuminate\Support\ViewErrorBag) {
                return $errors->has($key);
            }
        }
        return !empty($_SESSION['errors'][$key]);
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        if (function_exists('session')) {
            $errors = session('errors');
            if ($errors instanceof \Illuminate\Support\ViewErrorBag) {
                return $errors->first($key);
            }
        }
        return $_SESSION['errors'][$key][0] ?? null;
    }
}

if (!function_exists('render_alerts')) {
    function render_alerts(): void
    {
        static $rendered = false;
        if ($rendered) return;
        $rendered = true;

        if (View::exists('components.alerts')) {
            echo View::make('components.alerts')->render();
        } elseif (file_exists(base_path('views/components/alerts.php'))) {
            include base_path('views/components/alerts.php');
        }
    }
}
