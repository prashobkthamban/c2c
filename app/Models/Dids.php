<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Dids extends Model
{
    protected $table = 'dids';
    protected $primaryKey = 'id';
    protected $fillable = ['did', 'rdins', 'rdnid', 'assignedto', 'gatewayid', 'did_extra1', 'did_extra2', 'did_extra3', 'outgoing_gatewayid', 'outgoing_callerid', 'set_did_no', 'c2cpri', 'c2ccallerid', 'dnid_name'];
}
