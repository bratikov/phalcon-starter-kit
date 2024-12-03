<?php

declare(strict_types=1);

namespace App\Endpoints;

use Phalcon\Mvc\Micro\CollectionInterface;

abstract class AbstractManager
{
	/**
	 * @return array<string>
	 */
	abstract public function getRoutes(): array;

	abstract public static function getVersion(): string;

	public static function produce(string $version): ?AbstractManager
	{
		$manager = "App\\Endpoints\\{$version}\\Manager";
		if (class_exists($manager) && is_a($manager, self::class, true)) {
			return new $manager();
		}

		return null;
	}

	public function getCollection(): ?CollectionInterface
	{
		foreach ($this->getRoutes() as $route) {
			$item = new $route();
			if ($item instanceof IMountable && $item->isMountable()) {
				return $item->getCollection();
			}
			unset($item);
		}

		return null;
	}
}
