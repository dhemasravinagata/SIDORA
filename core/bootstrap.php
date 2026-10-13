<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configurations
$appConfig = require_once __DIR__ . '/../config/app.php';
date_default_timezone_set($appConfig['timezone'] ?? 'Asia/Jakarta');

// Autoload core files
require_once __DIR__ . '/Exceptions/HttpException.php';
require_once __DIR__ . '/Exceptions/NotFoundHttpException.php';
require_once __DIR__ . '/Exceptions/AccessDeniedHttpException.php';
require_once __DIR__ . '/Exceptions/SessionExpiredException.php';
require_once __DIR__ . '/Exceptions/ValidationException.php';

require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/ExceptionHandler.php';

// Setup Laravel-style Class Aliases for developer convenience
if (!class_exists('Log', false)) {
    class_alias(\Core\Logger::class, 'Log');
}
if (!class_exists('Validator', false)) {
    class_alias(\Core\Validator::class, 'Validator');
}

// Register Global Exception & Error Handler
\Core\ExceptionHandler::register();

// Register automatic notification toast injector via output buffer
ob_start(function ($buffer) {
    // Only inject if buffer is valid HTML and has </body>
    if (stripos($buffer, '</body>') !== false && (
        !empty($_SESSION['flash']) ||
        !empty($_SESSION['success']) ||
        !empty($_SESSION['error']) ||
        !empty($_SESSION['warning']) ||
        !empty($_SESSION['info']) ||
        !empty($_SESSION['errors'])
    )) {
        ob_start();
        render_alerts();
        $alertsHtml = ob_get_clean();

        // Inject right before </body>
        return str_ireplace('</body>', $alertsHtml . "\n</body>", $buffer);
    }
    return $buffer;
});
