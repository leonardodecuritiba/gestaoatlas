<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\SeloLacre;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selo extends Model
{
    use SeloLacre;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'selos';
    protected $primaryKey = 'idselo';
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
        'used',
    ];


    // ******************** FUNCTIONS ******************************

    static public function selo_exists($numeracao)
    {
        return (self::where('numeracao', $numeracao)->count() > 0);
    }

    public function scopeNumeracao($query, $numeracao)
    {
        return $query->where('numeracao', 'like', '%' . $numeracao . '%')
            ->orWhere('numeracao_externa', 'like', '%' . $numeracao . '%');
    }

    public function getFormatedSelo()
    {
        if ($this->isExterno()) {
            $cod = $this->attributes['numeracao_externa'];
            return ($cod != NULL) ? $cod : '-';
        }
        $cod = $this->attributes['numeracao'];
        return ($cod != NULL) ? DataHelper::mask($cod, '##.###.###') : '-';

    }

    public function isExterno()
    {
        return ($this->attributes['numeracao'] == NULL);
    }

    public function getFormatedSeloDV()
    {
        if ($this->isExterno()) {
            $cod = $this->attributes['numeracao_externa'];
            return ($cod != NULL) ? $cod : '-';
        }
        $cod = ($this->attributes['numeracao'] != NULL) ? $this->attributes['numeracao'] . $this->getDV() : NULL;
        return ($cod != NULL) ? DataHelper::mask($cod, '##.###.###-#') : '-';
    }

    public function getDV()
    {
        return DataHelper::calculateModulo11($this->numeracao);
    }

    public function has_selo_instrumento()
    {
        return ($this->selo_instrumento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    public function selo_instrumento()
    {
        return $this->hasOne('App\SeloInstrumento', 'idselo');
    }

    // ********************** HASONE ********************************

    public function tecnico()
    {
        return $this->belongsTo('App\Tecnico', 'idtecnico');
    }
    // ************************** HASMANY **********************************
}
