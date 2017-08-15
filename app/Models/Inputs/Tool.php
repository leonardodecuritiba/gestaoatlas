<?php

namespace App\Models\Inputs;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'idcategory',
        'idbrand',
        'idunit',
        'description',
        'cost',
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

    public function getCost()
    {
        return DataHelper::getFloat2RealMoeda($this->attributes['cost']);
    }

    public function getBrandText()
    {
        return $this->brand->description;
    }

    public function getCategoryText()
    {
        return $this->category->description;
    }

    public function getUnityText()
    {
        return $this->unity->codigo;
    }


    public function brand()
    {
        return $this->belongsTo('App\Models\Commons\Brand', 'idbrand');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Inputs\Tool\ToolCategory', 'idcategory');
    }

    public function unity()
    {
        return $this->belongsTo('App\Unidade', 'idunit', 'idunidade');
    }

}
