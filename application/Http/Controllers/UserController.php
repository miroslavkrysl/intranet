<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Services\Auth\Auth;

class UserController
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
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Auth $auth
     * @internal param ResponseFactoryInterface $responseFactory
     * @internal param ResponseInterface $response
     */
    public function __construct(UserRepositoryInterface $userRepository, Auth $auth)
    {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
    }

    /**
     * Show the page with user profile.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function index(RequestInterface $request)
    {
        $rules = [
            'username' => [
                'exists' => [
                    'table' => 'user',
                    'column' => 'username'
                ]
            ]
        ];

        if (!$request->validate($rules)) {
            return \response()->error(404, $request->errors()['username']['exists']);
        }

        $user = $this->userRepository->findByUsername($request->username);
        unset($user->password);
        return json($user);
    }

    /**
     * Show the page with the the user settings.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function showSettings(RequestInterface $request)
    {
        return \response()->html('user_settings');
    }
}