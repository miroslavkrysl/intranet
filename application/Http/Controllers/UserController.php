<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Intranet\Contracts\Auth\AuthInterface;
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
     * @param AuthInterface $auth
     * @internal param ResponseFactoryInterface $responseFactory
     * @internal param ResponseInterface $response
     */
    public function __construct(UserRepositoryInterface $userRepository, AuthInterface $auth)
    {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
    }

    /**
     * Create a new user.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'user_manage')) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $valid = $request->validate([
            'username' => [
                'required',
                'regex' => [
                    'pattern' => "/[\w\.\-]{5,32}/"
                ],
                'unique' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'name' => [
                'required',
                'max_length' => [
                    'max' => 255
                ]
            ],
            'email' => [
                'required',
                'max_length' => [
                    'max' => 255,
                ],
                'email'
            ],
            'password' => [
                'required',
                'min_length' => [
                    'min' => 6
                ],
            ],
            'role_name' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.role'),
                    'column' => 'name'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $user = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $this->userRepository->hashPassword($request->password),
            'role_name' => $request->role,
            'password_reset_token' => null
        ];

        $user = $this->userRepository->save($user);
        unset($user['password']);

        return \json($user);
    }

    /**
     * Update existing user.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface
     */
    public function update(RequestInterface $request)
    {
        $valid = $request->validate([
            'username' => [
                'required',
                'regex' => [
                    'pattern' => "/[\w\.\-]{5,32}/"
                ],
            ],
            'name' => [
                'max_length' => [
                    'max' => 255
                ]
            ],
            'email' => [
                'max_length' => [
                    'max' => 255,
                ],
                'email'
            ],
            'password' => [
                'min_length' => [
                    'min' => 6
                ],
            ],
            'role_name' => [
                'exists' => [
                    'table' => \config('database.tables.role'),
                    'column' => 'name'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $user = $this->userRepository->findByUsername($request->username);
        $loggedUsername = $this->auth->user()['username'];
        $canManage = $this->userRepository->hasPermission($loggedUsername, 'user_manage');

        if ($user['username'] != $loggedUsername and !$canManage) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $user['name'] = \is_null($request->name) ? $user['name'] : $request->name;
        $user['email'] = $request->email ?? $user['email'];
        $user['password'] = $request->password ? $this->userRepository->hashPassword($request->password) : $user['password'];

        if ($canManage) {
            $user['role_name'] = $request->role_name ?? $user['role_name'];
        }

        $user = $this->userRepository->save($user);
        $user = $this->userRepository->toPublic($user);

        return \json(['user' => $user, 'message' => \text('app.user.update.success')]);
    }

    /**
     * Delete the user.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface
     */
    public function delete(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'user_manage')) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $valid = $request->validate([
            'username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $this->userRepository->delete($request->username);

        return \json();
    }

    /**
     * Show page with listed users.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface
     */
    public function list(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], "user_manage")) {
            return \error(403, \text('base.permission_denied'));
        }

        $users = $this->userRepository->findAll('name', false);

        return \html('users', ['users' => $users]);
    }

    /**
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface|string
     */
    public function showSettings(RequestInterface $request)
    {
        return \html('settings');
    }

    /**
     * Show change password form.
     * @param RequestInterface $request
     */
    public function showChangePasswordForm(RequestInterface $request)
    {
        $valid = $request->validate([
            'username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'token' => [
                'required'
            ]
        ]);

        if (!$valid) {
            return \error(403, \text('app.invalid_link'));
        }

        $hash = $this->userRepository->findByUsername($request->username)['password_reset_token'];

        if (!$hash or !$this->userRepository->verifyPassword($request->token, $hash)) {
            return \error(403, \text('app.invalid_link'));
        }

        return \html('change_password', ['username' => $request->username, 'token' => $request->token]);
    }

    /**
     * Change user password.
     * @param RequestInterface $request
     * @return \Core\Contracts\Http\ResponseInterface|string
     */
    public function changePassword(RequestInterface $request)
    {
        $valid = $request->validate([
            'username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'token' => [
                'required'
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \error(422, $errors);
        }

        return \html('change_password', ['username' => $request->username, 'token' => $request->token]);
    }

    /**
     * Send change password email
     * @param RequestInterface $request
     */
    public function resetPassword(RequestInterface $request)
    {

    }
}