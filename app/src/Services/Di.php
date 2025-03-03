<?php

declare(strict_types=1);

namespace App\Services;

use App\Schemas\System\Config;
use Phalcon\Di\Di as DiFactory;
use Phalcon\Di\DiInterface;

class Di
{
	public static function releaseResources(): void
	{
		$container = DiFactory::getDefault();
		if (null === $container) {
			return;
		}

		if ($container->has('db')) {
			$container->remove('db');
		}

		self::registerSharedServices($container);
	}

	public static function registerSharedServices(?DiInterface $container = null): void
	{
		if (null === $container) {
			$container = DiFactory::getDefault();
		}

		$config = Config::load();
		$container?->setShared('config', $config);

		if (!empty($config->application->sentryDsn)) {
			\Sentry\init([
				'dsn' => $config->application->sentryDsn,
				'environment' => $config->application->environment,
			]);
			\Sentry\captureLastError();
		}

		$container?->setShared('db', fn () => self::getDbConnection($config));
	}

	protected static function getDbConnection(Config $config): \Phalcon\Db\Adapter\Pdo\AbstractPdo
	{
		$class = 'Phalcon\Db\Adapter\Pdo\\'.$config->database->adapter;
		$params = [
			'host' => $config->database->host,
			'username' => $config->database->username,
			'password' => $config->database->password,
			'dbname' => $config->database->dbname,
			'charset' => $config->database->charset,
			'options' => [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			],
		];

		/** @var \Phalcon\Db\Adapter\Pdo\Mysql $connection */
		$connection = new $class($params);

		$connection->execute('SET NAMES UTF8', []);

		return $connection;
	}
}
