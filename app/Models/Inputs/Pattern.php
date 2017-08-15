<?php

namespace App\Models\Inputs;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pattern extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'idunit',
        'idbrand',
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

    public function getMeasureAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
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
        return DataHelper::getFloat2RealMoeda($this->attributes['cost_certification']);
    }

    public function getCost()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['cost']);
    }

    public function getMeasure()
    {
        return $this->getAttribute('measure') . ' ' . $this->unity->codigo;
    }

    public function getBrandText()
    {
        return $this->brand->description;
    }

    public function unity()
    {
        return $this->hasOne('App\Unidade', 'idunidade', 'idunit');
    }

    public function brand()
    {
        return $this->hasOne('App\Models\Commons\Brand', 'id', 'idbrand');
    }

}
