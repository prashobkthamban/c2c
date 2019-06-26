<?php

namespace App\Services;

use App\Services\IUserService;
use App\Models\Users;
use Carbon;
use DB;
use Auth;
use Config;

class UserService implements IUserService
{
    public function getAllUsers()
    {
        return $users = Users::all();
    }
}
