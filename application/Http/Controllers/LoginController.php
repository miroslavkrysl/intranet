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
    public function index(RequestInterface $request)
    {
        return \response(\view('login'));
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
            return \response(\view('login', ['errors' => $errors]));
        }

        \var_dump($request->username);
        $user = $this->userRepository->findByUsername($request->username);
        \var_dump($user);

        if (!$this->userRepository->verifyPassword($request->password, $user['password'])) {
            $errors = ['password' => [\text('login.wrong_password')]];
            return \response(\view('login', ['errors' => $errors]));
        }

        $this->auth->login($user['id']);

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