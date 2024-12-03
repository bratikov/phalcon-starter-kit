<?php

declare(strict_types=1);

namespace App\Utils;

use Phalcon\Http\Response as HttpResponse;
use Phalcon\Http\ResponseInterface;

class Response
{
	private const DEFAULT_ERROR_CODE = 400;

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
	];

	/**
	 * @param array<mixed> $payload
	 */
	public static function send(HttpResponse &$response, int $code, string $msg = '', array $payload = []): ResponseInterface|bool
	{
		$code = $code ?: self::DEFAULT_ERROR_CODE;
		$status = self::$codes[$code] ?? 'unknown';

		return $response
			->setContentType('application/json', 'UTF-8')
			->setStatusCode($code, $status)
			->setJsonContent([
				'code' => $code,
				'status' => $status,
				'message' => $msg,
				'payload' => $payload,
			])
			->sendHeaders();
	}
}
