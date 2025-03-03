<?php

declare(strict_types=1);

namespace App\Services\Security;

use Phalcon\Di\Di;

class Service
{
	public static function encrypt(string $data): string|false
	{
		/** @var \App\Schemas\System\Config $config */
		$config = Di::getDefault()?->getShared('config');
		$cl = openssl_cipher_iv_length('aes-256-cbc');
		if (false === $cl) {
			return false;
		}
		$iv = openssl_random_pseudo_bytes($cl);
		$encryptedData = openssl_encrypt($data, 'aes-256-cbc', $config->application->secret, 0, $iv);

		return base64_encode($iv.$encryptedData);
	}

	public static function decrypt(string $data): string|false
	{
		/** @var \App\Schemas\System\Config $config */
		$config = Di::getDefault()?->getShared('config');
		$data = base64_decode($data);
		$cl = openssl_cipher_iv_length('aes-256-cbc');
		if (false === $cl) {
			return false;
		}
		$iv = substr($data, 0, $cl);

		return openssl_decrypt(substr($data, $cl), 'aes-256-cbc', $config->application->secret, 0, $iv);
	}
}
