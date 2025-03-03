<?php

declare(strict_types=1);

namespace App\Schemas\Dummy;

use App\Services;
use OpenApi\Attributes as OA;

/**
 * @method static addItems(Response $item)
 *
 * @method Response[] getItems()
 *
 * @property Response[] $items
 */
#[OA\Schema(
	type: 'object',
	schema: 'DummyList',
	properties: [
		new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/DummyResponse')),
	]
)]
class Index extends Services\Response
{
	public function __construct()
	{
		$this->items = [];
	}
}
