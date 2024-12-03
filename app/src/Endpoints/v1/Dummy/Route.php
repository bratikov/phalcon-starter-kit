<?php

declare(strict_types=1);

namespace App\Endpoints\v1\Dummy;

use App\Endpoints\IMountable;
use App\Endpoints\v1\Manager;
use Phalcon\Di\Di;
use Phalcon\Mvc\Micro\Collection;

class Route implements IMountable
{
	public function isMountable(): bool
	{
		if (false === strpos(Di::getDefault()?->getShared('request')->getURI(true), '/'.Manager::getVersion().'/dummy')) {
			return false;
		}

		return true;
	}

	public function getCollection(): Collection
	{
		$collection = new Collection();
		$collection->setHandler(Controller::class, true)->setPrefix('/'.Manager::getVersion().'/dummy')
			->get('/', 'list')
			->get('/{id}', 'get')
			->post('/', 'post')
			->put('/{id}', 'put')
			->delete('/{id}', 'delete');

		return $collection;
	}
}
