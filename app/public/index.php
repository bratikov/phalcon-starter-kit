<?php

declare(strict_types=1);

require_once __DIR__.'/../config/loader.php';

use App\Endpoints\AbstractManager;
use App\Utils\Response;
use Phalcon\Di\Di;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Http\Response as HttpResponse;
use Phalcon\Mvc\Micro;

$eventsManager = new EventsManager();
$eventsManager->attach(
	'micro:beforeExecuteRoute',
	function (Event $event, Micro $app) {
		// Implement your logic here, like authentication, etc.
	}
);
echo $b;
$a = array();

$eventsManager->attach(
	'micro:afterExecuteRoute',
	function (Event $event, Micro $app) {
		// Implement your logic here, like logging, etc.
	}
);

$app = new Micro(Di::getDefault());
$app->setEventsManager($eventsManager);

$apiVersion = explode('/', trim($app->request->getURI(), '/'))[0];
$manager = AbstractManager::produce($apiVersion);
if (!$manager) {
	return Response::send($app->response, HttpResponse::STATUS_NOT_FOUND, 'Invalid API version');
}

$collection = $manager->getCollection();
if ($collection) {
	$app->mount($collection);
}

$app->notFound(function () use ($app) {
	return Response::send($app->response, HttpResponse::STATUS_NOT_FOUND);
});

$request = new Phalcon\Http\Request();
$app->handle($request->getURI());
