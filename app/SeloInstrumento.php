<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeloInstrumento extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'selo_instrumentos';
    protected $primaryKey = 'idselo_instrumento';
    protected $fillable = [
        'idaparelho_manutencao',
        'idinstrumento',
        'idselo',
        'afixado_em',
        'retirado_em'
    ];



    // ******************** FUNCTIONS ******************************

    static public function retirar($idselo)
    {
        $SeloInstrumento = SeloInstrumento::where('idselo',$idselo)->first();
        $SeloInstrumento->retirado_em = Carbon::now()->toDateTimeString();
        return ($SeloInstrumento->save())?$SeloInstrumento:0;
    }

    public function getAfixadoEmAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

    public function getRetiradoEmAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function aparelho_manutencao()
    {
        return $this->belongsTo('App\AparelhoManutencao', 'idaparelho_manutencao');
    }
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
