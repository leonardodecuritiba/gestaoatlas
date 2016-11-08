<?php

namespace App\Helpers;
use Carbon\Carbon;

class DataHelper
{
    // ******************** FUNCTIONS ******************************
    static public function getReal2Float($value)
    {
        return floatval(str_replace(',','.',str_replace('.','',$value)));
    }
    static public function getFloat2Real($value)
    {
        return number_format($value,2,',','.');
    }
    static public function getPrettyDateTime($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('H:i - d/m/Y'):$value;
    }
    static public function getPrettyDate($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):$value;
    }

}
