<?php

declare(strict_types=1);

namespace App\Endpoints\v2\Dummy;

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\StringLength\Min;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Dummy request model",
 *     schema="DummyRequest"
 * )
 */
class Validator extends Validation
{
	/**
	 * @OA\Property(format="string", type="string", property="name", example="Dummy")
	 */
	public function initialize(): void
	{
		$this->add('name', new Min(['min' => 5]));
	}
}
