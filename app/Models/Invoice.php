<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Invoice extends Model
{
    protected $table = 'invoice';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'operator_id', 'billing_address', 'customer_id', 'date','discount','total_amount','grand_total','invoice_number','total_tax_amount','inserted_date','payment_status'];

    public static function getReport( ){

        $data = Invoice::paginate(10);
        return $data;
    }

}
