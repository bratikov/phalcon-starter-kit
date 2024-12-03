<?php

namespace App\Utils;

use Phalcon\Config\Config;

/**
 * @property AppConfig      $application
 * @property DatabaseConfig $database
 * @property RedisConfig    $redis
 */
class ConfigStub extends Config
{
	public static function load(): ConfigStub
	{
		$configContent = file_get_contents(APP_PATH.'/config/'.APPENV.'.app.json');
		if (false == $configContent) {
			throw new \Exception('Config file not found');
		}
		/** @var array<mixed> $conf */
		$conf = json_decode($configContent, true);
		if (null == $conf) {
			throw new \Exception('Config file is not a valid JSON');
		}

		/* @var ConfigStub $config */
		return new self($conf);
	}
}

/**
 * @property string $sentryDsn
 * @property string $environment
 * @property string $timeZone
 */
class AppConfig extends Config
{
}

/**
 * @property string $adapter
 * @property string $host
 * @property string $port
 * @property string $username
 * @property string $password
 * @property string $dbname
 * @property string $charset
 */
class DatabaseConfig extends Config
{
}

/**
 * @property string $host
 * @property string $port
 * @property string $password
 */
class RedisConfig extends Config
{
}
