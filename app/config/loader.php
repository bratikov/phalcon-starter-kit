<?php

define('APP_PATH', __DIR__.'/../../app');
define('APPENV', getenv('APPENV') ?: 'local');

ini_set('log_errors', 1);
if (APPENV === 'local') {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
} else {
	ini_set('display_errors', 0);
}

require_once APP_PATH.'/vendor/autoload.php';

use App\Services\Di;
use Phalcon\Autoload\Loader;
use Phalcon\Di\FactoryDefault;

$loader = new Loader();
$loader
	->setDirectories([APP_PATH.'/src/'])
	->setNamespaces(['App' => APP_PATH.'/src/'])
	->register();

$di = new FactoryDefault();
Di::registerSharedServices($di);
