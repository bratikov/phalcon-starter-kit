<?php

declare(strict_types=1);

namespace App\Schemas\Dummy;

use App\Services;
use OpenApi\Attributes as OA;

/**
 * @method static setName(string $name)
 *
 * @method string getName()
 *
 * @property string $name
 */
#[OA\Schema(
	type: 'object',
	schema: 'DummyRequest',
	properties: [
		new OA\Property(property: 'name', format: 'string', type: 'string', example: 'Ivan Govnov'),
	],
	required: ['name'],
	additionalProperties: false
)]
class Request extends Services\Request
{
}
