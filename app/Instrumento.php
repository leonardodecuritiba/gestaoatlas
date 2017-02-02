<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
    public $timestamps = true;
    protected $table = 'instrumentos';
    protected $primaryKey = 'idinstrumento';
    protected $fillable = [
        'idcliente',
        'idmarca',
        'idcolaborador_criador',
        'idcolaborador_validador',
        'validated_at',
        'descricao',
        'modelo',
        'numero_serie',
        'inventario',
        'patrimonio',
        'ano',
        'portaria',
        'divisao',
        'capacidade',
        'ip',
        'endereco',
        'setor',
        'foto'
    ];

    // ******************** FUNCTIONS ****************************
    public function has_aparelho_manutencao()
    {
        return ($this->aparelho_manutencao()->count() > 0);
    }

    public function aparelho_manutencao()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idequipamento');
    }

    public function has_lacres_instrumentos()
    {
        return ($this->lacres_instrumentos()->count() > 0);
    }

    public function lacres_instrumentos()
    {
        return $this->hasMany('App\LacreInstrumento', 'idinstrumento', 'idinstrumento');
    }

    public function getFoto()
    {
        return ($this->foto != '') ? asset('uploads/' . $this->table . '/' . $this->foto) : asset('imgs/cogs.png');
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function getFotoThumb()
    {
        return ($this->foto != '') ? asset('uploads/' . $this->table . '/thumb_' . $this->foto) : asset('imgs/cogs.png');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca', 'idmarca');
    }

    // ************************** HASMANY **********************************

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    public function selo_afixado_numeracao()
    {
        $selo = $this->selo_afixado();
        return ($selo != NULL) ? $selo->numeracao : $selo;
    }

    public function selo_afixado()
    {
        if($this->has_selo_instrumentos()){
            return $this->hasMany('App\SeloInstrumento', 'idinstrumento', 'idinstrumento')
                ->where('retirado_em',NULL)->first()->selo;
        } else {
            return NULL;
        }
    }

    public function has_selo_instrumentos()
    {
        return ($this->selo_instrumentos()->count() > 0);
    }

    public function selo_instrumentos()
    {
        return $this->hasMany('App\SeloInstrumento', 'idinstrumento', 'idinstrumento');
    }

    public function lacres_afixados()
    {
        return $this->hasMany('App\LacreInstrumento', 'idinstrumento', 'idinstrumento')
            ->where('retirado_em',NULL);
    }
    public function lacres_afixados_valores()
    {
        $retorno = NULL;
        foreach($this->lacres_afixados as $lacre){
            $retorno[]=$lacre->lacre->numeracao;
        }
        return (count($retorno) > 1) ? implode('; ', $retorno) : $retorno[0];
    }

    public function selo_instrumento_cliente()
    {
        $selo = $this->selo_afixado();
        if($selo != NULL){

            $selo = json_encode([
                'numeracao'     => $selo->numeracao,
                'tecnico'       => $selo->tecnico->colaborador->nome,
                'afixado_em'    => $selo->selo_instrumento->afixado_em
            ]);
        }

        return $selo;
    }

    public function lacres_instrumento_cliente()
    {
        $retorno = NULL;
        foreach($this->lacres_afixados as $lacre){
            $lacre = $lacre->lacre;
            $retorno[]=[
                'numeracao'     => $lacre->numeracao,
                'tecnico'       => $lacre->tecnico->colaborador->nome,
                'afixado_em'    => $lacre->lacre_instrumento->afixado_em
            ];
        }
        return json_encode($retorno);
    }


}
