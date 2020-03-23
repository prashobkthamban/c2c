<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class ToDoTask extends Model
{
    protected $table = 'todotask';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'title', 'date','inserted_date'];

}
