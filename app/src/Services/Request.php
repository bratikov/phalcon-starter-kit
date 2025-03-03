<?php

declare(strict_types=1);

namespace App\Services;

use Phalcon\Http\Request as HttpRequest;

abstract class Request extends Mutator
{
	/** @var array<string> */
	protected array $children = [];

	public function __construct(?HttpRequest $request = null)
	{
		if (null !== $request) {
			$this->fromHttpRequest($request);
		}
	}

	public function fromHttpRequest(HttpRequest $request): void
	{
		$data = $request->getJsonRawBody();
		if (false === $data) {
			throw new BadRequestException('Invalid JSON');
		}

		$this->fromObj($this, $data);
	}

	public function fromObj(object &$parent, object $object): void
	{
		/** @var \Iterator $object */
		foreach ($object as $property => $value) {
			if (isset($parent->children[$property]) && is_a($parent->children[$property], self::class, true)) {
				switch (gettype($value)) {
					case 'object':
						$child = (new $parent->children[$property]());
						$child->fromObj($child, $value);
						$parent->{$property} = $child;
						break;
					case 'array':
						$children = [];
						foreach ($value as $val) {
							$child = (new $parent->children[$property]());
							$child->fromObj($child, $val);
							$children[] = $child;
						}
						$parent->{$property} = $children;
						break;
				}
				continue;
			}

			$parent->{$property} = $value;
		}
	}

	/**
	 * @return array<string, string|float|int|bool|int[]|object|null>
	 */
	public function toArray(): array
	{
		return $this->data;
	}
}
