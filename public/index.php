<?php

use Slim\Factory\AppFactory;
use App\database\Database;
require_once "vendor/autoload.php";

$app = AppFactory::create();


$app->run();
