<?php

declare(strict_types=1);

namespace App\Endpoints;

use Phalcon\Http\Response as HttpResponse;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller;

/**
 * @property \Phalcon\Config\Config        $config
 * @property \Phalcon\Mvc\Url              $url
 * @property \Phalcon\Db\Adapter\Pdo\Mysql $db
 * @property \Phalcon\Http\Request         $request
 * @property HttpResponse                  $response
 * @property \Redis                        $redis
 * @property \App\Schemas\System\Identity  $identity
 */
class Endpoint extends Controller
{
	private const DEFAULT_ERROR_CODE = 400;

	/** @var array<string> */
	protected $validationMessages = [];

	/**
	 * @var array<int, string>
	 */
	public static $codes = [
		HttpResponse::STATUS_OK => 'OK',
		HttpResponse::STATUS_BAD_REQUEST => 'Bad Request',
		HttpResponse::STATUS_UNAUTHORIZED => 'Unauthorized',
		HttpResponse::STATUS_NOT_FOUND => 'Not Found',
		HttpResponse::STATUS_CONFLICT => 'Conflict',
		HttpResponse::STATUS_INTERNAL_SERVER_ERROR => 'Server error',
		HttpResponse::STATUS_NO_CONTENT => 'No Content',
		HttpResponse::STATUS_CREATED => 'Created',
	];

	/**
	 * @param array<mixed> $payload
	 */
	protected function respondOk(array|object $payload = []): ResponseInterface|bool
	{
		return self::send($this->response, HttpResponse::STATUS_OK, $payload);
	}

	protected function respondCreated(): ResponseInterface|bool
	{
		return self::send($this->response, HttpResponse::STATUS_CREATED);
	}

	protected function respondNoContent(): ResponseInterface|bool
	{
		return self::send($this->response, HttpResponse::STATUS_NO_CONTENT);
	}

	protected function respondBadRequest(string $message): ResponseInterface|bool
	{
		return self::send($this->response, HttpResponse::STATUS_BAD_REQUEST, new Error(['message' => $message]));
	}

	protected function respondInternalServerError(string $message = 'Internal Server Error'): ResponseInterface|bool
	{
		return self::send($this->response, HttpResponse::STATUS_INTERNAL_SERVER_ERROR, new Error(['message' => $message]));
	}

	/**
	 * @param array<mixed> $payload
	 */
	public static function send(HttpResponse &$response, int $code, array|object $payload = []): ResponseInterface|bool
	{
		$code = $code ?: self::DEFAULT_ERROR_CODE;
		$status = self::$codes[$code] ?? 'unknown';

		$resp = $response->setContentType('application/json', 'UTF-8')->setStatusCode($code, $status);
		if (!in_array($code, [HttpResponse::STATUS_NO_CONTENT, HttpResponse::STATUS_CREATED])) {
			$resp->setJsonContent($payload);
		}

		return $resp->sendHeaders();
	}
}
