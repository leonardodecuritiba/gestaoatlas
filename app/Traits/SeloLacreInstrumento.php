<?php

namespace App\Traits;

use App\Helpers\DataHelper;
use Carbon\Carbon;

trait SeloLacreInstrumento
{

    public function tirar($id, $key)
    {
        $Data = self::where($key, $id)->first();
        return $Data->update(['retirado_em' => Carbon::now()->toDateTimeString()]);
    }

    public function getAfixadoEm()
    {
        return ($this->attributes['afixado_em'] != NULL) ? DataHelper::getPrettyDateTime($this->attributes['afixado_em']) : '-';
    }

    public function getRetiradoEm()
    {
        return ($this->attributes['retirado_em'] != NULL) ? DataHelper::getPrettyDateTime($this->attributes['retirado_em']) : '-';
    }


	public function getUnsetText() {
		$aparelho = $this->aparelho_manutencao_unset;

		return ( $aparelho == null ) ? $this->getRetiradoEm() : $this->getRetiradoEm() . " (" . $aparelho->idordem_servico . ")";
	}

	public function getSetText() {
		$aparelho = $this->aparelho_manutencao_set;

		return ( $aparelho == null ) ? $this->getAfixadoEm() : $this->getAfixadoEm() . " (" . $aparelho->idordem_servico . ")";
	}

	public function aparelho_manutencao_set() {
		return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_set', 'idaparelho_manutencao' );
	}

	public function aparelho_manutencao_unset() {
		return $this->belongsTo( 'App\AparelhoManutencao', 'idaparelho_unset', 'idaparelho_manutencao' );
	}


	public function instrumento() {
		return $this->belongsTo( 'App\Instrumento', 'idinstrumento' );
	}
}