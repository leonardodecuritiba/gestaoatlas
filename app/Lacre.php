<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lacre extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'lacres';
    protected $primaryKey = 'idlacre';
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
        'used',
    ];

    // ******************** FUNCTIONS ******************************
    static public function set_used($idlacre)
    {
        $Lacre = self::find($idlacre);
        $Lacre->used = 1;
        return $Lacre->save();
    }

    static public function lacre_exists($numeracao)
    {
        return (self::where('numeracao', $numeracao)->count() > 0);
    }

    public function extorna()
    {
        if ($this->externo == 1) {
            $this->forceDelete();
        } else {
            $this->used = 0;
            $this->save();
        }
        return;
    }

    public function repassaTecnico($idtecnico)
    {
        $this->idtecnico = $idtecnico;
        return $this->save();
    }

    public function has_lacre_instrumento()
    {
        return ($this->lacre_instrumento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function lacre_instrumento()
    {
        return $this->hasOne('App\LacreInstrumento', 'idlacre');
    }
    // ********************** HASONE ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ************************** HASMANY **********************************
}
