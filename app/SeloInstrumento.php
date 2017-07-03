<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacreInstrumento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeloInstrumento extends Model
{
    use SeloLacreInstrumento;
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
        $Data = self::where('idselo', $idselo)->first();
        return $Data->update(['retirado_em' => Carbon::now()->toDateTimeString()]);
    }

    public function getNomeTecnico()
    {
        return $this->selo->getNomeTecnico();
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
