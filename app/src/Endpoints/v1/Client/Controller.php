<?php

declare(strict_types=1);

namespace App\Endpoints\v1\Client;

use App\Attributes\Validable;
use App\Endpoints\Endpoint;
use App\Schemas\Client\Credentials;
use App\Services\BadRequestException;
use App\Services\Security\Client;
use OpenApi\Attributes as OA;
use Phalcon\Http\ResponseInterface;

class Controller extends Endpoint
{
	#[OA\Post(
		tags: ['Clients'],
		path: '/client/token',
		summary: 'Client token request',
		operationId: 'getToken',
		requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ClientCredentials')),
		responses: [
			new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/ClientToken')),
			new OA\Response(response: 400, description: 'Bad Request', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
			new OA\Response(response: 500, description: 'Internal Server Error', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
		]
	)]
	#[Validable]
	public function post(): ResponseInterface|bool
	{
		try {
			$credentials = new Credentials($this->request);
			$client = new Client();

			return $this->respondOk($client->getToken($credentials));
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}
}
