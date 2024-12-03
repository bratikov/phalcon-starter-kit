<?php

declare(strict_types=1);

namespace App\Endpoints\v2\Dummy;

use App\Endpoints\Endpoint;
use App\Models\Dummy;
use Phalcon\Http\ResponseInterface;

class Controller extends Endpoint
{
	/**
	 * @OA\Get(
	 *     tags={"Dummy"},
	 *     path="/dummy",
	 *     summary="List of dummy records",
	 *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=200),
	 *             @OA\Property(property="status", type="string", example="OK"),
	 *             @OA\Property(property="message", type="string", example="Request completed successfully"),
	 *             @OA\Property(
	 *                property="payload",
	 *                type="array",
	 *                @OA\Items(ref="#/components/schemas/Dummy")
	 *             )
	 *         )
	 *     )
	 * )
	 */
	public function list(): ResponseInterface|bool
	{
		return $this->respondOk(Dummy::find()->toArray());
	}

	/**
	 * @OA\Get(
	 *     tags={"Dummy"},
	 *     path="/dummy/{id}",
	 *     summary="Dummy info",
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="ID of record",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=200),
	 *             @OA\Property(property="status", type="string", example="OK"),
	 *             @OA\Property(property="message", type="string", example="Request completed successfully"),
	 *             @OA\Property(
	 *                property="payload",
	 *                type="object",
	 *                ref="#/components/schemas/Dummy"
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Bad Request",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=400),
	 *             @OA\Property(property="status", type="string", example="Bad Request"),
	 *             @OA\Property(property="message", type="string", example="Requested item not found"),
	 *             @OA\Property(
	 *                property="payload",
	 *                example="[]"
	 *             )
	 *         )
	 *     )
	 * )
	 */
	public function get(int $id): ResponseInterface|bool
	{
		/* @var Dummy|null $model */
		$model = Dummy::findFirst($id);
		if (null === $model) {
			return $this->respondBadRequest('Requested item not found');
		}

		return $this->respondOk([$model->toArray()]);
	}

	/**
	 * @OA\Post(
	 *     tags={"Dummy"},
	 *     path="/dummy",
	 *     summary="Add new dummy record",
	 *     @OA\RequestBody(
	 *         description="Dummy object",
	 *         required=true,
	 *         @OA\JsonContent(ref="#/components/schemas/DummyRequest")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=200),
	 *             @OA\Property(property="status", type="string", example="OK"),
	 *             @OA\Property(property="message", type="string", example="Request completed successfully"),
	 *             @OA\Property(
	 *                property="payload",
	 *                type="object",
	 *                ref="#/components/schemas/Dummy"
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Bad Request",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=400),
	 *             @OA\Property(property="status", type="string", example="Bad Request"),
	 *             @OA\Property(property="message", type="string", example="Validation error(s)"),
	 *             @OA\Property(
	 *                property="payload",
	 *                type="array",
	 *                @OA\Items(type="string", example="some validation error")
	 *             )
	 *         )
	 *     )
	 * )
	 */
	public function post(): ResponseInterface|bool
	{
		$data = $this->request->getJsonRawBody(true);

		if (!$this->isValidRequest(new Validator(), $data)) {
			return $this->getDefaultValidationErrorsResponse();
		}

		/** @var Dummy $model */
		$model = new Dummy($data);
		$model->create();

		return $this->respondOk($model->toArray());
	}

	/**
	 * @OA\Put(
	 *     tags={"Dummy"},
	 *     path="/dummy/{id}",
	 *     summary="Update dummy record",
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="ID of dummy record",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int"
	 *         )
	 *     ),
	 *     @OA\RequestBody(
	 *         description="Dummy object",
	 *         required=true,
	 *         @OA\JsonContent(ref="#/components/schemas/DummyRequest")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=200),
	 *             @OA\Property(property="status", type="string", example="OK"),
	 *             @OA\Property(property="message", type="string", example="Request completed successfully"),
	 *             @OA\Property(
	 *                property="payload",
	 *                type="object",
	 *                ref="#/components/schemas/Dummy"
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Bad Request",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=400),
	 *             @OA\Property(property="status", type="string", example="Bad Request"),
	 *             @OA\Property(property="message", type="string", example="Requested item not found"),
	 *             @OA\Property(
	 *                property="payload",
	 *                example="[]"
	 *             )
	 *         )
	 *     )
	 * )
	 */
	public function put(int $id): ResponseInterface|bool
	{
		$data = $this->request->getJsonRawBody(true);
		if (!$this->isValidRequest(new Validator(), $data)) {
			return $this->getDefaultValidationErrorsResponse();
		}

		/** @var Dummy|null $model */
		$model = Dummy::findFirst($id);
		if (null === $model) {
			return $this->respondBadRequest('Requested item not found');
		}

		$model->assign($data)->update();

		return $this->respondOk($model->toArray());
	}

	/**
	 * @OA\Delete(
	 *     tags={"Dummy"},
	 *     path="/dummy/{id}",
	 *     summary="Delete dummy record",
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="ID of dummy record",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="integer",
	 *             format="int"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=200),
	 *             @OA\Property(property="status", type="string", example="OK"),
	 *             @OA\Property(property="message", type="string", example="Request completed successfully"),
	 *             @OA\Property(
	 *                property="payload",
	 *                example="[]"
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Bad Request",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="code", type="int", example=400),
	 *             @OA\Property(property="status", type="string", example="Bad Request"),
	 *             @OA\Property(property="message", type="string", example="Requested item not found"),
	 *             @OA\Property(
	 *                property="payload",
	 *                example="[]"
	 *             )
	 *         )
	 *     )
	 * )
	 */
	public function delete(int $id): ResponseInterface|bool
	{
		/* @var Dummy|null $model */
		$model = Dummy::findFirst($id);
		if (null === $model) {
			return $this->respondBadRequest('Requested item not found');
		}

		try {
			$model->delete();
		} catch (\Throwable $th) {
			\Sentry\captureException($th);

			return $this->respondBadRequest("Can't delete Dummy, with existing assigments to users");
		}

		return $this->respondOk();
	}
}
