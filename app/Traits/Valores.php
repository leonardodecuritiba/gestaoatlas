<?php

namespace App\Traits;

use App\Helpers\DataHelper;

trait Valores
{

    public function valor_bruto_real()
    {
        return DataHelper::getFloat2RealMoeda($this->valor_bruto());
    }

    public function valor_bruto()
    {
        return ($this->attributes['valor'] * $this->attributes['quantidade']);
    }

    public function desconto_real()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['desconto']);
    }

    public function valor_total_real()
    {
        return DataHelper::getFloat2RealMoeda($this->valor_total());
    }

    public function valor_total()
    {
        return ($this->attributes['valor'] * $this->attributes['quantidade']) - $this->attributes['desconto'];
    }

    public function getValorAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function valor_float()
    {
        return $this->getValorFloatAttribute();
    }

    public function getValorFloatAttribute()
    {
        return $this->attributes['valor'];
    }
}