<?php

declare(strict_types=1);

require_once __DIR__.'/../config/loader.php';

use App\Attributes\Accessable;
use App\Attributes\Validable;
use App\Endpoints\AbstractManager;
use App\Endpoints\Endpoint;
use App\Endpoints\Error;
use App\Schemas\System\ERole;
use App\Schemas\System\Identity;
use App\Services\Security\EJwtClaim;
use App\Services\Security\Jwt;
use Phalcon\Di\Di;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Http\Response as HttpResponse;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Micro;

$eventsManager = new EventsManager();
$eventsManager->attach(
	'micro:beforeExecuteRoute',
	function (Event $event, Micro $app) {
		$className = $app->getActiveHandler()[0]->getDefinition();
		$methodName = $app->getActiveHandler()[1];
		$reflector = new ReflectionMethod($className, $methodName);

		// Check validable methods attributes, if set - validate request
		$validableAttributes = $reflector->getAttributes(Validable::class);
		if (!empty($validableAttributes)) {
			try {
				$validableAttributes[0]
					->newInstance()
					->setClass($className)
					->setMethod($methodName)
					->setRequestBody($app->request->getRawBody())
					->validate();
			} catch (Exception $e) {
				$message = $e->getPrevious() ? ($e->getPrevious()->getMessage() ?: $e->getMessage()) : 'Unknown error';
				$response = Endpoint::send($app->response, HttpResponse::STATUS_BAD_REQUEST, new Error(['message' => $message]));
				if ($response instanceof ResponseInterface) {
					$response->send();
				}

				return false;
			}
		}

		// Check if given token has access to the method
		$accessableAttributes = $reflector->getAttributes(Accessable::class);
		if ($accessableAttributes) {
			// Get client token and verify it, if not valid - return unauthorized
			$token = str_replace('Bearer ', '', $app->request->getHeader('Authorization'));
			if (empty($token)) {
				$app->response->setStatusCode(401, 'No Unauthorized')->sendHeaders();

				return false;
			}
			if (($jwtToken = Jwt::verifyToken($token)) === false) {
				$app->response->setStatusCode(401, 'Token expired or invalid')->sendHeaders();

				return false;
			}

			$accessMethod = $accessableAttributes[0]->newInstance();
			/** @var Phalcon\Encryption\Security\JWT\Token\Token $jwtToken */
			$clientRole = ERole::from($jwtToken->getClaims()->get(EJwtClaim::ROLE->value));
			if (!$accessMethod->hasRole($clientRole)) {
				$app->response->setStatusCode(403, 'Forbidden')->sendHeaders();

				return false;
			}

			// Setup identity
			$app->getDI()->setShared('identity', function () use ($clientRole) {
				return new Identity($clientRole);
			});
		}
	}
);

$eventsManager->attach(
	'micro:afterExecuteRoute',
	function (Event $event, Micro $app) {
		// Implement your logic here, like logging, etc.
	}
);

$app = new Micro(Di::getDefault());
$app->setEventsManager($eventsManager);

$apiVersion = explode('/', trim($app->request->getURI(), '/'))[0];
$apiManager = AbstractManager::produce($apiVersion);
if (!$apiManager) {
	return Endpoint::send($app->response, HttpResponse::STATUS_NOT_FOUND);
}
Di::getDefault()?->setShared('apiManager', $apiManager);

$collection = $apiManager->getCollection();
if ($collection) {
	$app->mount($collection);
}

$app->notFound(function () use ($app) {
	return Endpoint::send($app->response, HttpResponse::STATUS_NOT_FOUND);
});

$request = new Phalcon\Http\Request();
$app->handle($request->getURI());
