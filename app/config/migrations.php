<?php

define('APP_PATH', __DIR__.'/../../app');
define('APPENV', getenv('APPENV') ?: 'local');

use App\Schemas\System\Config;
use Phalcon\Autoload\Loader;

$loader = new Loader();
$loader
	->setDirectories([APP_PATH.'/src/'])
	->setNamespaces(['App' => APP_PATH.'/src/'])
	->register();
$config = Config::load();

return new Phalcon\Config\Config([
	'database' => $config->database->toArray(),
	'application' => [
		'logInDb' => true,
		'migrationsDir' => APP_PATH.'/migrations',
		'migrationsTsBased' => true,
		'no-auto-increment' => true,
		'skip-ref-schema' => true,
		'skip-foreign-checks' => true,
		'descr' => 1,
	],
]);
