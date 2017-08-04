<?php

namespace App;

use App\Traits\SeloLacre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lacre extends Model
{
    use SeloLacre;
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

    static public function assign($data)
    {
        return self::whereIn('idlacre', $data['valores'])
            ->update(['idtecnico' => $data['idtecnico']]);
    }

    static public function lacre_exists($numeracao)
    {
        return (self::where('numeracao', $numeracao)->count() > 0);
    }

    public function has_lacre_instrumento()
    {
        return ($this->lacre_instrumento()->count() > 0);
    }

    public function lacre_instrumento()
    {
        return $this->hasOne('App\LacreInstrumento', 'idlacre');
    }

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function getNumeracao()
    {
        return ($this->attributes['externo']) ? $this->attributes['numeracao_externa'] : $this->attributes['numeracao'];
    }

    // ********************** HASONE ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ************************** HASMANY **********************************
}
