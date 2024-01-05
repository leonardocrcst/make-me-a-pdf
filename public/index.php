<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$route = require_once __DIR__.'/../src/Application/Setting/route.php';

$app = AppFactory::create();
$route($app);
$app->run();