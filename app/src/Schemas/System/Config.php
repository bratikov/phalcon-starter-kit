<?php

declare(strict_types=1);

namespace App\Schemas\System;

use Phalcon\Config\Config as PhalconConfig;

/**
 * @property AppConfig        $application
 * @property DatabaseConfig   $database
 * @property RedisConfig      $redis
 * @property Mq               $mq
 * @property ClickhouseConfig $clickhouse
 * @property Billing          $billing
 */
class Config extends PhalconConfig
{
	public static function load(): Config
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

		/* @var Config $config */
		return new self($conf);
	}
}

/**
 * @property string       $sitename
 * @property string       $sentryDsn
 * @property string       $environment
 * @property string       $secret
 * @property AppJwtConfig $jwt
 */
class AppConfig extends Config
{
}

/**
 * @property string $expires
 * @property string $secret
 */
class AppJwtConfig extends Config
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

/**
 * @property string    $host
 * @property string    $port
 * @property MqChannel $channel
 */
class Mq extends Config
{
}

/**
 * @property string $name
 * @property string $size
 */
class MqChannel extends Config
{
}

/**
 * @property string $host
 * @property string $port
 * @property string $username
 * @property string $password
 * @property string $dbname
 */
class ClickhouseConfig extends Config
{
}

/**
 * @property string $runPlan
 * @property int    $timeRange
 */
class Billing extends Config
{
}
