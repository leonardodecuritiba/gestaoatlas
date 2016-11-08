<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecaKit extends Model
{
    use SoftDeletes;
    protected $table = 'peca_kit';
    protected $primaryKey = 'idpeca_kit';
    public $timestamps = true;
    protected $fillable = [
        'idkit',
        'idpeca',
        'quantidade',
        'valor_unidade',
        'valor_total',
        'descricao_adicional',
    ];





//    public function valor_total()
//    {
//        $value = $this->attributes['quantidade'] * $this->attributes['valor_unidade'];
//        return number_format($value,2,',','.');
//    }

    public function getValorUnidadeAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    public function getValorTotalAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idpeca');
    }
    public function kit()
    {
        return $this->belongsTo('App\Kit', 'idkit');
    }
    // ************************** HASMANY **********************************

}
