<?php
// Raw PHP health check - bypasses Laravel entirely
header('Content-Type: application/json');

$checks = [
    'php_version' => PHP_VERSION,
    'php_works' => true,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
    'port' => $_SERVER['SERVER_PORT'] ?? 'unknown',
];

// Check environment variables
$checks['env_APP_KEY'] = !empty(getenv('APP_KEY')) ? 'SET (' . substr(getenv('APP_KEY'), 0, 10) . '...)' : 'NOT SET';
$checks['env_APP_ENV'] = getenv('APP_ENV') ?: 'NOT SET';
$checks['env_APP_DEBUG'] = getenv('APP_DEBUG') ?: 'NOT SET';
$checks['env_GEMINI_API_KEY'] = !empty(getenv('GEMINI_API_KEY')) ? 'SET' : 'NOT SET';
$checks['env_LOG_CHANNEL'] = getenv('LOG_CHANNEL') ?: 'NOT SET';
$checks['env_SESSION_DRIVER'] = getenv('SESSION_DRIVER') ?: 'NOT SET';

// Check file system
$checks['vendor_autoload_exists'] = file_exists(__DIR__ . '/../vendor/autoload.php');
$checks['bootstrap_app_exists'] = file_exists(__DIR__ . '/../bootstrap/app.php');
$checks['env_file_exists'] = file_exists(__DIR__ . '/../.env');
$checks['storage_dir_exists'] = is_dir(__DIR__ . '/../storage');
$checks['storage_writable'] = is_writable(__DIR__ . '/../storage');
$checks['bootstrap_cache_writable'] = is_writable(__DIR__ . '/../bootstrap/cache');
$checks['vite_manifest_exists'] = file_exists(__DIR__ . '/build/manifest.json');
$checks['vite_build_dir'] = is_dir(__DIR__ . '/build') ? scandir(__DIR__ . '/build') : 'NOT FOUND';
$checks['database_exists'] = file_exists(__DIR__ . '/../database/database.sqlite');

// Check key config files
$checks['config_app_exists'] = file_exists(__DIR__ . '/../config/app.php');
$checks['config_database_exists'] = file_exists(__DIR__ . '/../config/database.php');
$checks['cached_config_exists'] = file_exists(__DIR__ . '/../bootstrap/cache/config.php');
$checks['cached_routes_exists'] = file_exists(__DIR__ . '/../bootstrap/cache/routes-v7.php');

// Try to bootstrap Laravel and catch the EXACT error
$checks['laravel_bootstrap'] = 'NOT ATTEMPTED';
try {
    require __DIR__ . '/../vendor/autoload.php';
    $checks['autoload'] = 'OK';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $checks['app_created'] = 'OK';
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $checks['kernel_created'] = 'OK';
    
    $checks['laravel_bootstrap'] = 'SUCCESS';
} catch (\Throwable $e) {
    $checks['laravel_bootstrap'] = 'FAILED';
    $checks['bootstrap_error'] = $e->getMessage();
    $checks['bootstrap_error_file'] = $e->getFile() . ':' . $e->getLine();
    $checks['bootstrap_error_trace'] = array_slice(
        array_map(fn($t) => ($t['file'] ?? '?') . ':' . ($t['line'] ?? '?') . ' ' . ($t['class'] ?? '') . ($t['type'] ?? '') . ($t['function'] ?? ''), $e->getTrace()),
        0, 10
    );
}

echo json_encode($checks, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
