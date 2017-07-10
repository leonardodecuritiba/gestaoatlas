<?php

namespace App;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;

class Instrumento extends Model
{
    public $timestamps = true;
    protected $table = 'instrumentos';
    protected $primaryKey = 'idinstrumento';
    protected $fillable = [
        'idcliente',
        'numero_serie',
        'inventario',
        'patrimonio',
        'ano',
        'ip',
        'endereco',
        'idbase',
        'idsetor',
        'idprotecao',

        'etiqueta_identificacao',
        'etiqueta_inventario'
//        'idmarca',-
//        'idcolaborador_criador',-
//        'idcolaborador_validador',-
//        'validated_at',-
//        'descricao',-
//        'foto'-
//        'modelo',-
//        'portaria',-
//        'divisao',-
//        'capacidade',-
//        'setor',-
    ];

    // ******************** FUNCTIONS ****************************
    public function getDetalhesBase()
    {
        return $this->base->getDetalhesBase();
    }

    public function getMarcaModelo()
    {
        return $this->base->getMarcaModelo();
    }

    public function has_aparelho_manutencao()
    {
        return ($this->aparelho_manutencao()->count() > 0);
    }

    public function aparelho_manutencao()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idequipamento');
    }

    public function getFoto()
    {
        return $this->base->getFoto();
    }

    public function getThumbFoto()
    {
        return $this->base->getThumbFoto();
    }

    public function getEtiquetas()
    {
        return json_encode([
            'etiqueta_identificacao' => $this->getEtiquetaIdentificacao(),
            'etiqueta_inventario' => $this->getEtiquetaInventario(),
        ]);
    }

    public function getEtiquetaIdentificacao()
    {
        return ($this->etiqueta_identificacao != NULL) ? ImageHelper::getFullPath('instrumentos') . $this->etiqueta_identificacao : $this->etiqueta_identificacao;
    }

    public function getEtiquetaInventario()
    {
        return ($this->etiqueta_inventario != NULL) ? ImageHelper::getFullPath('instrumentos') . $this->etiqueta_inventario : $this->etiqueta_inventario;
    }

    public function getThumbEtiquetaIdentificacao()
    {
        return ($this->etiqueta_identificacao != NULL) ? ImageHelper::getFullThumbPath('instrumentos') . $this->etiqueta_identificacao : $this->etiqueta_identificacao;
    }

    public function getThumbEtiquetaInventario()
    {
        return ($this->etiqueta_inventario != NULL) ? ImageHelper::getFullThumbPath('instrumentos') . $this->etiqueta_inventario : $this->etiqueta_inventario;
    }

    public function base()
    {
        return $this->belongsTo('App\Models\Instrumentos\InstrumentoBase', 'idbase');
    }

    public function setor()
    {
        return $this->belongsTo('App\Models\Instrumentos\InstrumentoSetor', 'idsetor');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    //SELOS --------

    public function numeracao_selo_afixado()
    {
        $selo = $this->selo_afixado();
        return ($selo != NULL) ? $selo->getFormatedSeloDV() : '-';
    }

    public function selo_afixado()
    {
        if($this->has_selo_instrumentos()){
            $SeloInstrumento = $this->selo_instrumentos()->whereNull('retirado_em')->first();
            return ($SeloInstrumento != NULL) ? $SeloInstrumento->selo : $SeloInstrumento;
        }
        return NULL;
    }

    public function has_selo_instrumentos()
    {
        return ($this->selo_instrumentos()->count() > 0);
    }

    public function selo_instrumentos()
    {
        return $this->hasMany('App\SeloInstrumento', 'idinstrumento', 'idinstrumento')->orderBy('retirado_em');
    }

    public function numeracao_selo_retirado()
    {
        $selo = $this->selo_retirado();
        return ($selo != NULL) ? $selo->getFormatedSeloDV() : '-';
    }

    //LACRES --------

    public function selo_retirado()
    {
        if ($this->has_selo_instrumentos()) {
            $SeloInstrumento = $this->selo_instrumentos()->whereNotNull('retirado_em')->first();
            return ($SeloInstrumento != NULL) ? $SeloInstrumento->selo : $SeloInstrumento;
        }
        return NULL;
    }

    public function numeracao_lacres_afixados()
    {
        $lacresInstrumento = $this->lacres_afixados();
        $numeracao = NULL;
        if ($lacresInstrumento != NULL) {
            foreach ($lacresInstrumento as $li) {
                $lacre = $li->lacre;
                $numeracao[] = ($lacre->numeracao != NULL) ? $lacre->numeracao : $lacre->numeracao_externa;
            }
        }
        return ($numeracao != NULL) ? implode('; ', $numeracao) : '-';
    }

    public function lacres_afixados()
    {
        if ($this->has_lacres_instrumentos()) {
            $LacresInstrumento = $this->lacres_instrumentos()->whereNull('retirado_em')->get();
            return $LacresInstrumento;
        }
        return NULL;
    }

    public function has_lacres_instrumentos()
    {
        return ($this->lacres_instrumentos()->count() > 0);
    }

    public function lacres_instrumentos()
    {
        return $this->hasMany('App\LacreInstrumento', 'idinstrumento', 'idinstrumento')->orderBy('retirado_em');
    }

    // ************************** HASMANY **********************************

    public function numeracao_lacres_retirados()
    {
        $lacresInstrumento = $this->lacres_retirados();
        $numeracao = NULL;
        if ($lacresInstrumento != NULL) {
            foreach ($lacresInstrumento as $li) {
                $lacre = $li->lacre;
                $numeracao[] = ($lacre->numeracao != NULL) ? $lacre->numeracao : $lacre->numeracao_externa;
            }
        }
        return ($numeracao != NULL) ? implode('; ', $numeracao) : '-';
    }

    public function lacres_retirados()
    {
        if ($this->has_lacres_instrumentos()) {
            $LacresInstrumento = $this->lacres_instrumentos()->whereNotNull('retirado_em')->get();
            return $LacresInstrumento;
        }
        return NULL;
    }

    public function selo_instrumento_cliente()
    {
        $selosInstrumento = $this->selo_instrumentos;
        if ($selosInstrumento->count() > 0) {
            return $selosInstrumento->map(function ($s) {
                $s->nome_tecnico = $s->selo->getNomeTecnico();
                $s->retirado_em = $s->getRetiradoEm();
                $s->afixado_em = $s->getAfixadoEm();
                $s->numeracao_dv = $s->selo->getFormatedSeloDV();
                return $s;
            });
        }
        return NULL;
    }

    public function lacres_instrumento_cliente()
    {
        $lacresInstrumento = $this->lacres_instrumentos;
        if ($lacresInstrumento->count() > 0) {
            return $lacresInstrumento->map(function ($l) {
                $l->nome_tecnico = $l->lacre->getNomeTecnico();
                $l->retirado_em = $l->getRetiradoEm();
                $l->afixado_em = $l->getAfixadoEm();
                $l->numeracao = $l->lacre->getNumeracao();
                return $l;
            });
        }
        return NULL;
    }
}
