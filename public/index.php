<?php

use Core\Application\Application;


ini_set('display_errors', true);


// require autoloader
require('../vendor/autoload.php');

// make app
/** @var Application $app */
$app = require('../bootstrap/app.php');

// process request and send response
$request = app('request');
$response = $app->handle($request);
$response->send();