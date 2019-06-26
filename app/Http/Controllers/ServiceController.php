<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IUserService;
class ServiceController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function test()
    {
        $users = $this->userService->getAllUsers();
        print_r($users);die;
    }
}
