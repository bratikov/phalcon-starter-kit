<?php

declare(strict_types=1);

namespace App\Schemas\Client;

use App\Services\Request;
use OpenApi\Attributes as OA;

/**
 * @method static setUsername(string $username)
 * @method static setPassword(string $password)
 *
 * @method string getUsername()
 * @method string getPassword()
 */
#[OA\Schema(
	type: 'object',
	schema: 'ClientCredentials',
	properties: [
		new OA\Property(property: 'username', format: 'string', type: 'string', example: 'admin'),
		new OA\Property(property: 'password', format: 'string', type: 'string', example: 'some very strong password'),
	],
	required: ['username', 'password'],
	additionalProperties: false,
)]
class Credentials extends Request
{
}
