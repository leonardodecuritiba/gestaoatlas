<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lacre extends Model
{
    use SoftDeletes;
    protected $table = 'lacres';
    protected $primaryKey = 'idlacre';
    public $timestamps = true;
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
    ];

    // ******************** FUNCTIONS ******************************
    static public function lacre_exists($numeracao)
    {
        return (Lacre::where('numeracao',$numeracao)->count() > 0);
    }
    public function has_lacre_instrumento()
    {
        return ($this->lacre_instrumento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ********************** HASONE ********************************
    public function lacre_instrumento()
    {
        return $this->hasOne('App\LacreInstrumento', 'idlacre');
    }
    // ************************** HASMANY **********************************
}
