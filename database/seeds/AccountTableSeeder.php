<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('account')->get();
        foreach( $users as $row ){
            $password = Hash::make( $row->password );
            DB::table('account')
                ->where('id', $row->id)
                ->update(['password' => $password]);
        }
    }
}
