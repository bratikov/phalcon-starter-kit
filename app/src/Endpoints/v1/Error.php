<?php

declare(strict_types=1);

namespace App\Endpoints\v1;

use App\Endpoints;
use OpenApi\Attributes as OA;

/**
 * @method static setMessage(string $message)
 */
#[OA\Schema(
	type: 'object',
	schema: 'Error',
	properties: [
		new OA\Property(property: 'message', format: 'string', type: 'string', example: 'message'),
	]
)]
class Error extends Endpoints\Error
{
}
