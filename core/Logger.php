<?php

namespace Core;

use Throwable;

class Logger
{
    public const EMERGENCY = 'EMERGENCY';
    public const ALERT     = 'ALERT';
    public const CRITICAL  = 'CRITICAL';
    public const ERROR     = 'ERROR';
    public const WARNING   = 'WARNING';
    public const NOTICE    = 'NOTICE';
    public const INFO      = 'INFO';
    public const DEBUG     = 'DEBUG';

    private static ?string $logDir = null;

    public static function getLogDir(): string
    {
        if (self::$logDir === null) {
            $config = @include __DIR__ . '/../config/app.php';
            self::$logDir = $config['log_path'] ?? (__DIR__ . '/../storage/logs');
        }

        if (!is_dir(self::$logDir)) {
            @mkdir(self::$logDir, 0777, true);
        }

        return self::$logDir;
    }

    public static function setLogDir(string $dir): void
    {
        self::$logDir = $dir;
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        try {
            $dir = self::getLogDir();
            $today = date('Y-m-d');
            $timestamp = date('Y-m-d H:i:s');

            // Collect request & auth context
            $requestContext = [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
                'url' => $_SERVER['REQUEST_URI'] ?? '/',
            ];

            if (isset($_SESSION['user'])) {
                $requestContext['user_id'] = $_SESSION['user']['id'] ?? null;
                $requestContext['username'] = $_SESSION['user']['username'] ?? $_SESSION['user']['email'] ?? null;
                $requestContext['role'] = $_SESSION['user']['role'] ?? null;
            }

            $mergedContext = array_merge($requestContext, $context);
            $contextJson = !empty($mergedContext) ? ' ' . json_encode($mergedContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';

            $logLine = sprintf("[%s] [%s]: %s%s%s", $timestamp, strtoupper($level), $message, $contextJson, PHP_EOL);

            // Write to both daily file and general log
            $dailyFile = $dir . DIRECTORY_SEPARATOR . "sidora-{$today}.log";
            $generalFile = $dir . DIRECTORY_SEPARATOR . 'sidora.log';

            @file_put_contents($dailyFile, $logLine, FILE_APPEND | LOCK_EX);
            @file_put_contents($generalFile, $logLine, FILE_APPEND | LOCK_EX);
        } catch (Throwable $e) {
            // Fail silently to prevent recursive crash
            error_log("Failed to write SIDORA log: " . $e->getMessage());
        }
    }

    public static function emergency(string $message, array $context = []): void
    {
        self::log(self::EMERGENCY, $message, $context);
    }

    public static function alert(string $message, array $context = []): void
    {
        self::log(self::ALERT, $message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::log(self::CRITICAL, $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log(self::ERROR, $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log(self::WARNING, $message, $context);
    }

    public static function notice(string $message, array $context = []): void
    {
        self::log(self::NOTICE, $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log(self::INFO, $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log(self::DEBUG, $message, $context);
    }

    public static function exception(Throwable $e, ?string $customMessage = null): void
    {
        $message = $customMessage ? "{$customMessage} - " . $e->getMessage() : $e->getMessage();
        $context = [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ];

        self::error($message, $context);
    }
}
