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

/*
echo '<hr />';

echo '<h1>GLOBALS</h1>';

echo '<br />';
echo 'uri:';
echo $_SERVER['REQUEST_URI'];
echo '<br />';
echo 'get:';
var_dump($_GET);
echo '<br />';
echo 'post:';
var_dump($_POST);
echo '<br />';
echo 'cookie: ';
var_dump($_COOKIE);
echo '<br />';
echo 'session: ';
var_dump($_SESSION);
echo '<br />';
echo 'files: ';
var_dump($_FILES);
echo '<br />';
echo 'request: ';
var_dump($_REQUEST);

echo "<hr />";

*/