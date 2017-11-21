<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseFactoryInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

class UserController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param ResponseFactoryInterface $responseFactory
     * @param UserRepositoryInterface $userRepository
     * @internal param ResponseInterface $response
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
                'required',
                'exists' => [
                    'table' => 'user',
                    'column' => 'username'
                ]
            ]
        ];

        if (!$request->validate($rules)) {
            return \response()->error(404, $request->errors()['username']);
        }

        return \response($this->userRepository->findByUsername($request->username));
    }
}