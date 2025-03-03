<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Models\Client as ClientModel;
use App\Schemas\Client\Credentials;
use App\Schemas\Client\Token;
use App\Services\BadRequestException;
use Phalcon\Di\Di;

class Client
{
	/**
	 * @throws BadRequestException
	 */
	public function getToken(Credentials $credentials): Token
	{
		$client = ClientModel::findFirst([
			'username = :username:',
			'bind' => [
				'username' => $credentials->getUsername(),
			],
		]);

		if (empty($client)) {
			throw new BadRequestException('Invalid username or password');
		}

		$security = Di::getDefault()?->get('security');
		if (!$security->checkHash($credentials->getPassword(), $client->getPassword())) {
			throw new BadRequestException('Invalid username or password');
		}

		$response = new Token();
		$response->setToken(Jwt::getToken([EJwtClaim::ROLE->value => $client->getRole()]));

		return $response;
	}
}
