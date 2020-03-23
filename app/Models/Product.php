<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'name', 'category_id', 'image','unit_of_measurement','landing_cost','selling_cost','description'];

    public static function getReport( ){

        $data = Product::paginate(10);
        return $data;
    }

}
