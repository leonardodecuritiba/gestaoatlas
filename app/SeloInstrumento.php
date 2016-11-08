<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeloInstrumento extends Model
{
    use SoftDeletes;
    protected $table = 'selo_instrumentos';
    protected $primaryKey = 'idselo_instrumento';
    public $timestamps = true;
    protected $fillable = [
        'idinstrumento',
        'idselo',
        'afixado_em',
        'retirado_em'
    ];



    // ******************** FUNCTIONS ******************************
    public function getAfixadoEmAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    public function getRetiradoEmAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    static public function retirar($idselo)
    {
        $SeloInstrumento = SeloInstrumento::where('idselo',$idselo)->first();
        $SeloInstrumento->retirado_em = Carbon::now()->toDateTimeString();
        return ($SeloInstrumento->save())?$SeloInstrumento:0;
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }
    public function selo()
    {
        return $this->belongsTo('App\Selo', 'idselo');
    }
    // ************************** HASMANY **********************************
}
