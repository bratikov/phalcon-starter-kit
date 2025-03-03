<?php

declare(strict_types=1);

namespace App\Endpoints\v1\Client;

use App\Endpoints\IMountable;
use App\Endpoints\v1\Manager;
use Phalcon\Di\Di;
use Phalcon\Mvc\Micro\Collection;

class Route implements IMountable
{
	public function isMountable(): bool
	{
		if (false === strpos(Di::getDefault()?->getShared('request')->getURI(true), '/'.Manager::getVersion().'/client')) {
			return false;
		}

		return true;
	}

	public function getCollection(): Collection
	{
		$collection = new Collection();
		$collection->setHandler(Controller::class, true)->setPrefix('/'.Manager::getVersion().'/client')
			->post('/token', 'post');

		return $collection;
	}
}
