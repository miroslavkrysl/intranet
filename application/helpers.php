<?php
/**
 * Get the current session csrf token.
 * @return mixed
 */
function csrf_token()
{
    return app('csrf')->token();
}

function auth()
{
    return app('auth');
}