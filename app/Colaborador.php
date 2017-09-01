<?php

namespace App;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\Inputs\Equipment;
use App\Models\Inputs\Instrument;
use App\Models\Inputs\Pattern;
use App\Models\Inputs\Stocks\PatternStock;
use App\Models\Inputs\Tool;
use App\Models\Inputs\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Colaborador extends Model
{
    public $timestamps = true;
    protected $table = 'colaboradores';
    protected $primaryKey = 'idcolaborador';
    protected $fillable = [
        'idcontato',
        'iduser',
        'nome',
        'cpf',
        'rg',
        'data_nascimento',
        'cnh',
        'carteira_trabalho'
    ];

    // ************************ FUNCTIONS ******************************

    public function setDataNascimentoAttribute($value)
    {
        $this->attributes['data_nascimento'] = DataHelper::setDate($value);
    }

    public function getDataNascimentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
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

    public function getCpfAttribute($value)
    {
        return DataHelper::mask($value, '###.###.###-##');
    }

    public function getRgAttribute($value)
    {
        return DataHelper::mask($value, '##.###.###-#');
    }

    public function getDocumentos()
    {
        return json_encode([
            'CNH' => ($this->attributes['cnh'] != '') ? asset('uploads/' . $this->table . '/' . $this->attributes['cnh']) : asset('imgs/documents.png'),
            'Carteira de Trabalho' => ($this->attributes['carteira_trabalho'] != '') ? storage_path('/uploads/' . $this->table . '/' . $this->attributes['carteira_trabalho']) : asset('imgs/documents.png')
        ]);
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function has_equipamento()
    {
        return 0;
    }

    public function getDataNascimento()
    {
        return DataHelper::getPrettyDate($this->attributes['data_nascimento']);
    }

    public function is()
    {
        return $this->user->is();
    }

    public function hasRole($tipo)
    {
        return $this->user->hasRole($tipo);
    }

    public function hasAvisosClientes($tipo)
    {
        return $this->user->hasRole($tipo);
    }

    public function isSelf()
    {
        return (Auth::user()->colaborador->idcolaborador == $this->attributes['idcolaborador']) ? 1 : 0;
    }
    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function clientes_invalidos()
    {
        return Cliente::getInvalidos();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'iduser', 'iduser');
    }

    // ********************** BELONGS ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idcolaborador', 'idcolaborador');
    }

    public function requisicoes()
    {
        return $this->hasMany('App\Models\Requests\Request', 'idrequester', 'idcolaborador');
    }

    public function requisicoes_waiting()
    {
        return $this->hasMany('App\Models\Requests\Request', 'idrequester', 'idcolaborador')->waiting();
    }


	public function patterns() {
		return $this->hasMany( PatternStock::class, 'idcolaborador' );
	}

	// ************************** HAS **********************************

	public function tools() {
		return $this->belongsToMany( Tool::class, 'tool_stocks', 'idcolaborador', 'idtool' );
	}

	public function vehicles() {
		return $this->belongsToMany( Vehicle::class, 'vechicle_stocks', 'idcolaborador', 'idvechicle' );
	}

	public function instruments() {
		return $this->belongsToMany( Instrument::class, 'instrument_stocks', 'idcolaborador', 'idinstrument' );
	}

	public function equipments() {
		return $this->belongsToMany( Equipment::class, 'equipment_stocks', 'idcolaborador', 'idequipment' );
	}
}
