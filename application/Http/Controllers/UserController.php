<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\RoleRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Services\Auth\Auth;
use Intranet\Services\Mail\Mail;

class UserController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var Mail
     */
    private $mail;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     * @param AuthInterface $auth
     * @param Mail $mail
     */
    public function __construct(UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository, AuthInterface $auth, Mail $mail)
    {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->roleRepository = $roleRepository;
        $this->mail = $mail;
    }

    /**
     * Create a new user.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'user_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'username' => [
                'required',
                'regex' => [
                    'pattern' => "/[A-Za-z0-9\_\-\.]{5,32}/"
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
            'password' => null,
            'role_name' => $request->role_name,
            'password_reset_token' => null,
            'password_reset_expire_at' => null
        ];

        $token = \random_string(128);
        $user['password_reset_token'] = $this->userRepository->hashPassword($token);

        $this->userRepository->save($user);

        $url = \sprintf("%s/user/change-password?username=%s&token=%s", \config('app.url'), $user['username'], $token);
        $subject = \text('app.user.create.mail.subject');
        $message = \text('app.user.create.mail.message', ['url' => $url, 'username' => $user['username']]);

        $this->mail->send($user['email'], $subject, $message);

        $user = $this->userRepository->toPublic($user);

        return \json(['user' => $user, 'message' => \text('app.user.create.success')]);
    }

    /**
     * Update existing user.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function update(RequestInterface $request)
    {
        $valid = $request->validate([
            'username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
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
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $user['name'] = \is_null($request->name) ? $user['name'] : $request->name;
        $user['email'] = $request->email ?? $user['email'];

        if ($canManage and $request->role_name) {
            if ($user['username'] == $loggedUsername and $user['role_name'] == 'admin' and $request->role_name != 'admin') {
                return \jsonError(403, ['role_name' => [\text('app.user.update.admin_role_denied')]]);
            }

            $user['role_name'] = $request->role_name ?? $user['role_name'];
        }

        $user = $this->userRepository->save($user);
        $user = $this->userRepository->toPublic($user);

        return \json(['user' => $user, 'message' => \text('app.user.update.success')]);
    }

    /**
     * Delete the user.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function delete(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'user_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
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

        if ($request->username == $this->auth->user()['username']) {
            return \jsonError(403, ['delete' => [\text('app.user.delete.delete_self_denied')]]);
        }

        $this->userRepository->delete($request->username);

        return \json(['message' => \text('app.user.delete.success', ['username' => $request->username])]);
    }

    /**
     * Show page with listed users.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function list(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], "user_manage")) {
            return \error(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $users = $this->userRepository->findAll(['name']);
        $roles = $this->roleRepository->findAll();

        return \html('users', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * Show table with listed users.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function usersTable(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], "user_manage")) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $users = $this->userRepository->findAll(['name']);
        $roles = $this->roleRepository->findAll();

        return \html('components.users_table', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface|string
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
     * @return ResponseInterface|string
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
            'password' => [
                'required',
                'min_length' => [
                    'min' => 6
                ]
            ],
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $user = $this->userRepository->findByUsername($request->username);

        if ($request->_password) {
            $hash = $user['password'];
            if (!$this->userRepository->verifyPassword($request->_password, $hash)) {
                return \jsonError(403, ['password' => [\text('app.user.change_password.invalid_password')]]);
            }
        }
        else if ($request->token) {
            $hash = $user['password_reset_token'];
            $now = \strtotime('now');
            $expire = \strtotime($user['password_reset_expire_at']);

            if (!$hash or ($expire and $now > $expire) or !$this->userRepository->verifyPassword($request->token, $hash)){
                return \jsonError(403, ['token' => [\text('app.user.change_password.invalid_token')]]);
            }
        }
        else {
            return \jsonError(403, ['auth' => [\text('app.user.change_password.no_auth')]]);
        }

        $user['password_reset_token'] = null;
        $user['password'] = $this->userRepository->hashPassword($request->password);

        $this->userRepository->save($user);

        return \json(['message' => \text('app.user.change_password.success')]);
    }

    /**
     * Send change password email
     * @param RequestInterface $request
     */
    public function sendChangePasswordEmail(RequestInterface $request)
    {
        $valid = $request->validate([
            'email' => [
                'required',
                'email'
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $user = $this->userRepository->findByEmail($request->email);

        if ($user) {
            if (!$user['password_reset_expire_at'] or \strtotime($user['password_reset_expire_at']) < \strtotime('now')) {
                $token = \random_string(128);
                $user['password_reset_token'] = $this->userRepository->hashPassword($token);
                $user['password_reset_expire_at'] =  date("Y-m-d H:i:s", \strtotime('+3 hour'));

                $this->userRepository->save($user);

                $url = \sprintf("%s/user/change-password?username=%s&token=%s", \config('app.url'), $user['username'], $token);
                $subject = \text('app.user.change_password.mail.subject');
                $message = \text('app.user.change_password.mail.message', ['url' => $url]);

                $this->mail->send($user['email'], $subject, $message);
            }
        }

        return \json(['message' => \text('app.user.change_password.mail.sent')]);
    }
}