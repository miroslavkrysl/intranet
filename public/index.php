<?php

ini_set('display_errors', true);
require('../vendor/autoload.php');
session_start();
echo $_SERVER['REQUEST_URI'];
var_dump($_GET);
var_dump($_POST);
var_dump($_COOKIE);
var_dump($_SESSION);
var_dump($_FILES);
var_dump($_REQUEST);

$gg =[];
$gg['dd']['sdsd'][4] = 41;

var_dump($gg);

//$app = require('../bootstrap/app.php');
