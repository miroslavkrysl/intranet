<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;

/**
 * Controller to handle dashboard.
 */
class DashboardController
{
    public function showDashboard(RequestInterface $request)
    {
        return \json();
    }
}