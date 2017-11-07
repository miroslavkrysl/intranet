<?php

ini_set('display_errors', true);
require('../vendor/autoload.php');
$app = require('../bootstrap/app.php');
session();

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


cookie('gg', 43);

echo app('request')->path();
echo app('request')->method();
var_dump(app('request')->get());