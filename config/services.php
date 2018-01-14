<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Container\ParameterReference as PR;
use Core\Container\ServiceReference as SR;
use Core\Contracts\Database\DatabaseInterface;
use Core\Cookies\CookieManager;
use Core\Database\PDOWrapper;
use Core\Http\Request;
use Core\Language\Language;
use Core\Session\SessionManager;
use Core\Validation\Validator;
use Core\View\TwigView;

use Core\View\TwigViewFactory;
use Intranet\Http\Controllers\DashboardController;
use Intranet\Http\Controllers\DocumentController;
use Intranet\Http\Controllers\LoginController;
use Intranet\Http\Controllers\RequestController;
use Intranet\Http\Controllers\UserController;
use Intranet\Http\Middleware\Csrf as CsrfMiddleware;
use Intranet\Http\Middleware\RedirectNonLogged;
use Intranet\Repositories\LoginRepository;
use Intranet\Repositories\UserRepository;
use Intranet\Services\Auth\Auth;
use Intranet\Services\Csrf\Csrf as CsrfService;

/**
 * This file contains services registrations to the container.
 */


/** @var Container $container */
$container = app();


/*
|-------------------------------|
|         Core services         |
|-------------------------------|
*/


// config - should come first to instant use
$container->register('config', Config::class)
    ->addCall('loadFromFile', [path('settings')]);


// request
$container->register('request', Request::class)
    ->addArgument(new SR('validator'))
    ->addCall('createFromGlobals');


// router
$container->register('router', \Core\Routing\Router::class)
    ->addArgument(new SR('container'));


// responses
$container->register('response', \Core\Http\ResponseFactory::class)
    ->addArgument(new SR('view'));


// view
$container->register('view', TwigViewFactory::class)
    ->addArgument(path('views'))
    ->addCall('registerFunction', ['_text', [new SR('language'), 'get']])
    ->addCall('registerFunction', ['_config', [new SR('config'), 'get']])
    ->addCall('registerFunction', ['_token', [new SR('csrf'), 'token']])
    ->addCall('registerFunction', ['_auth', new SR('auth')])
    ->addCall('registerFunction', ['_copyright', 'copyright']);


// language
$container->register('language', Language::class)
    ->addArgument(path('language'))
    ->addArgument(\config('app.locale'))
    ->addArgument(\config('app.fallback_locale'));


// database
$container->register('database', PDOWrapper::class)
    ->addArgument(config('database.type'))
    ->addArgument(config('database.host'))
    ->addArgument(config('database.dbname'))
    ->addArgument(config('database.username'))
    ->addArgument(config('database.password'));


// session
$container->register('session', SessionManager::class);


// cookies
$container->register('cookie', CookieManager::class);


// validation
$container->register('validator', Validator::class)
    ->addArgument(new SR('database'))
    ->addArgument(new SR('language'))
    ->addArgument(config('validator.lang_prefix_messages'))
    ->addArgument(config('validator.lang_prefix_fields'));


/*
|-------------------------------|
|         App services          |
|-------------------------------|
*/


// services

$container->register('csrf', CsrfService::class)
    ->addArgument(new SR('session'));

$container->register('auth', Auth::class)
    ->addArgument(new SR('repository.user'))
    ->addArgument(new SR('repository.login'))
    ->addArgument(\config('auth.login_expire_days'));


// middleware

$container->register('middleware.csrf', CsrfMiddleware::class)
    ->addArgument(new SR('csrf'));

$container->register('middleware.redirectNonLogged', RedirectNonLogged::class)
    ->addArgument(new SR('auth'));


// repositories

$container->register('repository.user', UserRepository::class)
    ->addArgument(new SR('database'))
    ->addArgument(config('database.tables.user'))
    ->addArgument(config('database.tables.role_permission'))
    ->addArgument(config('database.tables.permission'));

$container->register('repository.login', LoginRepository::class)
    ->addArgument(new SR('database'))
    ->addArgument(config('database.tables.login'));


// controllers

$container->register('UserController', UserController::class)
    ->addArgument(new SR('repository.user'))
    ->addArgument(new SR('auth'));

$container->register('LoginController', LoginController::class)
    ->addArgument(new SR('repository.user'))
    ->addArgument(new SR('auth'));

$container->register('DashboardController', DashboardController::class)
    ->addArgument(new SR('repository.user'));

$container->register('RequestController', RequestController::class);

$container->register('DocumentController', DocumentController::class);