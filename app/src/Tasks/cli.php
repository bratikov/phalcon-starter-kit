#!/usr/bin/env php
<?php

declare(strict_types=1);

define('APP_PATH', __DIR__.'/../..');
define('APPENV', getenv('APPENV') ?: 'local');

require_once APP_PATH.'/vendor/autoload.php';

use App\Services\Di;
use Phalcon\Autoload\Loader;
use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli as CliDI;

$loader = new Loader();
$loader->setDirectories([APP_PATH.'/src/'])->register();
$loader->setNamespaces(['App' => APP_PATH.'/src/']);
$loader->register();

$container = new CliDI();
$dispatcher = new Dispatcher();

$dispatcher->setDefaultNamespace('App\Tasks');
$container->setShared('dispatcher', $dispatcher);

Di::registerSharedServices($container);

$console = new Console($container);
$arguments = [];
foreach ($argv as $k => $arg) {
	if (1 === $k) {
		$arguments['task'] = $arg;
	} elseif (2 === $k) {
		$arguments['action'] = $arg;
	} elseif ($k >= 3) {
		$arguments['params'][] = $arg;
	}
}

try {
	$console->handle($arguments);
} catch (Exception $e) {
	fwrite(STDERR, $e->getMessage().PHP_EOL);
	exit(1);
}
