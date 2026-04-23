<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

// Temporary debug route - shows actual error
Route::get('/debug', function () {
    $info = [
        'php_version' => PHP_VERSION,
        'app_key_set' => !empty(env('APP_KEY')),
        'app_env' => env('APP_ENV'),
        'app_debug' => env('APP_DEBUG'),
        'gemini_key_set' => !empty(env('GEMINI_API_KEY')),
        'storage_writable' => is_writable(storage_path()),
        'bootstrap_cache_writable' => is_writable(base_path('bootstrap/cache')),
        'vite_manifest_exists' => file_exists(public_path('build/manifest.json')),
        'vite_build_dir_exists' => is_dir(public_path('build')),
        'database_exists' => file_exists(database_path('database.sqlite')),
        'public_path' => public_path(),
        'base_path' => base_path(),
        'storage_path' => storage_path(),
    ];

    // Try rendering the view to catch the exact error
    try {
        $view = view('index')->render();
        $info['view_render'] = 'SUCCESS';
    } catch (\Throwable $e) {
        $info['view_render'] = 'FAILED';
        $info['view_error'] = $e->getMessage();
        $info['view_error_file'] = $e->getFile() . ':' . $e->getLine();
    }

    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/', function () {
    return view('index');
});

Route::post('/check', [DocumentController::class, 'check']);
