<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AparelhoManutencao extends Model
{
    use SoftDeletes;
    protected $table = 'aparelho_manutencaos';
    protected $primaryKey = 'idaparelho_manutencao';
    public $timestamps = true;
    protected $fillable = [
        'idordem_servico',
        'idinstrumento',
        'idequipamento',
        'defeito',
        'solucao',
    ];

    // ******************** FUNCTIONS ******************************
    static public function check_equip_duplo($idordem_servico,$idinstrumento)
    {
        return parent::where('idordem_servico', $idordem_servico)
            ->where('idinstrumento', $idinstrumento)->count();
//        return $this->belongsTo('App\OrdemServico', 'idordem_servico');
    }
    public function has_servico_prestados()
    {
        return ($this->servico_prestados()->count() > 0);
    }
    public function has_pecas_utilizadas()
    {
        return ($this->pecas_utilizadas()->count() > 0);
    }
    public function has_kits_utilizados()
    {
        return ($this->kits_utilizados()->count() > 0);
    }
    public function has_instrumento()
    {
        return ($this->instrumento()->count() > 0);
    }
    public function has_equipamentos()
    {
        return ($this->equipamento()->count() > 0);
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function ordem_servico()
    {
        return $this->belongsTo('App\OrdemServico', 'idordem_servico');
    }

    public function instrumento()
    {
        return $this->belongsTo('App\Instrumento', 'idinstrumento');
    }
    public function equipamento()
    {
        return $this->belongsTo('App\Equipamento', 'idequipamento');
    }
    // ************************** HASMANY **********************************
    public function servico_prestados()
    {
        return $this->hasMany('App\ServicoPrestado', 'idaparelho_manutencao');
    }
    public function pecas_utilizadas()
    {
        return $this->hasMany('App\PecasUtilizadas', 'idaparelho_manutencao');
    }
    public function kits_utilizados()
    {
        return $this->hasMany('App\KitsUtilizados', 'idaparelho_manutencao');
    }
}
