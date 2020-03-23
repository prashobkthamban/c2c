<?php

namespace App\Imports;
use App\SpeedTest;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\ToModel;

class SpeedTestsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SpeedTest([
            'server_id'     => $row[0],
            'sponsor'       => $row[1], 
            'server_name'   => $row[2],
            'timestamp'     => $row[3],
            'distance'      => $row[4], 
            'ping'          => $row[5],
            'download'      => $row[6],
            'upload'        => $row[7],
            'share'         => $row[8],
            'ip_address'    => $row[9]
        ]);
    }
}