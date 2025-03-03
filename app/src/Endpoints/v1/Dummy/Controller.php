<?php

declare(strict_types=1);

namespace App\Endpoints\v1\Dummy;

use App\Attributes\Accessable;
use App\Attributes\Validable;
use App\Endpoints\Endpoint;
use App\Schemas\Dummy\Request;
use App\Schemas\System\ERole;
use App\Services\BadRequestException;
use App\Services\Dummy\Service;
use OpenApi\Attributes as OA;
use Phalcon\Http\ResponseInterface;

class Controller extends Endpoint
{
	#[OA\Get(
		tags: ['Dummy'],
		path: '/dummy',
		summary: 'List of dummy records',
		operationId: 'listDummy',
		responses: [
			new OA\Response(
				response: 200,
				description: 'OK',
				content: new OA\JsonContent(ref: '#/components/schemas/DummyList')
			),
		]
	)]
	#[Validable]
	public function list(): ResponseInterface|bool
	{
		try {
			$service = new Service();

			return $this->respondOk($service->list());
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}

	#[OA\Get(
		tags: ['Dummy'],
		path: '/dummy/{id}',
		summary: 'Dummy info',
		operationId: 'getDummy',
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				description: 'ID of dummy that needs to be fetched',
				required: true,
				schema: new OA\Schema(type: 'integer', format: 'int')
			),
		],
		responses: [
			new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/DummyResponse')),
			new OA\Response(response: 400, description: 'Bad Request', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
			new OA\Response(response: 500, description: 'Internal Server Error', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
		]
	)]
	#[Validable]
	public function get(int $id): ResponseInterface|bool
	{
		try {
			$dummyResponseScheme = (new Service())->get($id);

			return $this->respondOk($dummyResponseScheme);
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}

	#[OA\Post(
		tags: ['Dummy'],
		path: '/dummy',
		summary: 'Add new dummy record',
		operationId: 'addDummy',
		requestBody: new OA\RequestBody(
			description: 'Dummy object',
			required: true,
			content: new OA\JsonContent(ref: '#/components/schemas/DummyRequest')
		),
		responses: [
			new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/DummyResponse')),
			new OA\Response(response: 400, description: 'Bad Request', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
			new OA\Response(response: 500, description: 'Internal Server Error', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
		]
	)]
	#[Accessable(ERole::ROLE_OWNER, ERole::ROLE_MAINTAINER)]
	#[Validable]
	public function post(): ResponseInterface|bool
	{
		$service = new Service();
		$dummyRequestScheme = new Request($this->request);

		try {
			$dummyResponseScheme = $service->create($dummyRequestScheme);

			return $this->respondOk($dummyResponseScheme);
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}

	#[OA\Put(
		tags: ['Dummy'],
		path: '/dummy/{id}',
		summary: 'Update dummy record',
		operationId: 'updateDummy',
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				description: 'ID of dummy record that needs to be updated',
				required: true,
				schema: new OA\Schema(type: 'integer')
			),
		],
		requestBody: new OA\RequestBody(
			description: 'Dummy object',
			required: true,
			content: new OA\JsonContent(ref: '#/components/schemas/DummyRequest')
		),
		responses: [
			new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: '#/components/schemas/DummyResponse')),
			new OA\Response(response: 400, description: 'Bad Request', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
			new OA\Response(response: 500, description: 'Internal Server Error', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
		]
	)]
	#[Validable]
	public function put(int $id): ResponseInterface|bool
	{
		$service = new Service();
		$dummyRequestScheme = new Request($this->request);

		try {
			$dummyResponseScheme = $service->update($id, $dummyRequestScheme);

			return $this->respondOk($dummyResponseScheme);
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}

	#[OA\Delete(
		tags: ['Dummy'],
		path: '/dummy/{id}',
		summary: 'Delete dummy record',
		operationId: 'deleteDummy',
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				description: 'ID of dummy that needs to be deleted',
				required: true,
				schema: new OA\Schema(type: 'integer', format: 'int')
			),
		],
		responses: [
			new OA\Response(response: 204, description: 'No Content'),
			new OA\Response(response: 400, description: 'Bad Request', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
			new OA\Response(response: 500, description: 'Internal Server Error', content: new OA\JsonContent(ref: '#/components/schemas/Error')),
		]
	)]
	#[Validable]
	public function delete(int $id): ResponseInterface|bool
	{
		try {
			(new Service())->delete($id);

			return $this->respondNoContent();
		} catch (\Exception $e) {
			if ($e instanceof BadRequestException) {
				return $this->respondBadRequest($e->getMessage());
			}

			\Sentry\captureException($e);

			return $this->respondInternalServerError();
		}
	}
}
