<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Services\Auth\Auth;

class LoginController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * LoginController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Auth $auth
     */
    public function __construct(UserRepositoryInterface $userRepository, Auth $auth)
    {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
    }

    /**
     * Show login form.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function showLoginForm(RequestInterface $request)
    {
        return \html('login');
    }

    /**
     * Login the user on the machine.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseFactoryInterface|ResponseInterface
     */
    public function login(RequestInterface $request)
    {
        $valid = $request->validate([
            'username' => [
                'required',
                'exists' => [
                    'table' => 'user',
                    'column' => 'username'
                ]
            ],
            'password' => [
                'required'
            ]
        ]);

        $errors = $request->errors();

        if (!$valid) {
            return \html('login', ['errors' => $errors]);
        }

        $user = $this->userRepository->findByUsername($request->username);

        if (!$this->userRepository->verifyPassword($request->password, $user['password'])) {
            $errors = ['password' => [\text('login.wrong_password')]];
            return \html('login', ['errors' => $errors]);
        }

        $this->auth->login($user['username'], $request->remember ?? false);

        return \redirect('/');
    }

    /**
     * Logout the user from the machine.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function logout(RequestInterface $request)
    {
        $this->auth->logout();
        return \redirect('/');
    }
}