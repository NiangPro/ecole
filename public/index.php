<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Désactiver le timeout en local (les vendor files sont lents à charger sans OPcache chaud)
if (PHP_SAPI === 'cli-server') {
    set_time_limit(0);
}

// Masquer les E_DEPRECATED de PHP 8.5 (PDO::MYSQL_ATTR_SSL_CA → Pdo\Mysql::ATTR_SSL_CA)
// Ces avertissements viennent du vendor Laravel et sont sans impact fonctionnel.
error_reporting(E_ALL & ~E_DEPRECATED);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
