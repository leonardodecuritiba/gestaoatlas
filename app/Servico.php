<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use SoftDeletes;
    protected $table = 'servicos';
    protected $primaryKey = 'idservico';
    public $timestamps = true;
    protected $fillable = [
        'nome',
        'descricao',
        'valor',
    ];

    public function getCreatedAtAttribute($value)
    {
        return ($value==NULL)?$value:Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y - H:i');
    }
    public function getValorAttribute($value)
    {
        return number_format($value,2,',','.');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    // ************************** HASMANY **********************************
    public function servico_prestados()
    {
        return $this->hasMany('App\ServicoPrestado', 'idservico');
    }
}
