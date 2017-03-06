<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    public $timestamps = true;
    protected $table = 'tecnicos';
    protected $primaryKey = 'idtecnico';
    protected $fillable = [
        'idcolaborador',
        'carteira_imetro',
        'carteira_ipem',
        'desconto_max',
        'acrescimo_max',
    ];

    // ************************ FUNCTIONS ******************************

    static public function outros($idtecnico)
    {
        return self::where('idtecnico', '<>', $idtecnico)->get();
    }

    public function setDescontoMaxAttribute($value)
    {
        $this->attributes['desconto_max'] = DataHelper::getReal2Float($value);
    }

    public function getDescontoMaxAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function desconto_max_float()
    {
        return $this->attributes['desconto_max'];
    }

    public function setAcrescimoMaxAttribute($value)
    {
        $this->attributes['acrescimo_max'] = DataHelper::getReal2Float($value);
    }

    public function getAcrescimoMaxAttribute($value)
    {
        return DataHelper::getFloat2Real($value);
    }

    public function acrescimo_max_float()
    {
        return $this->attributes['acrescimo_max'];
    }

    public function getDocumentos()
    {
        return json_encode([
            'Carteira do IMETRO' => ($this->carteira_imetro != '') ? asset('uploads/' . $this->table . '/' . $this->carteira_imetro) : asset('imgs/documents.png'),
            'Carteira do IPEM' => ($this->carteira_ipem != '') ? asset('uploads/' . $this->table . '/' . $this->carteira_ipem) : asset('imgs/documents.png')
        ]);
    }

    public function has_selos()
    {
        return ($this->selos()->count() > 0);
    }

    public function selos()
    {
        return $this->hasMany('App\Selo', 'idtecnico')->orderBy('numeracao', 'dsc');
    }

    public function last_selo()
    {
        $data = $this->hasMany('App\Selo', 'idtecnico')->orderBy('numeracao', 'dsc')->first();
        return (count($data) > 0) ? $data->numeracao : 0;
    }

    public function last_lacre()
    {
        $data = $this->hasMany('App\Lacre', 'idtecnico')->orderBy('numeracao', 'dsc')->first();
        return (count($data) > 0) ? $data->numeracao : 0;
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function has_lacres()
    {
        return ($this->lacres()->count() > 0);
    }

    public function lacres()
    {
        return $this->hasMany('App\Lacre', 'idtecnico')->orderBy('numeracao', 'dsc');
    }

    public function lacres_usados()
    {
        return $this->hasMany('App\Lacre', 'idtecnico')->where('used', 1)->orderBy('numeracao', 'asc');
    }

    public function lacres_disponiveis()
    {
        return $this->hasMany('App\Lacre', 'idtecnico')->where('used', 0)->orderBy('numeracao', 'asc');
    }

    public function selos_usados()
    {
        return $this->hasMany('App\Selo', 'idtecnico')->where('used', 1)->orderBy('numeracao', 'asc');
    }

    public function selos_disponiveis()
    {
        return $this->hasMany('App\Selo', 'idtecnico')->where('used', 0)->orderBy('numeracao', 'asc');
    }

    public function selos_a_trocar($ini, $end)
    {
        return $this->selos()
            ->where('used', 0)
            ->whereNotNull('numeracao')
            ->whereBetween('numeracao', [$ini, $end])
            ->orderBy('numeracao', 'desc');
    }

    public function lacres_a_trocar($ini, $end)
    {
        return $this->lacres()
            ->where('used', 0)
            ->whereNotNull('numeracao')
            ->whereBetween('numeracao', [$ini, $end])
            ->orderBy('numeracao', 'desc');
    }

    // ********************** BELONGS ********************************

    public function colaborador()
    {
        return $this->belongsTo('App\Colaborador', 'idcolaborador');
    }
}
