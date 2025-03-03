<?php

declare(strict_types=1);

namespace App\Schemas\Client;

use App\Services\Response;
use OpenApi\Attributes as OA;

/**
 * @method static setToken(string $token)
 *
 * @method string getToken()
 */
#[OA\Schema(
	type: 'object',
	schema: 'ClientToken',
	properties: [
		new OA\Property(property: 'token', format: 'string', type: 'string', example: 'token'),
	]
)]
class Token extends Response
{
}
