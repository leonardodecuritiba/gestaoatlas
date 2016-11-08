<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    protected $table = 'contatos';
    protected $primaryKey = 'idcontato';
    public $timestamps = true;
    protected $fillable = [
        'telefone',
        'celular',
        'skype',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento'
    ];

    // ******************** RELASHIONSHIP ******************************
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcontato');
    }
    public function getTelefoneAttribute($value)
    {
        return Controller::mask($value, '(##) ####-####');
    }
    public function getCelularAttribute($value)
    {
        return Controller::mask($value, '(##) #####-####');
    }
    public function getCepAttribute($value)
    {
        return Controller::mask($value, '#####-###');
    }
    public function getEnderecoCompleto()
    {
        $endereco = '';
        $endereco .= ($this->logradouro != '')?$this->logradouro:'';
        $endereco .= ($this->numero != '')?', '.$this->numero:', s/n';
        $endereco .= ($this->bairro != '')?' - '.$this->bairro:' - sem bairro';
        $endereco .= ($this->cidade != '')?' - '.$this->cidade:' - sem cidade';
        $endereco .= ($this->estado != '')?'/'.$this->estado:'';

        return $endereco;
    }
}
