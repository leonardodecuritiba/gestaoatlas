<?php

namespace App\Traits;


use App\Helpers\ImageHelper;

trait InstrumentsTrait {

	//===================== BASE =====================
	public function getDetalhesBase() {
		return $this->base->getDetalhesBase();
	}

	public function getMarcaModelo() {
		return $this->base->getMarcaModelo();
	}

	public function getFoto() {
		return $this->base->getFoto();
	}

	public function getThumbFoto() {
		return $this->base->getThumbFoto();
	}
	//===================== /BASE ====================

	//===================== /etiqueta_identificacao ====================
	public function getEtiquetas() {
		return json_encode( [
			'etiqueta_identificacao' => $this->getEtiquetaIdentificacao(),
			'etiqueta_inventario'    => $this->getEtiquetaInventario(),
		] );
	}

	public function getEtiquetaIdentificacao() {
		return ( $this->etiqueta_identificacao != null ) ? ImageHelper::getFullPath( 'instrumentos' ) . DIRECTORY_SEPARATOR . $this->etiqueta_identificacao : asset( 'imgs/cogs.png' );
	}

	public function getEtiquetaInventario() {
		return ( $this->etiqueta_inventario != null ) ? ImageHelper::getFullPath( 'instrumentos' ) . DIRECTORY_SEPARATOR . $this->etiqueta_inventario : asset( 'imgs/cogs.png' );
	}

	public function getThumbEtiquetaIdentificacao() {
		return ( $this->etiqueta_identificacao != null ) ? ImageHelper::getFullThumbPath( 'instrumentos' ) . DIRECTORY_SEPARATOR . $this->etiqueta_identificacao : asset( 'imgs/cogs.png' );
	}

	public function getThumbEtiquetaInventario() {
		return ( $this->etiqueta_inventario != null ) ? ImageHelper::getFullThumbPath( 'instrumentos' ) . DIRECTORY_SEPARATOR . $this->etiqueta_inventario : asset( 'imgs/cogs.png' );
	}

	public function base() {
		return $this->belongsTo( 'App\Models\Instrumentos\InstrumentoBase', 'idbase' );
	}

//	public function protection()
//	{
//		return $this->belongsTo('App\Models\Instrumentos\InstrumentoSetor', 'idprotection');
//	}

}