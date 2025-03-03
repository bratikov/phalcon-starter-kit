<?php

declare(strict_types=1);

namespace App\Schemas\Dummy;

use App\Services;
use OpenApi\Attributes as OA;

/**
 * @method static setId(int $id)
 * @method static setName(string $name)
 * @method static setCreatedAt(string $createdAt)
 * @method static setUpdatedAt(string $updatedAt)
 *
 * @method int    getId()
 * @method string getName()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 */
#[OA\Schema(
	type: 'object',
	schema: 'DummyResponse',
	properties: [
		new OA\Property(property: 'id', format: 'integer', type: 'integer', example: 1),
		new OA\Property(property: 'name', format: 'string', type: 'string', example: 'Basic'),
		new OA\Property(property: 'createdAt', format: 'string', type: 'string', example: '2021-01-01 00:00:00'),
		new OA\Property(property: 'updatedAt', format: 'string', type: 'string', example: '2021-01-01 00:00:00'),
	]
)]
class Response extends Services\Response
{
}
