<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cst extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'numeracao',
    ];

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function produto_tributacaos()
    {
        return $this->hasMany('App\PecaTributacao', 'idcst');
    }

}

