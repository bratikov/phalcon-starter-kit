<?php

declare(strict_types=1);

namespace App\Endpoints\v2;

/**
 * @OA\Info(
 *     description="Your awesome REST API application",
 *     version="v2",
 *     title="App",
 *     @OA\Contact(
 *         email="email@example.com"
 *     )
 * )
 * @OA\Tag(
 *     name="Dummy",
 *     description="Dummy endpoints",
 * )
 * @OA\Server(
 *     description="Test server",
 *     url="http://test.local/v2"
 * )
 */
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
