<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'fornecedores';
    protected $primaryKey = 'idfornecedor';
    public $timestamps = true;
    protected $fillable = [
        'idcontato',
        'idpjuridica',
        'idpfisica',
        'idsegmento_fornecedor',
        'grupo',
        'email_orcamento',
        'nome_responsavel'
    ];

    // ************************ FUNCTIONS ******************************
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y - H:i');
    }
    public function getEnderecoResumido() {
        $contato = $this->contato()->first();
        $retorno[0] = $contato->cidade;
        $retorno[1] = $contato->estado;
        return $retorno;
    }

    public function getType()
    {
        if($this->idpjuridica != NULL){
            $retorno = (object)[
                'tipo_fornecedor'=> 1,
                'tipo'           => 'Nome Fantasia',
                'entidade'       => $this->pessoa_juridica()->first()->nome_fantasia,
                'nome_principal' => $this->pessoa_juridica()->first()->nome_fantasia
            ];
        } else {
            $retorno = (object)[
                'tipo_fornecedor'=> 0,
                'tipo'           => 'CPF',
                'entidade'       => $this->pessoa_fisica()->first()->cpf,
                'nome_principal' => $this->nome_responsavel
            ];
        }
        return $retorno;
    }

    public function has_peca()
    {
        return ($this->pecas()->count() > 0);
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function contato()
    {
        return $this->hasOne('App\Contato', 'idcontato', 'idcontato');
    }
    public function pessoa_juridica()
    {
        return $this->hasOne('App\PessoaJuridica', 'idpjuridica', 'idpjuridica');
    }
    public function pessoa_fisica()
    {
        return $this->hasOne('App\PessoaFisica', 'idpfisica', 'idpfisica');
    }
    public function pecas()
    {
        return $this->hasMany('App\Peca', 'idfornecedor');
    }
    public function segmento()
    {
        return $this->hasOne('App\SegmentoFornecedor', 'idsegmento_fornecedor', 'idsegmento_fornecedor');
    }
    // ********************** BELONGS ********************************

}
