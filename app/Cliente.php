<?php

namespace App;

use App\Models\Ajustes\Ajuste;
use App\Models\TipoEmissaoFaturamento;
use App\Traits\CommonTrait;
use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;
use App\Helpers\DataHelper;
use App\Models\PrazoPagamento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Cliente extends Model
{
	use CommonTrait;
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

        'numero_chamado',
    ];
	// =====================================================================
	// ======================== NEW FUNCTIONS ==============================
	// =====================================================================

    // ************ Corrigir depois quando Houver VENDA ************
    public function getData(){
        $data = $this->toArray();
        $available_limit_tecnica = $this->getAvailableLimitTecnica();
        $available_limit_comercial = $this->getAvailableLimitComercial();
        return json_encode(array_merge($data,[
            'available_limit_tecnica'               => $available_limit_tecnica,
            'available_limit_tecnica_formatted'     => $this->getAvailableLimitTecnicaFormatted($available_limit_tecnica),
            'available_limit_comercial'             => $available_limit_comercial,
            'available_limit_comercial_formatted'   => $this->getAvailableLimitComercialFormatted($available_limit_comercial),
        ]));

    }

    public function getAvailableLimitCentroCusto($type = 'tecnica'){
        $ordem_servicos = $this->ordem_servicos_centro_custo;
        $sum = $ordem_servicos->whereIn('idsituacao_ordem_servico',
            [
                OrdemServico::_STATUS_FINALIZADA_,
                OrdemServico::_STATUS_AGUARDANDO_PECA_,
                OrdemServico::_STATUS_EQUIPAMENTO_NA_OFICINA_,
                OrdemServico::_STATUS_FATURAMENTO_PENDENTE_,
            ])->sum('valor_final');
        return $sum;
    }

    public function getAvailableLimit($type = 'tecnica')
    {
        $sum = 0;
        if($this->centro_custo){ //se tem centro de custo
            $centro_custo = $this->centro_custo_de;
            $limit = $centro_custo->attributes['limite_credito_' . $type];
            if($type == 'tecnica'){
                $sum = $centro_custo->getAvailableLimitCentroCusto($type);
            }
        } else {
            $limit = $this->attributes['limite_credito_' . $type];
            if($type == 'tecnica'){
                $sum = $this->getAvailableLimitCentroCusto($type) +
                    $this->ordem_servicos->whereIn('idsituacao_ordem_servico',
                        [
                            OrdemServico::_STATUS_FINALIZADA_,
                            OrdemServico::_STATUS_AGUARDANDO_PECA_,
                            OrdemServico::_STATUS_EQUIPAMENTO_NA_OFICINA_,
                            OrdemServico::_STATUS_FATURAMENTO_PENDENTE_,
                        ])->sum('valor_final');
            }
        }
        return $limit - $sum;
    }

    public function getAvailableLimitTecnica()
    {
        return $this->getAvailableLimit($type = 'tecnica');
    }

    public function getAvailableLimitTecnicaFormatted($value = NULL)
    {
        $value = ($value == NULL) ? $this->getAvailableLimitTecnica() : $value;
        return DataHelper::getFloat2RealMoeda($value);
    }

    public function getAvailableLimitComercial()
    {
        return $this->getAvailableLimit($type = 'comercial');
    }

    public function getAvailableLimitComercialFormatted($value = NULL)
    {
        $value = ($value == NULL) ? $this->getAvailableLimitComercial() : $value;
        return DataHelper::getFloat2RealMoeda($value);
    }


    static public function findByText($search)
    {
        $query = (new self)->newQuery();
        if ($search != NULL) {
            $pj_ids = PessoaJuridica::where('razao_social', 'like', '%' . $search . '%')
                ->orWhere('nome_fantasia', 'like', '%' . $search . '%')->pluck('idpjuridica');
            $query->whereIn('idpjuridica', $pj_ids);
        }
        return $query
            ->with('pessoa_juridica', 'pessoa_fisica');
    }


	public function getLimitCentroCusto($client_id = NULL)
	{
		$max = $this->attributes['limite_credito_tecnica'];
		$soma = $this->centro_custo_para($client_id)->sum(function($c){
			return $c->attributes['limite_credito_tecnica'];
		});

		return $max - $soma;
	}

	public function getLimitCentroCustoFormatted()
	{
		return DataHelper::getFloat2RealMoeda($this->getLimitCentroCusto($client_id = NULL));
	}

    public function getMaxCentroCusto()
    {
        return $this->centro_custo_de->getLimitCentroCusto();
    }

    public function isCentroCusto()
    {
        return ($this->attributes['idcliente_centro_custo'] != NULL);
    }


    public function getRazaoSocial()
    {
        return ($this->is_pjuridica()) ?
            $this->pessoa_juridica()->first()->razao_social :
            $this->attributes['nome_responsavel'];
    }

    public function getTipoEmissao($option)
    {
        $tipo = ($option == 'tecnica') ? $this->tipo_emissao_tecnica : $this->tipo_emissao_comercial;
        return $tipo->descricao;
    }

    public function getFormaPagamento($option)
    {
        $tipo = ($option == 'tecnica') ? $this->forma_pagamento_tecnica : $this->forma_pagamento_comercial;
        return $tipo->descricao;
    }

    public function getPrazoPagamentoText($option)
    {
        if ($option == 'tecnica') {
            $data = $this->prazo_pagamento_tecnica;
        } else {
            $data = $this->prazo_pagamento_comercial;
        }

        if ($data->id) {
            $parcelas = implode(', ', $data->extras);
            $text = 'PARCELADO: ' . $parcelas . ' dias';
        } else {
            $text = 'À VISTA';
        }
        return $text;
    }

    public function getName()
    {
        return ($this->is_pjuridica()) ?
            $this->pessoa_juridica()->first()->nome_fantasia :
            $this->attributes['nome_responsavel'];
    }

	public function getShortName()
	{
		return str_limit($this->getName(),20);
	}

	public function getDocument()
	{
		return ($this->is_pjuridica()) ?
			$this->pessoa_juridica()->first()->cnpj :
			$this->pessoa_fisica()->first()->cpf;
	}

	public function getResponsibleName()
	{
		return $this->attributes['nome_responsavel'];
	}

	public function getPhone()
	{
		return $this->contato->telefone;
	}

    // =====================================================================
    // ======================== SITUATION ==================================
    // =====================================================================

    public function getValidatedColor()
    {
        return $this->validado() ? 'success' : 'danger';
    }

    public function getValidatedText()
    {
        return $this->validado() ? 'Validado' : 'Não Validado';
    }

	// =====================================================================
	// ======================== COSTS FUNCTIONS ============================
	// =====================================================================

	public function getCostDisplacement()
	{
		return ($this->attributes['distancia'] * DataHelper::getReal2Float(Ajuste::getByMetaKey('custo_km')->meta_value));
	}

	public function getCostDisplacementFormatted()
	{
		return DataHelper::getReal2Float($this->getCostDisplacement());
	}

	public function getCostToll()
	{
		return $this->attributes['pedagios'];
	}

	public function getCostTollFormatted()
	{
		return DataHelper::getReal2Float($this->getCostToll());
	}

	public function getCostOther()
	{
		return $this->attributes['outros_custos'];
	}

	public function getCostOtherFormatted()
	{
		return DataHelper::getReal2Float($this->getCostOther());
	}


	// =====================================================================
	// =====================================================================
	// =====================================================================

	// ************************ EMAIL-FUNCTIONS ******************************

	static public function getInvalidos()
	{
		return self::whereNull('idcolaborador_validador')->whereNull('validated_at')->get();
	}


	// ************************ FUNCTIONS ******************************


	static public function getValidosOrdemServico()
    {
        return self::whereNotNull('validated_at')
            ->orWhere('created_at', '<', Carbon::now()->subDay());

        return self::whereNotNull('validated_at')
            ->orWhere(function ($query) {
                $query->whereNull('validated_at');
                $query->where('created_at', '<', Carbon::now()->subDay());
            });

        return self::whereNull('validated_at')
            ->orWhere('created_at', '<', Carbon::now()->subDay());
    }

    public function scopeValidos($query)
    {
//        return $query->where(function ($q) {
//            $q->whereNotNull('validated_at')
//                ->orWhere('created_at', '<', Carbon::now()->subDay());
//        });
        return $query->whereNotNull('validated_at');
    }

    public function isValidated()
    {
        if ($this->attributes['validated_at'] == NULL) {
            $days = Carbon::now()->diffInHours(Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']));
            return ($days <= 24);
        }
        return true;
    }

    public function sendNF($link)
    {
// Setup a new SmtpTransport instance for Gmail
        $transport = SmtpTransport::newInstance(
            env('MAIL_HOST'),
            env('MAIL_PORT'),
            env('MAIL_ENCRYPTION')
        );

        $transport->setUsername(env('MAIL_USERNAME_FATURAMENTO'));
        $transport->setPassword(env('MAIL_PASSWORD_FATURAMENTO'));
        $email = new Swift_Mailer($transport);
        Mail::setSwiftMailer($email);

        $cliente = [
            'nome' => $this->nome_responsavel,
            'email' => $this->email_nota
        ];
        return Mail::send('emails.clientes.send_nf', ['link' => $link, 'cliente' => $this], function ($m) use ($cliente) {
            $m->from(env('MAIL_USERNAME_FATURAMENTO'), env('MAIL_NAME_FATURAMENTO'));
            $m->to(['silva.zanin@gmail.com', 'comercial@atlastecnologia.com.br'], $cliente['nome'])
                ->subject('Nota Fiscal');
        });

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
                'idcliente' => $this->idcliente,
                'tipo_cliente' => 1,
                'tipo' => 'CNPJ',
                'entidade' => $this->pessoa_juridica()->first()->cnpj,
                'nome_principal' => $this->pessoa_juridica()->first()->nome_fantasia,
                'razao_social' => $this->pessoa_juridica()->first()->razao_social,
                'ie' => $this->pessoa_juridica()->first()->ie,
                'documento' => 'CNPJ: ' . $this->pessoa_juridica()->first()->cnpj,
            ];
        } else {
            $retorno = (object)[
                'idcliente' => $this->idcliente,
                'tipo_cliente' => 0,
                'tipo' => 'CPF',
                'entidade' => $this->pessoa_fisica()->first()->cpf,
                'nome_principal' => $this->attributes['nome_responsavel'],
                'razao_social' => $this->attributes['nome_responsavel'],
                'ie' => '-',
                'documento' => 'CPF: ' . $this->pessoa_fisica()->first()->cpf,
            ];
        }


        return $retorno;
    }

    //Mutattors

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

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function setOutrosCustosAttribute($value)
    {
        $this->attributes['outros_custos'] = DataHelper::getReal2Float($value);
    }

    public function getOutrosCustosAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

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

    public function ordem_servicos_centro_custo()
    {
        return $this->hasMany(OrdemServico::class, 'idcentro_custo', 'idcliente');
    }
    // ********************** BELONGS ********************************

    public function segmento()
    {
        return $this->hasOne('App\Models\Ajustes\RecursosHumanos\Clientes\Segmento', 'idsegmento', 'idsegmento');
    }

    public function tabela_preco_tecnica()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco_tecnica');
    }

    public function tipo_emissao_tecnica()
    {
        return $this->belongsTo(TipoEmissaoFaturamento::class, 'idemissao_tecnica');
    }

    public function forma_pagamento_tecnica()
    {
        return $this->belongsTo(FormaPagamento::class, 'idforma_pagamento_tecnica');
    }

    public function tabela_preco_comercial()
    {
        return $this->belongsTo('App\TabelaPreco', 'idtabela_preco_comercial');
    }

    public function tipo_emissao_comercial()
    {
        return $this->belongsTo(TipoEmissaoFaturamento::class, 'idemissao_comercial');
    }

    public function forma_pagamento_comercial()
    {
        return $this->belongsTo(FormaPagamento::class, 'idforma_pagamento_comercial');
    }


    // ************************** HASMANY **********************************


	public function pessoa_juridica()
	{
		return $this->hasOne('App\PessoaJuridica', 'idpjuridica', 'idpjuridica');
	}

	public function pessoa_fisica()
	{
		return $this->hasOne('App\PessoaFisica', 'idpfisica', 'idpfisica');
	}

    public function centro_custo_de() //quem é o centro de custo do cliente
    {
        return $this->belongsTo(Cliente::class, 'idcliente_centro_custo','idcliente');
    }


    public function centro_custo_para($client_id = NULL) //listagens para quem o cliente é centro de custo
    {
    	$query = $this->hasMany(Cliente::class, 'idcliente_centro_custo','idcliente');
    	if($client_id != NULL) $query->where('idcliente', '!=',$client_id);
    	return $query->get();
    }

    public function faturamento()
    {
        return $this->hasMany('App\Models\Faturamento', 'idcliente');
    }
}
