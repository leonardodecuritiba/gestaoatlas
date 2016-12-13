<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    public $timestamps = true;
    protected $table = 'tecnicos';
    protected $primaryKey = 'idtecnico';
    protected $fillable = [
        'idcolaborador',
        'carteira_imetro',
        'carteira_ipem'
    ];

    // ************************ FUNCTIONS ******************************
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

    // ********************** BELONGS ********************************

    public function colaborador()
    {
        return $this->belongsTo('App\Colaborador', 'idcolaborador');
    }
}
