<?php


use Core\Contracts\Http\ResponseFactoryInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\Language\LanguageInterface;
use Core\Application\Application;
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
 * Get response using data.
 *
 * @return ResponseFactoryInterface
 */
function response()
{
    return app('response');
}

/**
 * Get a html response.
 * @param string $viewName
 * @param array $data
 * @return ResponseInterface
 */
function html(string $viewName, array $data = [])
{
    return response()->html($viewName, $data);
}

/**
 * Get a json response.
 * @param array $data
 * @return ResponseInterface
 */
function json(array $data = [])
{
    return response()->json($data);
}


/**
 * Get a redirection response.
 * @param string $url
 * @return ResponseInterface
 */
function redirect(string $url) {
    return response()->redirect($url);
}


/**
 * Get an error response.
 * @param int $code
 * @param string|null $message
 * @return ResponseInterface
 */
function error(int $code, string $message = null)
{
    return response()->error($code, $message);
}

/**
 * Get a jsonError response.
 * @param int $code
 * @param string|null $message
 * @return ResponseInterface
 */
function jsonError(int $code, string $message = null)
{
    return response()->jsonError($code, $message);
}

/**
 * Get a download response.
 * @param string $filename
 * @param string|null $name
 * @return ResponseInterface
 */
function download(string $filename, string $name = null)
{
    return response()->download($filename, $name);
}


/**
 * Get the translation for the key or the language service instance.
 * @param string $key
 * @param string|LanguageInterface $locale
 */
function text(string $key = null, array $replace = [], int $count = null, string $locale = null)
{
    if (is_null($key)) {
        return app('language');
    }
    return app('language')->get($key, $replace, $count, $locale);
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


/**
 * Generate random string.
 * @param int $length
 * @return string
 */
function random_string(int $length = 16)
{
    $string = '';

    while (($len = strlen($string)) < $length) {
        $size = $length - $len;

        $bytes = random_bytes($size);

        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }

    return $string;
}

/**
 * Generate unique string.
 * @return string
 */
function unique_string()
{
    return md5(uniqid(rand(), true));
}