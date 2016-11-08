<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selo extends Model
{
    use SoftDeletes;
    protected $table = 'selos';
    protected $primaryKey = 'idselo';
    public $timestamps = true;
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
    ];


    // ******************** FUNCTIONS ******************************
    static public function selo_exists($numeracao)
    {
        return (Selo::where('numeracao',$numeracao)->count() > 0);
    }
    public function has_selo_instrumento()
    {
        return ($this->selo_instrumento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ********************** HASONE ********************************
    public function selo_instrumento()
    {
        return $this->hasOne('App\SeloInstrumento', 'idselo');
    }
    // ************************** HASMANY **********************************
}
