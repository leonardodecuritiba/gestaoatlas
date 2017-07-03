<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LacreInstrumento extends Model
{
    use SeloLacreInstrumento;
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

    static public function tirar($idslacres)
    {
        foreach($idslacres as $lacre){
            //Nesse caso, vamos atualizar o retirado_em
            $Data = self::where('idlacre', $lacre->id)->first();
            return $Data->update(['retirado_em' => Carbon::now()->toDateTimeString()]);
        }
        return 1;
    }

    public function getNomeTecnico()
    {
        return $this->lacre->getNomeTecnico();
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

    public function lacres()
    {
        return $this->belongsTo('App\Lacre', 'idlacre');
    }
    // ************************** HASMANY **********************************
}
