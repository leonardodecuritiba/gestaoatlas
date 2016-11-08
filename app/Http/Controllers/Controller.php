<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    public function importar(Request $request)
    {
        $start = microtime(true);
        $file = storage_path('uploads').'\import\import.xlsx';
        // Loop through all rows
        set_time_limit(3600);
        $reader = Excel::load($file, function($reader) {})->ignoreEmpty();
        $reader->each(function($sheets) {
            $table = $sheets->getTitle();
            foreach ($sheets->toArray() as $sheet){
                DB::table($table)->insert($sheet);
            }
        });
        $time = round((microtime(true) - $start), 3);
        echo "*** Importacao realizada com sucesso em ".$time."s ***";
    }
    static public function mask($val, $mask)
    {
        if($val!=NULL || $val!=""){
            $maskared = '';
            $k = 0;
            for($i = 0; $i<=strlen($mask)-1; $i++)
            {
                if($mask[$i] == '#')
                {
                    if(isset($val[$k])) $maskared .= $val[$k++];
                }
                else
                {
                    if(isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
        } else {
            $maskared = NULL;
        }
        return $maskared;
    }

}
