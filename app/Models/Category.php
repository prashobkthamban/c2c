<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'parent_id', 'child_level', 'name'];

    public static function getReport( ){

        $data = Category::paginate(10);
        return $data;
    }

}
