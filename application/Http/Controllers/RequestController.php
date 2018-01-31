<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\CarRepositoryInterface;
use Intranet\Contracts\Repositories\RequestRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

class RequestController
{
    /**
     * @var RequestRepositoryInterface
     */
    private $requestRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;
    /**
     * @var AuthInterface
     */
    private $auth;

    /**
     * RequestController constructor.
     * @param RequestRepositoryInterface $requestRepository
     * @param UserRepositoryInterface $userRepository
     * @param CarRepositoryInterface $carRepository
     * @param AuthInterface $auth
     */
    public function __construct(RequestRepositoryInterface $requestRepository,
                                UserRepositoryInterface $userRepository,
                                CarRepositoryInterface $carRepository,
                                AuthInterface $auth)
    {
        $this->requestRepository = $requestRepository;
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
        $this->auth = $auth;
    }

    /**
     * Create a new request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        $user = $this->auth->user();

        if (!($this->userRepository->hasPermission($user['username'], 'req_manage') or
                $this->userRepository->hasPermission($user['username'], 'req_own'))) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'user_username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'car_name' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.car'),
                    'column' => 'name'
                ]
            ],
            'reserved_from' => [
                'required',
                'date'
            ],
            'reserved_to' => [
                'required',
                'date'
            ],
            'driver_username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'purpose' => [
                'required',
                'max_length' => [
                    'max' => 255
                ],
            ],
            'destination' => [
                'required',
                'max_length' => [
                    'max' => 255
                ],
            ],
            'passengers' => [
                'max_length' => [
                    'max' => 1024
                ],
            ],
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        if (!$this->userRepository->canDrive($request->driver_username, $request->car_name)) {
            return \jsonError(422, [
                    'driver_username' => [
                        'can_drive' => \text('user_can_drive.cant_drive', [
                            'username' => $request->driver_username,
                            'car_name' => $request->car_name
                        ])
                    ]
            ]);
        }

        $rf = \strtotime($request->reserved_from);
        $rt = \strtotime($request->reserved_to);
        $now = \strtotime('now');

        if ($now >= $rf) {
            return \jsonError(422, ['reserved_from' => ['after' => \text('app.request.reserved_from_before_now')]]);
        }

        if ($rf >= $rt) {
            return \jsonError(422, ['reserved_to' => ['after' => \text('app.request.reserved_to_before_from')]]);
        }

        if ($request->user_username != $user['username'] and !$this->userRepository->hasPermission($user['username'], 'req_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $req = $this->requestRepository->create();

        $req['user_username'] = $request->user_username;
        $req['car_name'] = $request->car_name;
        $req['reserved_from'] = date("Y-m-d H:i:s", $rf);
        $req['reserved_to'] = date("Y-m-d H:i:s", $rf);
        $req['driver_username'] = $request->driver_username;
        $req['purpose'] = $request->purpose;
        $req['destination'] = $request->destination;
        $req['passengers'] = $request->passengers;

        $req = $this->requestRepository->save($req);

        return \json(['request' => $req, 'message' => \text('app.request.create.success')]);
    }

    /**
     * Update existing request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function update(RequestInterface $request)
    {
        $user = $this->auth->user();

        if (!$this->userRepository->hasPermission($user['username'], 'req_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'id' => [
                'required',
                'digits',
                'exists' => [
                    'table' => \config('database.tables.request'),
                    'column' => 'id'
                ]
            ],
            'user_username' => [
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'car_name' => [
                'exists' => [
                    'table' => \config('database.tables.car'),
                    'column' => 'name'
                ]
            ],
            'reserved_from' => [
                'date'
            ],
            'reserved_to' => [
                'date'
            ],
            'driver_username' => [
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ],
            'purpose' => [
                'max_length' => [
                    'max' => 255
                ],
            ],
            'destination' => [
                'max_length' => [
                    'max' => 255
                ],
            ],
            'passengers' => [
                'max_length' => [
                    'max' => 1024
                ],
            ],
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $req = $this->requestRepository->findById($request->id);
        $now = \strtotime('now');

        $req['user_username'] = \is_null($request->user_username) ? $req['user_username'] : $request->user_username;
        $req['car_name'] = \is_null($request->car_name) ? $req['car_name'] : $request->car_name;
        $req['reserved_from'] = \is_null($request->reserved_from) ? $req['reserved_from'] : date("Y-m-d H:i:s", \strtotime($request->reserved_from));
        $req['reserved_to'] = \is_null($request->reserved_to) ? $req['reserved_to'] : date("Y-m-d H:i:s", \strtotime($request->reserved_to));
        $req['driver_username'] = \is_null($request->driver_username) ? $req['driver_username'] : $request->driver_username;
        $req['purpose'] = \is_null($request->purpose) ? $req['purpose'] : $request->purpose;
        $req['destination'] = \is_null($request->destination) ? $req['destination'] : $request->destination;
        $req['passengers'] = \is_null($request->passengers) ? $req['passengers'] : $request->passengers;

        if (!$this->userRepository->canDrive($req['driver_username'], $req['car_name'])) {
            return \jsonError(422, [
                'driver_username' => [
                    'can_drive' => \text('user_can_drive.cant_drive', [
                        'username' => $req['driver_username'],
                        'car_name' => $req['car_name']
                    ])
                ]
            ]);
        }

        if ($req['reserved_from'] >= $req['reserved_to']) {
            return \jsonError(422, ['reserved_to' => ['after' => \text('app.request.reserved_to_before_from')]]);
        }

        $req = $this->requestRepository->save($req);

        return \json(['request' => $req, 'message' => \text('app.request.update.success')]);
    }

    /**
     * Delete the car.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function delete(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'req_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'id' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.request'),
                    'column' => 'id'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $this->requestRepository->delete($request->id);

        return \json(['message' => \text('app.request.delete.success')]);
    }

    /**
     * Show page with listed requests.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function list(RequestInterface $request)
    {
        $from = \strtotime($request->after_date) ?? \strtotime('-1 week');

        $reqs = $this->requestRepository->findReservedFromAfter($from, ['reserved_from']);
        $users = $this->userRepository->findAll(['username']);
        $cars = $this->carRepository->findAll(['name']);

        return \html('requests', ['cars' => $cars, 'users' => $users, 'requests' => $reqs]);
    }

    /**
     * Show table with listed requests.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function requestsTable(RequestInterface $request)
    {
        $from = \strtotime($request->after_date) ?? \strtotime('-1 week');

        $reqs = $this->requestRepository->findReservedFromAfter($from, ['reserved_from']);

        return \html('components.requests_table', ['requests' => $reqs]);
    }

    /**
     * Confirm the request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function confirm(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'req_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'id' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.request'),
                    'column' => 'id'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $req = $this->requestRepository->findById($request->id);
        $req['confirmed_by_username'] = $this->auth->user()['username'];
        $this->requestRepository->save($req);

        return \json(['message' => \text('app.request.confirm.success')]);
    }
}