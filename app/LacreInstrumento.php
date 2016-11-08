<?php

namespace App;

use App\Helpers\DataHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LacreInstrumento extends Model
{
    use SoftDeletes;
    protected $table = 'lacre_instrumentos';
    protected $primaryKey = 'idlacre_instrumento';
    public $timestamps = true;
    protected $fillable = [
        'idinstrumento',
        'idlacre',
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
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
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
