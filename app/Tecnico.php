<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    protected $table = 'tecnicos';
    protected $primaryKey = 'idtecnico';
    public $timestamps = true;
    protected $fillable = [
        'idcolaborador',
        'carteira_imetro',
        'carteira_ipem'
    ];

    // ************************ FUNCTIONS ******************************
    public function getDocumentos()
    {
        return json_encode([
            'Carteira do IMETRO' => ($this->carteira_imetro!='')?asset('../storage/uploads/'.$this->table.'/'.$this->carteira_imetro):asset('imgs/documents.png'),
            'Carteira do IPEM'   => ($this->carteira_ipem!='')?asset('../storage/uploads/'.$this->table.'/'.$this->carteira_ipem):asset('imgs/documents.png')
        ]);
    }
    public function has_selos()
    {
        return ($this->selos()->count() > 0);
    }
    public function has_lacres()
    {
        return ($this->lacres()->count() > 0);
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function selos()
    {
        return $this->hasMany('App\Selo', 'idtecnico')->orderBy('numeracao','dsc');
    }
    public function lacres()
    {
        return $this->hasMany('App\Lacre', 'idtecnico')->orderBy('numeracao','dsc');
    }
    // ********************** BELONGS ********************************
    public function colaborador()
    {
        return $this->belongsTo('App\Colaborador', 'idcolaborador');
    }
}
