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
        return $token === $this->session->get('csrf-token');
    }

    /**
     * Generate a token for session and save it.
     * @return string Generated token
     */
    public function generate(): string
    {
        $token = \random_string(64);
        $this->session->set('csrf-token', $token);
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

    /**
     * Get the token for current session. Generate new if not set.
     * @return string
     */
    public function token(): string
    {
        if (!$this->has()) {
            $this->generate();
        }
        return $this->session->get('csrf-token');
    }
}