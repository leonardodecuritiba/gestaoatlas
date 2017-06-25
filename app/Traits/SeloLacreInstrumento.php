<?php

namespace App\Traits;

use App\Helpers\DataHelper;
use Carbon\Carbon;

trait SeloLacreInstrumento
{

    public function tirar($id, $key)
    {
        $Data = self::where($key, $id)->first();
        return $Data->update(['retirado_em' => Carbon::now()->toDateTimeString()]);
    }

    public function getAfixadoEm()
    {
        return ($this->attributes['afixado_em'] != NULL) ? DataHelper::getPrettyDateTime($this->attributes['afixado_em']) : '-';
    }

    public function getRetiradoEm()
    {
        return ($this->attributes['retirado_em'] != NULL) ? DataHelper::getPrettyDateTime($this->attributes['retirado_em']) : '-';
    }
}