<?php

namespace App;

use App\Helpers\DataHelper;
use App\Models\PrazoPagamento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $timestamps = true;
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';
    protected $fillable = [
        'idcontato',
        'idcliente_centro_custo',
        'idpjuridica',
        'idpfisica',
        'idsegmento',
        'idregiao',
        'idcolaborador_criador',
        'idcolaborador_validador',
        'validated_at',
        'centro_custo',
        'email_orcamento',
        'email_nota',
        'foto',
        'limite_credito',
        'nome_responsavel',
        'distancia',
        'pedagios',
        'outros_custos',

        'idforma_pagamento_comercial',
        'prazo_pagamento_comercial',
        'idemissao_comercial',
        'limite_credito_comercial',
        'idtabela_preco_comercial',

        'idforma_pagamento_tecnica',
        'prazo_pagamento_tecnica',
        'idemissao_tecnica',
        'limite_credito_tecnica',
        'idtabela_preco_tecnica',
    ];

    // ************************ FUNCTIONS ******************************

    static public function getInvalidos()
    {
        return self::whereNull('idcolaborador_validador')->whereNull('validated_at')->get();
    }

    static public function getValidosOrdemServico()
    {
        return self::whereNotNull('idcolaborador_validador')->whereNotNull('validated_at');
    }

    static public function getAll($search)
    {
        $pessoa_juridica_ids = PessoaJuridica::where('razao_social', 'like', '%' . $search . '%')
            ->orWhere('nome_fantasia', 'like', '%' . $search . '%')->pluck('idpjuridica');
        return self::whereIn('idpjuridica', $pessoa_juridica_ids);
    }
//    public function setPrazoPagamentoTecnicaAttribute($value)
//    {
//        return json_encode($value);
//    }
//    public function setPrazoPagamentoComercialAttribute($value)
//    {
//        return json_encode($value);
//    }

    public function updatePrazo($dataUpdate)
    {
        foreach (['tecnica', 'comercial'] as $tipo) {
            $prazo_pagamento['id'] = $dataUpdate['prazo_pagamento_' . $tipo];
            switch ($dataUpdate['prazo_pagamento_' . $tipo]) {
                case PrazoPagamento::_STATUS_A_VISTA_:
                    $prazo_pagamento['extras'] = '';
                    break;
                case PrazoPagamento::_STATUS_PARCELADO_:
                    $prazo_pagamento['extras'] = $dataUpdate['parcela_' . $tipo];
                    break;
            }
            $this->{'prazo_pagamento_' . $tipo} = json_encode($prazo_pagamento);
        }
        $this->save();

    }

    public function getPrazoPagamentoTecnicaAttribute($value)
    {
        return json_decode($value);
    }

    public function getPrazoPagamentoComercialAttribute($value)
    {
        return json_decode($value);
    }

    public function custo_deslocamento()
    {
        return DataHelper::getFloat2Real($this->attributes['distancia'] * DataHelper::getReal2Float(Ajuste::getByMetaKey('custo_km')->meta_value));
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
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

    public function getEndereco() {
        $contato = $this->contato()->first();
        $retorno[0] = $contato->cidade;
        $retorno[1] = $contato->estado;
        return implode(" / ",$retorno);
    }

    public function getFones() {
        $contato = $this->contato()->first();
        $retorno[0] = $contato->telefone;
        $retorno[1] = $contato->celular;
        return implode(" / ",$retorno);
    }

    public function is_pjuridica()
    {
        return ($this->attributes['idpjuridica'] != NULL);
    }

    public function getType()
    {
        if ($this->attributes['idpjuridica'] != NULL) {
            $retorno = (object)[
                'tipo_cliente'   => 1,
                'tipo'           => 'CNPJ',
                'entidade'       => $this->pessoa_juridica()->first()->cnpj,
                'nome_principal' => $this->pessoa_juridica()->first()->nome_fantasia,
                'razao_social' => $this->pessoa_juridica()->first()->razao_social,
                'documento' => 'CNPJ: ' . $this->pessoa_juridica()->first()->cnpj,
            ];
        } else {
            $retorno = (object)[
                'tipo_cliente'   => 0,
                'tipo'           => 'CPF',
                'entidade'       => $this->pessoa_fisica()->first()->cpf,
                'nome_principal' => $this->attributes['nome_responsavel'],
                'razao_social' => $this->attributes['nome_responsavel'],
                'documento' => 'CPF: ' . $this->pessoa_fisica()->first()->cpf,
            ];
        }


        return $retorno;
    }

    //Mutattors

    public function pessoa_juridica()
    {
        return $this->hasOne('App\PessoaJuridica', 'idpjuridica', 'idpjuridica');
    }

    public function pessoa_fisica()
    {
        return $this->hasOne('App\PessoaFisica', 'idpfisica', 'idpfisica');
    }

    public function getURLFoto()
    {
        return ($this->attributes['foto'] != '') ? asset('uploads/' . $this->table . '/thumb_' . $this->attributes['foto']) : asset('imgs/user.png');
    }

    public function setLimiteCreditoTecnicaAttribute($value)
    {
        $this->attributes['limite_credito_tecnica'] = DataHelper::getReal2Float($value);
    }

    public function getLimiteCreditoTecnicaAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setLimiteCreditoComercialAttribute($value)
    {
        $this->attributes['limite_credito_comercial'] = DataHelper::getReal2Float($value);
    }

    public function getLimiteCreditoComercialAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function validado()
    {
        //testa se o cadastro foi validado
        return (($this->attributes['idcolaborador_validador'] != NULL) && ($this->attributes['validated_at'] != NULL));
    }

    public function cadastro_invalido()
    {
        //testa se o cadastro é inválido a mais de 24h
        if ($this->attributes['validated_at'] == NULL) {
            return true;
        }
        $now = Carbon::now();
        $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);
        return ($created_at->diffInHours($now) > 24);
    }

    public function criado_em()
    {
        $criacao = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);
        return $criacao->diffForHumans(Carbon::now());
//        return Carbon::now()->diffForHumans(Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['created_at']));
    }

    public function setDistanciaAttribute($value)
    {
        $this->attributes['distancia'] = DataHelper::getReal2Float($value);
    }

    public function getDistanciaAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setPedagiosAttribute($value)
    {
        $this->attributes['pedagios'] = DataHelper::getReal2Float($value);
    }

    public function getPedagiosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function setOutrosCustosAttribute($value)
    {
        $this->attributes['outros_custos'] = DataHelper::getReal2Float($value);
    }

    public function getOutrosCustosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function has_instrumento()
    {
        return ($this->instrumentos()->count() > 0);
    }

    public function instrumentos()
    {
        return $this->hasMany('App\Instrumento', 'idcliente');
    }

    public function has_equipamento()
    {
        return ($this->equipamentos()->count() > 0);
    }

    public function equipamentos()
    {
        return $this->hasMany('App\Equipamento', 'idcliente');
    }

    public function has_ordem_servicos()
    {
        return ($this->ordem_servicos()->count() > 0);
    }

    public function ordem_servicos()
    {
        return $this->hasMany('App\OrdemServico', 'idcliente');
    }

    public function centro_custo_para()
    {
        return $this->hasOne('App\Cliente', 'idcliente', 'idcliente');
    }

    // ********************** BELONGS ********************************

    public function segmento()
    {
        return $this->hasOne('App\Segmento', 'idsegmento', 'idsegmento');
    }

    public function tabela_preco_tecnica()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco_tecnica');
    }

    public function tipo_emissao_tecnica()
    {
        return $this->belongsTo('App\Models\TipoEmissaoFaturamento', 'idemissao_tecnica');
    }

    public function forma_pagamento_tecnica()
    {
        return $this->belongsTo('App\FormaPagamento', 'idforma_pagamento_tecnica');
    }

    public function tabela_preco_comercial()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco_comercial');
    }

    public function tipo_emissao_comercial()
    {
        return $this->belongsTo('App\Models\TipoEmissaoFaturamento', 'idemissao_comercial');
    }

    public function forma_pagamento_comercial()
    {
        return $this->belongsTo('App\FormaPagamento', 'idforma_pagamento_comercial');
    }


    // ************************** HASMANY **********************************

    public function centro_custo_de()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    public function fechamentos()
    {
        return $this->hasMany('App\Models\Fechamento', 'idcliente');
    }
}
