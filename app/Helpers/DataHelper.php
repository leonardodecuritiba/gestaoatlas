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

    static public function getPercent2Float($value)
    {
        return floatval(str_replace(',', '.', $value));
    }

    static public function getFloat2Percent($value)
    {
        return number_format($value, 2, ',', '.');
    }
    static public function getPrettyDateTime($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('H:i - d/m/Y'):$value;
    }
    static public function getPrettyDate($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):$value;
    }

    static public function setDate($value)
    {
        return (($value != NULL) && ($value != '')) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : NULL;
    }

    static public function getOnlyNumbers($value)
    {
        return ($value != NULL) ? preg_replace("/[^0-9]/", "", $value) : $value;
    }

    static public function getShortName($value)
    {
        $value = explode(' ', $value);
        return (count($value) > 1) ? ($value[0] . " " . end($value)) : $value[0];
    }

    static public function mask($val, $mask)
    {
        if ($val != NULL || $val != "") {
            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
        } else {
            $maskared = NULL;
        }
        return $maskared;
    }
}
