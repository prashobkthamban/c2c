<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Proposal extends Model
{
    protected $table = 'proposal';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'operator_id', 'subject', 'cutomer_id', 'date','discount','total_amount','grand_total','total_tax_amount', 'cdrreport_lead_id','inserted_date','group_id'];

    public static function getReport( ){

        $data = Proposal::paginate(10);
        return $data;
    }

}
