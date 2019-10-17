<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//did Model

class Dids extends Model
{
    protected $table = 'dids';
    protected $primaryKey = 'id';
    protected $fillable = ['did', 'rdins', 'rdnid', 'assignedto', 'gatewayid', 'did_extra1', 'did_extra2', 'did_extra3', 'outgoing_gatewayid', 'outgoing_callerid', 'set_did_no', 'c2cpri', 'c2ccallerid', 'dnid_name'];

    public function get_prigateway() {
    	return DB::table('prigateway')->where('delete_status', '0')->pluck('Gprovider', 'id');
    }

    public function get_did($id = null) {
    	if(!empty($id)) {
    		return DB::table('dids')->where('assignedto', $id)->pluck('did', 'id');
    	} else {
    		return DB::table('dids')->where('assignedto', 0)->pluck('did', 'id');
    	}
    	
    }
}
