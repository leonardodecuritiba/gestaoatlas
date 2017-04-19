<?php

namespace App;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    public $timestamps = true;
    protected $table = 'contatos';
    protected $primaryKey = 'idcontato';
    protected $fillable = [
        'telefone',
        'celular',
        'skype',
        'cep',
        'estado',
        'cidade',
        'codigo_municipio',
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

    public function getCodCidade()
    {
        return $this->attributes['cidade'];
    }

    public function getTelefone()
    {
        return $this->attributes['telefone'];
    }

    public function getTelefoneAttribute($value)
    {
        return DataHelper::mask($value, '(##) ####-####');
    }
    public function getCelularAttribute($value)
    {
        return DataHelper::mask($value, '(##) #####-####');
    }
    public function getCepAttribute($value)
    {
        return DataHelper::mask($value, '#####-###');
    }

    public function getCep()
    {
        return $this->attributes['cep'];
    }

    public function setTelefoneAttribute($value)
    {
        $this->attributes['telefone'] = DataHelper::getOnlyNumbers($value);
    }

    public function setCelularAttribute($value)
    {
        $this->attributes['celular'] = DataHelper::getOnlyNumbers($value);
    }

    public function setCepAttribute($value)
    {
        $this->attributes['cep'] = DataHelper::getOnlyNumbers($value);
    }

    public function getRua()
    {
        $endereco = '';
        $endereco .= ($this->attributes['logradouro'] != '') ? $this->attributes['logradouro'] : '';
        $endereco .= ($this->attributes['numero'] != '') ? ', ' . $this->attributes['numero'] : ', s/n';
        return $endereco;
    }
    public function getEnderecoCompleto()
    {
        $endereco = '';
        $endereco .= ($this->attributes['logradouro'] != '') ? $this->attributes['logradouro'] : '';
        $endereco .= ($this->attributes['numero'] != '') ? ', ' . $this->attributes['numero'] : ', s/n';
        $endereco .= ($this->attributes['bairro'] != '') ? ' - ' . $this->attributes['bairro'] : ' - sem bairro';
        $endereco .= ($this->attributes['cidade'] != '') ? ' - ' . $this->attributes['cidade'] : ' - sem cidade';
        $endereco .= ($this->attributes['estado'] != '') ? '/' . $this->attributes['estado'] : '';

        return $endereco;
    }
}
