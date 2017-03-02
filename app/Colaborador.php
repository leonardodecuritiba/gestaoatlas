<?php

namespace App;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
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

    public function setDataNascimentoAttribute($value)
    {
        $this->attributes['data_nascimento'] = DataHelper::setDate($value);
    }

    public function getDataNascimentoAttribute($value)
    {
        return DataHelper::getPrettyDate($value);
    }

    // ************************ FUNCTIONS ******************************
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
}
