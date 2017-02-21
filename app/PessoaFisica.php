<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PessoaFisica extends Model
{
    public $timestamps = true;
    protected $table = 'pfisicas';
    protected $primaryKey = 'idpfisica';
    protected $fillable = [
        'cpf',
    ];


    public function getCpfAttribute($value)
    {
        return Controller::mask($value, '###.###.###-##');
    }

    public function getCpf()
    {
        return $this->attributes['cpf'];
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }
}
