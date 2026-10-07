<?php
use App\Routes;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/Routes.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

Routes::start($app);


$app->run();