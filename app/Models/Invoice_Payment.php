<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Invoice_Payment extends Model
{
    protected $table = 'invoice_payments';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'invoice_id', 'payment_amount', 'transaction_id','payment_date','payment_mode','note'];

}
