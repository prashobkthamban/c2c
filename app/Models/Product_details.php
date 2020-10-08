<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Product_details extends Model
{
    protected $table = 'proposal_details';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'proposal_id', 'product_id', 'qty','rate','tax','amount','discount_rate','discount_amount'];

}
