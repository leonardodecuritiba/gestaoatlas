<?php

namespace App\Models\Requests;

use App\Helpers\DataHelper;
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
        'parameters',
        'response',
        'enddate',
    ];

    /*
     * ========================================================
     * FLUX FUNCIONS ==========================================
     * ========================================================
     */

    static public function openSeloLacreRequest($values)
    {
        $idrequester = $values['idrequester'];
        $parameters = $values['parameters'];
        self::create([
            'idtype' => ($parameters['opcao'] == 'selos') ? TypeRequest::_TYPE_SELOS_ : TypeRequest::_TYPE_LACRES_,
            'idstatus' => StatusRequest::_STATUS_AGUARDANDO_,
            'parameters' => json_encode($parameters),
            'idrequester' => $idrequester,
        ]);
        return "Requisição de " . $parameters['opcao'] . " aberta com sucesso!";
    }

    static public function sendSeloLacreRequest($data)
    {
        $Request = self::accept($data);
        $data['idtecnico'] = $Request->requester->tecnico->idtecnico;
        Selo::assign($data);
        return "Requisição aceita com sucesso!";
    }

    static public function accept($data)
    {
        $Data = self::findOrFail($data['id']);
        $Data->update([
            'idmanager' => $data['idmanager'],
            'idstatus' => StatusRequest::_STATUS_ACEITA_,
        ]);
        return $Data;
    }

    static public function deny($data)
    {
        $Data = self::findOrFail($data['id']);
        $Data->update([
            'idstatus' => StatusRequest::_STATUS_NEGADA_,
            'idmanager' => $data['idmanager'],
            'response' => $data['response'],
        ]);
        return "Requisição de negada com sucesso!";
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
            'status' => $this->getStatusText(),
            'manager' => $this->getNameManager(),
            'requester' => $this->getNameRequester(),
        ]);
    }

    public function getTypeText()
    {
        return $this->type->description;
    }

    public function getParametersText()
    {
        $parameters = json_decode($this->getAttribute('parameters'));
        switch ($this->getAttribute('idtype')) {
            case TypeRequest::_TYPE_SELOS_:
            case TypeRequest::_TYPE_LACRES_:
                return 'Quantidade: ' . $parameters->quantidade;
                break;
        }
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

