<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

date_default_timezone_set('Asia/Kolkata');

class CommandController extends Controller
{
    public function __construct()
    {
    }

    public function migrateData()
    {
        echo "starting migration...";
        echo "<br>";

        set_time_limit(0);
        $operators = DB::table('operatoraccount')
                    ->where('operatortype', 'web')
                    ->get();
        if (!empty($operators)) {
            foreach ($operators as $operator) {
                $account = [
                    'groupid' => $operator->groupid,
                    'resellerid' => NULL,
                    'username' => $operator->login_username,
                    'password' => Hash::make($operator->password),
                    'status' => 'Active',
                    'usertype' => 'operator',
                    'adddate' => $operator->adddate,
                    'phone_number' => $operator->phonenumber,
                    'email' => '',
                    'deviceid' => $operator->deviceid,
                    'remember_token' => NULL,
                    'operator_id' => $operator->id,
                    'user_pwd' => $operator->password,
                    'is_command_migrated' => 1
                ];

                DB::table('account')->insert($account);
                echo "migrated operator " . $operator->opername . " (" . $operator->login_username . ")";
                echo "<br>";
            }
        }
        echo "migrated " . count($operators) . " operators...";
        echo "<br>";
        echo "migration completed...";
    }

    public function hashPassword()
    {
        echo "hashing begins...";
        echo "<br>";

        set_time_limit(0);
        $accounts = DB::table('account')
                    ->whereRaw('LENGTH(password) < 50')
                    ->get();
        if (!empty($accounts)) {
            foreach ($accounts as $account) {
                $data = [
                    'password' => Hash::make($account->password),
                    'user_pwd' => $account->password,
                    'is_password_command_hashed' => 1
                ];

                DB::table('account')->where('id', $account->id)->update($data);
                echo "hashed operator password (" . $account->password . ") for " . $account->username;
                echo "<br>";
            }
        }
        echo "hashed password for " . count($accounts) . " operators...";
        echo "<br>";
        echo "hashing completed...";
    }

    /**
     * upadate associated_groups column of reseller group table with date from group admin
     */
    public function updateResellerGroup()
    {
        echo "starting update...";
        echo "<br>";

        set_time_limit(0);
        $resellergroups = DB::table('resellergroup')
                    ->get();
        if (!empty($resellergroups)) {
            foreach ($resellergroups as $resellergroup) {
                $groupAdminIds =  DB::table('accountgroup')->where('resellerid', $resellergroup->id)->pluck('id');
                $data = [
                    'associated_groups' => json_encode($groupAdminIds)
                ];

                DB::table('resellergroup')->where('id', $resellergroup->id)->update($data);
                echo "updated entry for the reseller " . $resellergroup->resellername . " with data: " . $data['associated_groups'];
                echo "<br>";
            }
        }
        echo "updated entries for " . count($resellergroups) . " resellers...";
        echo "<br>";
        echo "update completed...";
    }
}
