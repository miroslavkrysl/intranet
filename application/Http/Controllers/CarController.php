<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\CarRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

class CarController
{
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
     * CarController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param CarRepositoryInterface $carRepository
     * @param AuthInterface $auth
     */
    public function __construct(UserRepositoryInterface $userRepository, CarRepositoryInterface $carRepository, AuthInterface $auth)
    {
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
        $this->auth = $auth;
    }

    /**
     * Create a new car.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'car_manage')) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $valid = $request->validate([
            'name' => [
                'required',
                'regex' => [
                    'pattern' => "/[\w\.\-]{3,64}/"
                ],
                'unique' => [
                    'table' => \config('database.tables.car'),
                    'column' => 'name'
                ]
            ],
            'description' => [
                'max_length' => [
                    'max' => 255
                ]
            ],
            'manufacturer' => [
                'required',
                'max_length' => [
                    'max' => 255,
                ],
            ],
            'model' => [
                'required',
                'max_length' => [
                    'max' => 255
                ],
            ],
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $car = [
            'name' => $request->name,
            'description' => $request->description,
            'manufacturer' => $request->manufacturer,
            'model' => $request->model,
            'role_name' => $request->role
        ];

        $car = $this->carRepository->save($car);

        return \json(['car' => $car, 'message' => \text('app.car.create.success', ['name' => $car['name']])]);
    }

    /**
     * Update existing user.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function update(RequestInterface $request)
    {
        if ($this->userRepository->hasPermission($this->auth->user()['username'], 'car_manage')) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $valid = $request->validate([
            'name' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.car'),
                    'column' => 'name'
                ]
            ],
            'description' => [
                'max_length' => [
                    'max' => 255
                ]
            ],
            'manufacturer' => [
                'max_length' => [
                    'max' => 255,
                ],
            ],
            'model' => [
                'required',
                'max_length' => [
                    'max' => 255
                ],
            ],
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $car = $this->carRepository->findByName($request->name);

        $car['description'] = \is_null($request->description) ? $car['description'] : $request->description;
        $car['manufacturer'] = \is_null($request->manufacturer) ? $car['manufacturer'] : $request->manufacturer;
        $car['model'] = \is_null($request->model) ? $car['model'] : $request->model;

        $car = $this->userRepository->save($car);

        return \json(['car' => $car, 'message' => \text('app.car.update.success')]);
    }

    /**
     * Delete the car.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function delete(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'car_manage')) {
            return \jsonError(403, \text('base.permission_denied'));
        }

        $valid = $request->validate([
            'name' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.car'),
                    'column' => 'name'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $this->carRepository->delete($request->name);

        return \json();
    }

    /**
     * Show page with listed cars.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function list(RequestInterface $request)
    {
        $cars = $this->carRepository->findAll(['name']);

        return \html('cars', ['cars' => $cars]);
    }

    /**
     * Show table with listed cars.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function carsTable(RequestInterface $request)
    {
        $cars = $this->carRepository->findAll(['name']);

        return \html('components.cars_table', ['cars' => $cars]);
    }
}