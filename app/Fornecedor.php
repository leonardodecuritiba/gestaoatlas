<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'fornecedores';
    protected $primaryKey = 'idfornecedor';
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

    public function contato()
    {
        return $this->hasOne('App\Contato', 'idcontato', 'idcontato');
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

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function pessoa_juridica()
    {
        return $this->hasOne('App\PessoaJuridica', 'idpjuridica', 'idpjuridica');
    }

    public function pessoa_fisica()
    {
        return $this->hasOne('App\PessoaFisica', 'idpfisica', 'idpfisica');
    }

    public function has_peca()
    {
        return ($this->pecas()->count() > 0);
    }

    public function pecas()
    {
        return $this->hasMany('App\Peca', 'idfornecedor');
    }
    public function segmento()
    {
        return $this->hasOne('App\Models\Ajustes\RecursosHumanos\Fornecedores\SegmentoFornecedor', 'idsegmento_fornecedor', 'idsegmento_fornecedor');
    }
    // ********************** BELONGS ********************************

}
