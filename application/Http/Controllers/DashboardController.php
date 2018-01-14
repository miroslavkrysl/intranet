<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

/**
 * Controller to handle dashboard.
 */
class DashboardController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * DashboardController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showDashboard(RequestInterface $request)
    {
        return \html('dashboard');
    }
}