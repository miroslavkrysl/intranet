<?php


namespace Intranet\Contracts\Csrf;


interface CsrfInterface
{
    /**
     * Determine if the token matches the records.
     * @param string $token
     * @return bool
     */
    public function matches(string $token): bool;

    /**
     * Generate a token for session and save it.
     * @return string Generated token
      */
    public function generate(): string;

    /**
     * Determine if the session has a generated token.
     * @return string
     */
    public function has(): string;

    /**
     * Get the token for current session. Generate new if not set.
     * @return string
     */
    public function token(): string;
}