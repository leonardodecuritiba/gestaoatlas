<?php

namespace App\Models;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pattern extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'idunit',
        'description',
        'brand',
        'measure',
        'cost',
        'cost_certification',
        'certification',
        'class',
        'expiration',
    ];

    // ************************** RELASHIONSHIP **********************************


    public function setExpirationAttribute($value)
    {
        return $this->attributes['expiration'] = DataHelper::setDate($value);
    }

    public function getExpirationAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    public function getCostAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getCostCertificationAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function getCostCertification()
    {
        return DataHelper::getFloat2RealMoeda($this->getAttribute('cost_certification'));
    }

    public function getMeasure()
    {
        return $this->getAttribute('measure') . ' ' . $this->unity->codigo;
    }

    public function unity()
    {
        return $this->hasOne('App\Unidade', 'idunidade', 'idunit');
    }

}
