<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PessoaFisica extends Model
{
    protected $table = 'pfisicas';
    protected $primaryKey = 'idpfisica';
    public $timestamps = true;
    protected $fillable = [
        'cpf',
    ];


    public function getCpfAttribute($value)
    {
        return Controller::mask($value, '###.###.###-##');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }
}
