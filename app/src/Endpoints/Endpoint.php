<?php

declare(strict_types=1);

namespace App\Endpoints;

use App\Utils\Response;
use Phalcon\Filter\Validation;
use Phalcon\Http\Response as HttpResponse;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller;

class Endpoint extends Controller
{
	/** @var array<string> */
	protected $validationMessages = [];

	/**
	 * @param array<mixed> $payload
	 */
	protected function respondOk(array $payload = [], string $msg = ''): ResponseInterface|bool
	{
		if (empty($msg)) {
			$msg = 'Request completed successfully';
		}

		return Response::send($this->response, HttpResponse::STATUS_OK, $msg, $payload);
	}

	/**
	 * @param array<mixed> $payload
	 */
	protected function respondBadRequest(
		string $msg = '',
		array $payload = [],
		int $httpCode = HttpResponse::STATUS_BAD_REQUEST
	): ResponseInterface|bool {
		return Response::send($this->response, $httpCode, $msg, $payload);
	}

	/**
	 * @param array<mixed> $data
	 */
	protected function isValidRequest(Validation $validator, array $data = []): bool
	{
		if (empty($data)) {
			return false;
		}

		$messages = (new $validator())->validate($data);
		if (count($messages) > 0) {
			foreach ($messages as $message) {
				$this->validationMessages[] = $message->getMessage();
			}

			return false;
		}

		return true;
	}

	protected function getDefaultValidationErrorsResponse(): ResponseInterface|bool
	{
		return $this->respondBadRequest('Validation error(s)', $this->validationMessages);
	}
}
