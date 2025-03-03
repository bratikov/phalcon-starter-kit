<?php

declare(strict_types=1);

namespace App\Endpoints\v2;

use OpenApi\Attributes as OA;

#[OA\Info(
	description: 'Your awesome REST API application',
	version: 'v2',
	title: 'App',
	contact: new OA\Contact(
		email: 'email@example.com'
	)
)]
#[OA\Tag(
	name: 'Dummy',
	description: 'Dummy endpoints'
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
		];
	}

	public static function getVersion(): string
	{
		return 'v2';
	}
}
