<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Resellergroup extends Model
{
    protected $table = 'resellergroup';
    protected $fillable = [
        'resellername', 'cdr_apikey', 'associated_groups'
        ];
    public $timestamps = false;
    public function accounts()
    {
        return $this->hasOne('\App\Models\Account', 'resellerid');
    }
}