<?php

namespace Core;

use Core\Exceptions\HttpException;
use Core\Exceptions\ValidationException;
use ErrorException;
use PDOException;
use Throwable;

class ExceptionHandler
{
    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered) return;
        self::$registered = true;

        // Register handlers
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $e): void
    {
        // 1. ValidationException handling (identical to Laravel's validation error redirect)
        if ($e instanceof ValidationException) {
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }

            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old']    = $_POST;

            if (self::isJsonRequest()) {
                http_response_code(422);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'message' => 'Data yang diberikan tidak valid.',
                    'errors'  => $e->getErrors(),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $redirectUrl = $e->getRedirectTo() ?: ($_SERVER['HTTP_REFERER'] ?? 'index.php');
            header("Location: {$redirectUrl}");
            exit;
        }

        // 2. HTTP Exception handling (404, 403, 419, etc.)
        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            if ($status >= 500) {
                Logger::exception($e, "HTTP {$status} Server Error");
            } elseif ($status === 403) {
                Logger::warning("Akses Ditolak (403): " . $e->getMessage());
            } elseif ($status === 419) {
                Logger::warning("Sesi Kadaluarsa (419): " . $e->getMessage());
            }

            self::renderErrorView($status, $e->getMessage(), $e);
            exit;
        }

        // 3. Database Exception
        if ($e instanceof PDOException) {
            Logger::critical("Database Exception: " . $e->getMessage(), [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $message = 'Terjadi kendala koneksi atau operasi pada basis data sistem.';
            self::renderErrorView(500, $message, $e);
            exit;
        }

        // 4. General / Uncaught Exception
        Logger::exception($e, "Unhandled Exception");
        self::renderErrorView(500, $e->getMessage() ?: 'Terjadi kesalahan tidak terduga pada server.', $e);
        exit;
    }

    public static function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        if (!(error_reporting() & $level)) {
            // Silenced by @
            return false;
        }

        // Fatal/recoverable errors -> convert to ErrorException
        if (in_array($level, [E_USER_ERROR, E_RECOVERABLE_ERROR], true)) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }

        // Log warnings and notices
        if (in_array($level, [E_WARNING, E_USER_WARNING], true)) {
            Logger::warning("PHP Warning: {$message}", ['file' => $file, 'line' => $line]);
        } else {
            Logger::debug("PHP Notice: {$message}", ['file' => $file, 'line' => $line]);
        }

        return false;
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            Logger::critical("PHP Fatal Error: {$error['message']}", [
                'file' => $error['file'],
                'line' => $error['line'],
                'type' => $error['type'],
            ]);

            while (ob_get_level() > 0) {
                @ob_end_clean();
            }

            $exception = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
            self::renderErrorView(500, 'Terjadi kesalahan fatal pada server.', $exception);
        }
    }

    public static function renderErrorView(int $statusCode, string $message, ?Throwable $exception = null): void
    {
        http_response_code($statusCode);

        if (self::isJsonRequest()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => $statusCode,
                'message' => $message,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        $debug = (bool)app_config('debug', false);

        // Find view in views/errors/{code}.php
        $viewFile = __DIR__ . "/../views/errors/{$statusCode}.php";
        if (!file_exists($viewFile)) {
            $viewFile = __DIR__ . '/../views/errors/500.php';
        }

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Fallback plain HTML if error views missing
            echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><title>Error {$statusCode} - SIDORA</title></head><body>";
            echo "<h1>Error {$statusCode}</h1>";
            echo "<p>" . htmlspecialchars($message) . "</p>";
            echo "<a href='index.php'>Kembali ke Beranda</a>";
            echo "</body></html>";
        }
    }

    private static function isJsonRequest(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
        return str_contains($accept, 'application/json') || $isAjax;
    }
}
