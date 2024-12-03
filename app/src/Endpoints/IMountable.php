<?php

declare(strict_types=1);

namespace App\Endpoints;

use Phalcon\Mvc\Micro\CollectionInterface;

interface IMountable
{
	public function isMountable(): bool;

	public function getCollection(): CollectionInterface;
}
