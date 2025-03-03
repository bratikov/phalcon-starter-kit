<?php

declare(strict_types=1);

namespace App\Services;

abstract class Response extends Mutator implements \JsonSerializable
{
	/**
	 * @param array<string, string|float|int|bool|int[]|null> $data
	 */
	public function __construct(array $data = [])
	{
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$this->data[$key] = $value;
			}
		}
	}

	/**
	 * @return array<mixed>
	 */
	public function jsonSerialize(): array
	{
		return $this->data;
	}
}
