<?php

declare(strict_types=1);

namespace App\Services;

abstract class Mutator
{
	/** @var array<string, string|float|int|bool|int[]|object|null> */
	protected $data = [];

	/**
	 * @param array<string|float|int|bool|null> $arguments
	 *
	 * @return string|float|int|bool|object|int[]|null
	 */
	public function __call(string $name, array $arguments): string|float|int|bool|object|array|null
	{
		if (count($arguments) > 1) {
			throw new \RuntimeException('Too many arguments');
		}

		$method = substr($name, 0, 3);
		$property = lcfirst(substr($name, 3));

		if ('get' === $method) {
			return $this->data[$property] ?? null;
		}

		if ('set' === $method) {
			$this->data[$property] = $arguments[0];

			return $this;
		}

		if ('add' === $method) {
			if (!isset($this->data[$property]) || !is_array($this->data[$property])) {
				$this->data[$property] = [];
			}

			array_push($this->data[$property], $arguments[0]);

			return $this;
		}

		throw new \RuntimeException("Method {$name} does not exist");
	}

	/**
	 * @param string|float|int|bool|int[]|object|null $value
	 */
	public function __set(string $name, string|float|int|bool|array|object|null $value): void
	{
		$this->data[$name] = $value;
	}

	/**
	 * @return string|float|int|bool|object|int[]|null
	 */
	public function __get(string $name): string|float|int|bool|array|object|null
	{
		return $this->data[$name] ?? null;
	}

	public function __unset(string $name): void
	{
		if (isset($this->data[$name])) {
			unset($this->data[$name]);
		}
	}
}
