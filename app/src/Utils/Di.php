<?php

declare(strict_types=1);

namespace App\Utils;

use Phalcon\Di\FactoryDefault;

class Di
{
	public static function registerSharedServices(FactoryDefault &$container): void
	{
		$config = ConfigStub::load();
		$container->setShared('config', $config);

		if (!empty($config->application->sentryDsn)) {
			\Sentry\init([
				'dsn' => $config->application->sentryDsn,
				'environment' => $config->application->environment,
			]);
			\Sentry\captureLastError();
		}

		$container->setShared('db', function () use ($config) {
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
			$connection->execute("SET time_zone = '{$config->application->timeZone}'", []);

			return $connection;
		});
	}
}
