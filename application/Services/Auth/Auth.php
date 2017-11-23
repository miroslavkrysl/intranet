<?php


namespace Intranet\Services\Auth;


use Intranet\Contracts\Repositories\LoginRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Services\Auth\Exception\AuthException;

/**
 * Service to hadle logins.
 */
class Auth
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
     * @var bool
     */
    private $isLogged;

    /**
     * Contains logged user.
     * @var array
     */
    private $user;

    /**
     * Auth constructor.
     * @param UserRepositoryInterface $userRepository
     * @param LoginRepositoryInterface $loginRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, LoginRepositoryInterface $loginRepository)
    {
        $this->userRepository = $userRepository;
        $this->loginRepository = $loginRepository;
        $this->deleteOldLogins();
        $this->isLogged = $this->checkLogged();
    }

    /**
     * Delete all expired logins.
     */
    private function deleteOldLogins()
    {
        $this->loginRepository->deleteOlder(64);
    }

    /**
     * Check whether the user is logged.
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->isLogged;
    }

    /**
     * Login the user.
     * @param $userId
     * @param bool $remember
     */
    public function login($userId, bool $remember = false)
    {
        if (!$this->userRepository->findById($userId)) {
            throw new AuthException(\sprintf('User with id %d does not exist.', $userId));
        }

        if ($remember) {
            $this->setToken($userId);
        }
        else {
            session('login_user_id', $userId);
        }
    }

    /**
     * Logout the user.
     * @param $userId
     * @param bool $remember
     */
    public function logout()
    {
        session()->unset('login_user_id');

        if (\cookie()->has('login_id')) {
            $loginId = \cookie('login_id');
            $this->loginRepository->delete($loginId);
            \cookie()->unset('login_id');
        }
        if (\cookie()->has('login_token')) {
            \cookie()->unset('login_token');
        }
    }

    /**
     * Check whether the user is logged and set the logged user.
     */
    private function checkLogged(): bool
    {
        $userId = null;

        if (session()->has('login_user_id')) {
            $userId = session('login_user_id');
        } else if (\cookie()->has('login_id') and \cookie()->has('login_token')) {
            $loginId = \cookie('login_id');
            $loginToken = \cookie('login_token');
            $login = $this->loginRepository->findById($loginId);

            if (!$login) {
                return false;
            }

            if (!\password_verify($loginToken, $login['token'])) {
                return false;
            }

            $userId = $login['user_id'];

            $this->refreshToken($login);
        }
        else {
            return false;
        }

        $this->user = $this->userRepository->findById($userId);

        return !empty($this->user);
    }

    /**
     * Set the token into cookie and into the database.
     * @param $loginId
     */
    private function setToken(int $userId)
    {
        $token = \random_string(128);
        $login = [
            'user_id' => $userId,
            'token' => \password_hash($token, \PASSWORD_DEFAULT)
        ];

        $loginId = $this->loginRepository->save($login);

        \cookie('login_token', $token);
        \cookie('login_id', $loginId);
    }

    /**
     * Refresh the token in cookie and update in database.
     * @param array $login
     */
    private function refreshToken(array $login)
    {
        $token = \random_string(128);
        $login['token'] = \password_hash($token, \PASSWORD_DEFAULT);

        $this->loginRepository->save($login);

        \cookie('login_token', $token);
        \cookie('login_id', $login['id']);
    }
}