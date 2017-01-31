<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NaturezaOperacao extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'numero',
        'descricao',
    ];
    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function produto_tributacaos()
    {
        return $this->hasMany('App\PecaTributacao', 'idcfop');
    }
}


