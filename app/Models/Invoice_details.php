<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Invoice_details extends Model
{
    protected $table = 'invoice_details';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'invoice_id', 'product_id', 'qty','rate','tax','amount','discount_rate','discount_amount'];

}
