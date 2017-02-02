<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LacreInstrumento extends Model
{
    public $timestamps = true;
    protected $table = 'lacre_instrumentos';
    protected $primaryKey = 'idlacre_instrumento';
    protected $fillable = [
        'idaparelho_manutencao',
        'idinstrumento',
        'idlacre',
        'afixado_em',
        'retirado_em'
    ];



    // ******************** FUNCTIONS ******************************

    static public function retirar($idslacres)
    {
        foreach($idslacres as $lacre){
            //Nesse caso, vamos atualizar o retirado_em
            $LacreInstrumento = LacreInstrumento::where('idlacre',$lacre->id)->first();
            $LacreInstrumento->retirado_em = Carbon::now()->toDateTimeString();
            $LacreInstrumento->save();
        }
        return 1;
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
    public function lacre()
    {
        return $this->belongsTo('App\Lacre', 'idlacre');
    }
    // ************************** HASMANY **********************************
}
