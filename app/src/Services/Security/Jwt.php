<?php

declare(strict_types=1);

namespace App\Services\Security;

use Phalcon\Di\Di;
use Phalcon\Encryption\Security\JWT\Builder;
use Phalcon\Encryption\Security\JWT\Signer\Hmac;
use Phalcon\Encryption\Security\JWT\Token\Enum;
use Phalcon\Encryption\Security\JWT\Token\Parser;
use Phalcon\Encryption\Security\JWT\Token\Token;
use Phalcon\Encryption\Security\JWT\Validator;
use Phalcon\Encryption\Security\Random;

class Jwt
{
	private const SUBJECT = 'token';

	/**
	 * @param array<string, mixed> $withClaims
	 */
	public static function getToken(array $withClaims = []): string
	{
		/** @var \App\Schemas\System\Config $config */
		$config = Di::getDefault()?->getShared('config');
		$signer = new Hmac();
		$builder = new Builder($signer);

		$now = new \DateTimeImmutable();
		$builder
			->setContentType('application/json')
			->setId((new Random())->uuid())
			->setIssuedAt($now->getTimestamp() - 1)
			->setNotBefore($now->getTimestamp() - 1)
			->setExpirationTime($now->modify('+'.$config->application->jwt->expires.' second')->getTimestamp())
			->setIssuer($config->application->sitename)
			->setSubject(self::SUBJECT)
			->setPassphrase($config->application->jwt->secret);

		foreach ($withClaims as $key => $value) {
			if (null !== EJwtClaim::tryFrom($key)) {
				$builder->addClaim($key, $value);
			}
		}

		$tokenObject = $builder->getToken();

		return $tokenObject->getToken();
	}

	public static function verifyToken(string $token): Token|bool
	{
		$now = (new \DateTime())->getTimestamp();

		$signer = new Hmac();
		$parser = new Parser();
		$tokenObject = $parser->parse($token);
		$validator = new Validator($tokenObject);

		/** @var \App\Schemas\System\Config $config */
		$config = Di::getDefault()?->getShared('config');

		$validator
			->validateExpiration($now)
			->validateIssuedAt($now)
			->validateNotBefore($now)
			->validateIssuer($config->application->sitename)
			->validateSignature($signer, $config->application->jwt->secret);

		return [] === $validator->getErrors() ? $tokenObject : false;
	}

	public static function getTokenTTL(string $token): int
	{
		$parser = new Parser();
		$tokenObject = $parser->parse($token);
		$expiredAt = $tokenObject->getClaims()->get(Enum::EXPIRATION_TIME);
		if (null === $expiredAt) {
			return 0;
		}

		return (int) $tokenObject->getClaims()->get(Enum::EXPIRATION_TIME) - time();
	}
}
