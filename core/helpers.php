<?php


use Core\Foundation\Application;
use Core\Contracts\Config\ConfigInterface;
use Core\Contracts\Session\SessionManagerInterface;
use Core\Contracts\Cookies\CookieManagerInterface;


/**
 * Get Application instance or a service instance.
 * @param string $service
 * @return mixed|Application
 */
function app(string $service = null)
{
    if ($service == null) {
        return Application::getInstance();
    }
    return Application::getInstance()->get($service);
}


/**
 * Get environment variable.
 * @param $key
 * @return mixed|null
 */
function env($key)
{
    return app()->env($key);
}


/**
 * Get or set the specified setting, or get the config service instance.
 * @param string $key
 * @param mixed $default
 * @return mixed|ConfigInterface
 */
function config(string $key = null, $value = null)
{
    if (is_null($key)) {
        return app('config');
    }

    if (is_null($value)) {
        return app('config')->get($key);
    }

    return app('config')->set($key, $value);
}


/**
 * Get or set the specified session variable, or get the session service instance.
 * @param string $key
 * @param mixed $default
 * @return mixed|SessionManagerInterface
 */
function session(string $key = null, $value = null)
{
    if (is_null($key)) {
        return app('session');
    }

    if (is_null($value)) {
        return app('session')->get($key);
    }

    return app('session')->set($key, $value);
}

/**
 * Get or set the specified cookie, or get the cookie service instance.
 * @param string $key
 * @param mixed $default
 * @return mixed|CookieManagerInterface
 */
function cookie(string $key = null, $value = null)
{
    if (is_null($key)) {
        return app('cookie');
    }

    if (is_null($value)) {
        return app('cookie')->get($key);
    }

    return app('cookie')->set($key, $value);
}


/**
 * Transform multidimensional array into one-dimensional.
 * The nested keys in old array is represented in dot notation in new array.
 * @param array $array
 * @return array
 */
function array_to_dot(array $array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        if (!is_array($value)) {
            $result[$key] = $value;
        }
        else {
            $subArray = array_to_dot($value);
            foreach ($subArray as $subKey => $subValue){
                $result[$key.'.'.$subKey] = $subValue;
            }
        }
    }

    return $result;
}


/**
 * Get application root directory.
 * @return string
 */
function root_dir(): string
{
    return app()->rootDir();
}


/**
 * Get the specified registered path.
 * @param $name
 * @return string
 */
function path($name): string
{
    return app()->path($name);
}


/**
 * Get app copyright text.
 * @return string
 */
function copyright(): string
{
    return sprintf("&copy; %s, %s", env('app.author'), env('app.year'));
}