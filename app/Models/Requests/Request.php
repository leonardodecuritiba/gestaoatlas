<?php

namespace App\Models\Requests;

use App\Helpers\DataHelper;
use App\Lacre;
use App\Models\Inputs\Stocks\PartStock;
use App\Selo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'idtype',
        'idstatus',
        'idrequester',
        'idmanager',
        'reason',
        'parameters',
        'response',
        'enddate',
    ];

    /*
     * ========================================================
     * FLUX FUNCIONS ==========================================
     * ========================================================
     */

	/*
	 * PARTS ===============================================
	 */
	static public function openPartsRequest($data)
	{
		//openPecasRequest
		$idrequester = $data['idrequester'];
		$parameters = $data['parameters'];
		self::create([
			'idtype' => TypeRequest::_TYPE_PECAS_,
			'idstatus' => StatusRequest::_STATUS_AGUARDANDO_,
			'reason' => $data['reason'],
			'parameters' => json_encode($parameters),
			'idrequester' => $idrequester,
		]);
		return "Requisição de " . $parameters['opcao'] . " aberta com sucesso!";
	}

	static public function sendPartsRequest($data)
	{
		$Request = self::accept($data);
		$data['owner_id'] = $Request->requester->tecnico->idcolaborador;
		$data['id'] = $Request->getParametersUncoded()->id;
		PartStock::assign($data);
		return "Requisição aceita com sucesso!";
	}

	/*
	 * TOOLS ===============================================
	 */
	static public function openToolsRequest($data)
	{
		$idrequester = $data['idrequester'];
		$parameters = $data['parameters'];
		self::create([
			'idtype' => TypeRequest::_TYPE_FERRAMENTAS_,
			'idstatus' => StatusRequest::_STATUS_AGUARDANDO_,
			'reason' => $data['reason'],
			'parameters' => json_encode($parameters),
			'idrequester' => $idrequester,
		]);
		return "Requisição de " . $parameters['opcao'] . " aberta com sucesso!";
	}

	static public function sendToolsRequest($data)
	{
		dd('sendToolsRequest');
		$Request = self::accept($data);
		$data['idtecnico'] = $Request->requester->tecnico->idtecnico;
		if ($Request->idtype == TypeRequest::_TYPE_SELOS_) {
			Selo::assign($data);
		} elseif ($Request->idtype == TypeRequest::_TYPE_LACRES_) {
			Lacre::assign($data);
		}
		return "Requisição aceita com sucesso!";
	}

	/*
	 * PATTERNS ===============================================
	 */
	static public function openPatternsRequest($data)
	{
		$idrequester = $data['idrequester'];
		$parameters = $data['parameters'];
		self::create([
			'idtype' => TypeRequest::_TYPE_PADROES_,
			'idstatus' => StatusRequest::_STATUS_AGUARDANDO_,
			'reason' => $data['reason'],
			'parameters' => json_encode($parameters),
			'idrequester' => $idrequester,
		]);
		return "Requisição de " . $parameters['opcao'] . " aberta com sucesso!";
	}

	static public function sendPatternsRequest($data)
	{
		dd('sendPatternsRequest');
		$Request = self::accept($data);
		$data['idtecnico'] = $Request->requester->tecnico->idtecnico;
		if ($Request->idtype == TypeRequest::_TYPE_SELOS_) {
			Selo::assign($data);
		} elseif ($Request->idtype == TypeRequest::_TYPE_LACRES_) {
			Lacre::assign($data);
		}
		return "Requisição aceita com sucesso!";
	}

    /*
     * SELO-LACRE =============================================
     */

    static public function openSeloLacreRequest($data)
    {
        $idrequester = $data['idrequester'];
        $parameters = $data['parameters'];
        self::create([
            'idtype' => ($parameters['opcao'] == 'selos') ? TypeRequest::_TYPE_SELOS_ : TypeRequest::_TYPE_LACRES_,
            'idstatus' => StatusRequest::_STATUS_AGUARDANDO_,
            'reason' => $data['reason'],
            'parameters' => json_encode($parameters),
            'idrequester' => $idrequester,
        ]);
        return "Requisição de " . $parameters['opcao'] . " aberta com sucesso!";
    }

    static public function sendSeloLacreRequest($data)
    {
        $Request = self::accept($data);
        $data['idtecnico'] = $Request->requester->tecnico->idtecnico;
        if ($Request->idtype == TypeRequest::_TYPE_SELOS_) {
            Selo::assign($data);
        } elseif ($Request->idtype == TypeRequest::_TYPE_LACRES_) {
            Lacre::assign($data);
        }
        return "Requisição aceita com sucesso!";
    }

	public function getSelos($ids)
	{
		return Selo::whereIn('idselo',$ids)->get()->map(function($s){
			return $s->getFormatedSeloDV();
		});
	}

	public function getLacres($ids)
	{
		return Lacre::whereIn('idlacre',$ids)->get()->map(function($s){
			return $s->getNumeracao();
		});
	}

	/*
	 * DEFAULT =============================================
	 */

    static public function accept($data)
    {
        $self = self::findOrFail($data['id']);
        $parameters = $self->getParametersUncoded();
        if($self->attributes['idtype'] == TypeRequest::_TYPE_PECAS_){
	        $parameters->valores = $parameters->id;
        } else {
	        $parameters->valores = $data['valores'];
        }
	    $self->update([
            'parameters' => json_encode($parameters),
            'idmanager' => $data['idmanager'],
            'idstatus' => StatusRequest::_STATUS_ACEITA_,
        ]);
        return $self;
    }

    static public function deny($data)
    {
        $Data = self::findOrFail($data['id']);
        $Data->update([
            'idstatus' => StatusRequest::_STATUS_NEGADA_,
            'idmanager' => $data['idmanager'],
            'response' => $data['response'],
        ]);
        return "Requisição negada com sucesso!";
    }

    /*
     * ========================================================
     * MUTATTORS ==============================================
     * ========================================================
     */

    /*
     * ========================================================
     * ACCESSORS ==============================================
     * ========================================================
     */

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getFormatedRequest()
    {
        return json_encode([
            'id' => $this->getAttribute('id'),
            'date' => $this->created_at,
            'type' => $this->getTypeText(),
            'parameters' => $this->getParametersText(),
            'parameters_json' => $this->getAttribute('parameters'),
            'reason' => $this->getAttribute('reason'),
            'status' => $this->getStatusText(),
            'manager' => $this->getNameManager(),
            'requester' => $this->getNameRequester(),
        ]);
    }

    public function getTypeText()
    {
        return $this->type->description;
    }

    public function getParametersUncoded()
    {
        return json_decode($this->getAttribute('parameters'));
    }

    public function getParametersText()
    {
        $parameters = $this->getParametersUncoded();
        switch ($this->getAttribute('idtype')) {
            case TypeRequest::_TYPE_SELOS_:
	            return 'Quantidade: ' . $parameters->quantidade;
            case TypeRequest::_TYPE_LACRES_:
                return 'Quantidade: ' . $parameters->quantidade;
                break;
            case TypeRequest::_TYPE_PECAS_:
	            $p = PartStock::findOrFail($parameters->id);
	            return 'Peça: ' . $p->getShortDescritptions();
                break;
        }
    }
    public function getParametersValoresText()
    {
        $parameters = $this->getParametersUncoded();
	    $valores = [];
        switch ($this->getAttribute('idtype')) {
            case TypeRequest::_TYPE_SELOS_:
	            if(isset($parameters->valores)){
		            $valores = $this->getSelos($parameters->valores);
	            }
	            break;
            case TypeRequest::_TYPE_LACRES_:
	            if(isset($parameters->valores)){
		            $valores = $this->getLacres($parameters->valores);
	            }
	            break;
        }
	    return $valores;
    }

    public function getResponseText()
    {
        switch ($this->getAttribute('idstatus')) {
            case StatusRequest::_STATUS_NEGADA_:
                return 'Motivo: ' . $this->getAttribute('response');
            case StatusRequest::_STATUS_ACEITA_:
                return '-';
                break;
        }
    }

    public function getStatusText()
    {
        return $this->status->description;
    }

    public function getNameManager()
    {
        $manager = $this->manager;
        return ($manager != NULL) ? $manager->nome : '-';
    }

    public function getNameRequester()
    {
        return $this->requester->nome;
    }

    public function isWaiting()
    {
        return ($this->getAttribute('idstatus') == StatusRequest::_STATUS_AGUARDANDO_);
    }

    public function getStatusColor()
    {
        switch ($this->getAttribute('idstatus')) {
            case StatusRequest::_STATUS_AGUARDANDO_:
                return 'warning';
            case StatusRequest::_STATUS_ACEITA_:
                return 'success';
            case StatusRequest::_STATUS_NEGADA_:
                return 'danger';
        }
    }

    /*
     * ========================================================
     * SCOPE ==================================================
     * ========================================================
     */


	/**
	 * Scope a query to only include active users.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWaiting($query)
	{
		return $query->where('idstatus', StatusRequest::_STATUS_AGUARDANDO_);
	}

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelos($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_SELOS_);
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSeloLacres($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_SELOS_)
            ->orWhere('idtype', TypeRequest::_TYPE_LACRES_);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLacres($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_LACRES_);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTools($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_FERRAMENTAS_);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePatterns($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_PADROES_);
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParts($query)
    {
        return $query->where('idtype', TypeRequest::_TYPE_PECAS_);
    }

    /*
     * ========================================================
     * RELATIONSHIP ===========================================
     * ========================================================
     */

    public function type()
    {
        return $this->belongsTo('App\Models\Requests\TypeRequest', 'idtype');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Requests\StatusRequest', 'idstatus');
    }

    public function requester()
    {
        return $this->belongsTo('App\Colaborador', 'idrequester', 'idcolaborador');
    }

    public function manager()
    {
        return $this->belongsTo('App\Colaborador', 'idmanager', 'idcolaborador');
    }

}

