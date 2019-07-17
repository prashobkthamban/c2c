<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Account extends Authenticatable
{
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password'];
}
