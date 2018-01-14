<?php


namespace Intranet\Services\Auth;


use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\LoginRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Services\Auth\Exception\AuthException;


/**
 * Service to handle logins.
 */
class Auth implements AuthInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var LoginRepositoryInterface
     */
    private $loginRepository;

    /**
     * Contains logged user.
     * @var array
     */
    private $user;
    /**
     * @var int
     */
    private $loginExpireDays;

    /**
     * Auth constructor.
     * @param UserRepositoryInterface $userRepository
     * @param LoginRepositoryInterface $loginRepository
     * @param int $loginExpireDays
     */
    public function __construct(UserRepositoryInterface $userRepository, LoginRepositoryInterface $loginRepository, int $loginExpireDays)
    {
        $this->userRepository = $userRepository;
        $this->loginRepository = $loginRepository;
        $this->loginExpireDays = $loginExpireDays;
        $this->deleteOldLogins($loginExpireDays);
        $this->user = $this->loadUser();
    }

    /**
     * Login the user.
     * @param $username
     * @param bool $remember
     */
    public function login($username, bool $remember = false)
    {
        if (!$this->userRepository->findByUsername($username)) {
            throw new AuthException(\sprintf('User with username %s does not exist.', $username));
        }

        if ($remember) {
            $login = [
                'id' => \unique_string(),
                'token' => "",
                'user_username' => $username
            ];

            $this->setLogin($login);
        }
        else {
            session('auth_username', $username);
        }
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        session()->unset('auth_username');

        if (\cookie()->has('auth_id')) {
            $loginId = \cookie('auth_id');
            $this->loginRepository->delete($loginId);
            \cookie()->unset('auth_id');
        }
        if (\cookie()->has('auth_token')) {
            \cookie()->unset('auth_token');
        }
    }

    /**
     * Check whether the user is logged.
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->user != null;
    }

    /**
     * Get the logged user.
     * @return array|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Delete all logins older than $days.
     * @param int|null $days
     */
    public function deleteOldLogins(int $days = null)
    {
        $this->loginRepository->deleteOlder($days == null ? $this->loginExpireDays : $days);
    }

    /**
     * Load the logged user.
     * @return array|null
     */
    private function loadUser()
    {
        $username = null;

        if (session()->has('auth_username')) {
            $username = session('auth_username');
        }
        else if (\cookie()->has('auth_id') and \cookie()->has('auth_token')) {
            $id = \cookie('auth_id');
            $token = \cookie('auth_token');
            $login = $this->loginRepository->findById($id);

            if (!$login or !$this->loginRepository->verifyToken($token, $login['token'])) {
                $this->logout();
                return null;
            }

            $username = $login['user_username'];

            $this->setLogin($login);
        }
        else {
            return null;
        }

        return $this->userRepository->findByUsername($username);
    }

    /**
     * Set the login token and save it into the cookie and the database.
     * @param array $login
     */
    private function setLogin(array $login)
    {
        $token = \random_string(128);
        $login['token'] = $this->loginRepository->hashToken($token);

        $this->loginRepository->save($login);

        \cookie('auth_token', $token);
        \cookie('auth_id', $login['id']);
    }

    /**
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this;
    }
}