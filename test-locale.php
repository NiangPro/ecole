<?php
// Script de test pour vérifier la locale
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simuler une requête
$request = Illuminate\Http\Request::create('/formations', 'GET');
$response = $kernel->handle($request);

echo "Locale détectée: " . app()->getLocale() . PHP_EOL;
echo "Session locale: " . session('locale') . PHP_EOL;
echo "Cookie locale: " . $request->cookie('locale') . PHP_EOL;
echo "Config locale: " . config('app.locale') . PHP_EOL;
echo "Test traduction: " . trans('app.formations.title') . PHP_EOL;

$kernel->terminate($request, $response);

