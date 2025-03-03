<?php

declare(strict_types=1);

namespace App\Attributes;

use GuzzleHttp\Psr7\ServerRequest;
use OpenApi\Annotations\Operation;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use Phalcon\Di\Di;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Validable
{
	private string $data;
	private string $class;
	private string $method;

	public function __construct()
	{
	}

	/**
	 * @return string[]
	 */
	protected function getValidableOperations(): array
	{
		return [
			Post::class,
			Put::class,
			Get::class,
			Delete::class,
		];
	}

	public function setClass(string $class): static
	{
		$this->class = $class;

		return $this;
	}

	public function setMethod(string $method): static
	{
		$this->method = $method;

		return $this;
	}

	public function setRequestBody(string $data): static
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	public function validate(): void
	{
		$reflector = new \ReflectionMethod($this->class, $this->method);
		$oaAttributes = $reflector->getAttributes();

		$validableOperation = null;
		foreach ($oaAttributes as $oaAttribute) {
			if (in_array($oaAttribute->getName(), $this->getValidableOperations())) {
				$validableOperation = $oaAttribute->newInstance();
				break;
			}
		}
		if (null === $validableOperation) {
			return;
		}

		/** @var \App\Endpoints\AbstractManager $apiManager */
		$apiManager = Di::getDefault()?->getShared('apiManager');
		$validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder())
			->fromJsonFile($apiManager->getOASchema())
			->getServerRequestValidator();
		/** @var Operation $validableOperation */
		$request = (new ServerRequest(
			$validableOperation->method,
			str_replace('/'.$apiManager->getVersion(), '', Di::getDefault()?->get('request')->getURI()),
			Di::getDefault()?->get('request')->getHeaders(),
			$this->data
		))->withQueryParams(Di::getDefault()?->get('request')->getQuery());
		$validator->validate($request);
	}
}
