<?php


namespace Intranet\Http\Middleware;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

class PasswordAuth
{
    /**
     * @var AuthInterface
     */
    private $auth;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * PasswordAuth constructor.
     * @param AuthInterface $auth
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(AuthInterface $auth, UserRepositoryInterface $userRepository)
    {
        $this->auth = $auth;
        $this->userRepository = $userRepository;
    }

    /**
     * Middleware before method.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    public function before(RequestInterface $request)
    {
        $password = $request->_password;
        $password = $request->_password;
        $user = $this->auth->user();
        $updatingUsername = $request->username;

        if (($updatingUsername != $user['username']) or
            ($password and $user and $this->userRepository->verifyPassword($password, $user['password']))) {
            return null;
        }

        $text = $password ? \text('app.auth.password.wrong') : \text('app.auth.password.empty');

        $response = $request->json() ?
            \jsonError(401, ['authorization' => [$text]]) :
            \error(401, \text('app.auth.password.wrong'));

        return $response;
    }
}