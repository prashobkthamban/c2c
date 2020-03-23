<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_Products extends Model
{
    protected $table = 'lead_products';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'product_id', 'quantity','pro_amount','subtotal_amount'];

}
