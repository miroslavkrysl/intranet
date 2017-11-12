<?php


namespace Intranet\Services\Csrf;


use Core\Contracts\Session\SessionManagerInterface;
use Intranet\Contracts\Csrf\CsrfInterface;


class Csrf implements CsrfInterface
{
    /**
     * The session manager implementation.
     * @var SessionManagerInterface
     */
    private $session;

    /**
     * Csrf constructor.
     * @param SessionManagerInterface $session
     */
    public function __construct(SessionManagerInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Determine if the token matches session value.
     * @param string $token
     * @return bool
     */
    public function matches(string $token): bool
    {
        $hash = $this->session->get('csrf-token');

        return \password_verify($token, $hash);
    }

    /**
     * Generate a token for session and save it.
     * @return string Generated token
     */
    public function generate(): string
    {
        $token = \random_string(64);
        $hash = \password_hash($token, \PASSWORD_DEFAULT);
        $this->session->set('csrf-token', $hash);
        return $token;
    }

    /**
     * Determine if the session has a generated token.
     * @return string Generated token
     */
    public function has(): string
    {
        return $this->session->has('csrf-token');
    }
}