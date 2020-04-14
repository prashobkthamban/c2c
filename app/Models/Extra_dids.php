<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//did Model

class Extra_dids extends Model
{
    protected $table = 'extra_dids';
    protected $primaryKey = 'id';
    protected $fillable = ['did_id', 'did_no', 'did_name'];

    public function get_prigateway() {
    	return DB::table('prigateway')->where('delete_status', '0')->pluck('Gprovider', 'id');
    }

    public function get_dids($id = null) {
    	if(!empty($id)) {
    		return DB::table('extra_dids')->where('groupid', $id)->pluck('did_no', 'id');
    	} else {
    		return DB::table('extra_dids')->where('groupid', 0)->pluck('did_no', 'id');
    	}
    	
    }
}
