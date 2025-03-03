<?php

declare(strict_types=1);

namespace App\Endpoints\v1;

use OpenApi\Attributes as OA;

#[OA\Info(
	description: 'Your awesome REST API application',
	version: 'v1',
	title: 'App',
	contact: new OA\Contact(
		email: 'email@example.com'
	)
)]
#[OA\Tag(
	name: 'Dummy',
	description: 'Dummy endpoints'
)]
#[OA\Tag(
	name: 'Clients',
	description: 'Clients endpoints'
)]
#[OA\Server(
	url: 'http://<<basepath>>',
	description: 'Test server'
)]
class Manager extends \App\Endpoints\AbstractManager
{
	public function getRoutes(): array
	{
		return [
			Dummy\Route::class,
			Client\Route::class,
		];
	}

	public static function getVersion(): string
	{
		return 'v1';
	}
}
