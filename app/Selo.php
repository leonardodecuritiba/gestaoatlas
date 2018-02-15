<?php

namespace App;

use App\Helpers\DataHelper;
use App\Traits\CommonTrait;
use App\Traits\SeloLacre;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Selo extends Model
{
    use SeloLacre;
    use SoftDeletes;
    use CommonTrait;
    public $timestamps = true;
    protected $table = 'selos';
    protected $primaryKey = 'idselo';
    protected $fillable = [
        'idtecnico',
        'numeracao',
        'numeracao_externa',
        'externo',
        'used',
        'declared',
    ];


    // ******************** FUNCTIONS ******************************

    static public function set_declared($id)
    {
        return self::findOrFail($id)
            ->update(['declared' => Carbon::now()]);
    }
    static public function set_undeclared($id)
    {
        return self::findOrFail($id)
            ->update(['declared' => NULL]);
    }

    static public function assign($data)
    {
        return self::whereIn('idselo', $data['valores'])
            ->update(['idtecnico' => $data['idtecnico']]);
    }

    static public function selo_exists($numeracao)
    {
        return (self::where('numeracao', $numeracao)->exists() > 0);
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
	    return (($this->attributes['numeracao_externa'] != NULL) && ($this->attributes['numeracao'] == NULL));
    }

	public function getOrdemServicoID()
	{
		$selo_i = $this->selo_instrumento;
		if($selo_i!=NULL){
			return $selo_i->aparelho_set->idordem_servico;
		}
		return $selo_i;
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

    static public function getAllListagem($filters)
    {
    	$self = new self();
	    $query = $self->newQuery();
	    if($filters['status']<2){
		    $query->where('used', $filters['status']);
	    }
	    if(isset($filters['numeracao'])){
		    $filters['numeracao'] = DataHelper::getOnlyNumbers($filters['numeracao']);
		    $query->where('numeracao', 'like','%' .$filters['numeracao']. '%');
	    }
    	if(isset($filters['origem'])){
    		if($filters['origem']>0){
			    $query->where('idtecnico', $filters['origem']);
		    }
	    }
	    if(isset($filters['cnpj'])){
		    if($filters['cnpj']!='') {
			    $filters['cnpj'] = DataHelper::getOnlyNumbers( $filters['cnpj'] );
			    $ids             = DB::table( 'selo_instrumentos' )
			                         ->join( 'aparelho_manutencaos', function ( $join ) {
				                         $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' )
				                              ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' );
			                         } )
			                         ->join( 'ordem_servicos', 'ordem_servicos.idordem_servico', '=', 'aparelho_manutencaos.idordem_servico' )
			                         ->join( 'clientes', 'clientes.idcliente', '=', 'ordem_servicos.idcliente' )
			                         ->join( 'pjuridicas', 'pjuridicas.idpjuridica', '=', 'clientes.idpjuridica' )
			                         ->select( 'selo_instrumentos.idselo' )
			                         ->where( 'pjuridicas.cnpj', 'like', '%' . $filters['cnpj'] . '%' )
			                         ->pluck( 'idselo' );
			    $query->whereIn( 'idselo', $ids );
		    }
	    }

	    if(isset($filters['idordem_servico'])){
		    if($filters['idordem_servico']!='') {
			    $ids             = DB::table( 'selo_instrumentos' )
			                         ->join( 'aparelho_manutencaos', function ( $join ) {
				                         $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' )
				                              ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' );
			                         })
				                     ->where('aparelho_manutencaos.idordem_servico',$filters['idordem_servico'])
			                         ->pluck( 'idselo' );
			    $query->whereIn( 'idselo', $ids );
		    }
	    }

	    if(isset($filters['numero_serie'])){
		    if($filters['numero_serie']!='') {
			    $filters['numero_serie'] = DataHelper::getOnlyNumbers($filters['numero_serie']);
			    $ids             = DB::table( 'selo_instrumentos' )
			                         ->join( 'aparelho_manutencaos', function ( $join ) {
				                         $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' )
				                              ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' );
			                         })
			                         ->join('instrumentos' , 'aparelho_manutencaos.idinstrumento', '=', 'instrumentos.idinstrumento')
			                         ->where('instrumentos.numero_serie', 'like','%' .$filters['numero_serie']. '%')
			                         ->pluck( 'idselo' );
			    $query->whereIn( 'idselo', $ids );
		    }
	    }

	    if(isset($filters['inventario'])){
		    if($filters['inventario']!='') {
			    $filters['inventario'] = DataHelper::getOnlyNumbers($filters['inventario']);
			    $ids             = DB::table( 'selo_instrumentos' )
			                         ->join( 'aparelho_manutencaos', function ( $join ) {
				                         $join->on( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' )
				                              ->orOn( 'aparelho_manutencaos.idaparelho_manutencao', '=', 'selo_instrumentos.idaparelho_set' );
			                         })
			                         ->join('instrumentos' , 'aparelho_manutencaos.idinstrumento', '=', 'instrumentos.idinstrumento')
			                         ->where('instrumentos.inventario', 'like','%' .$filters['inventario']. '%')
			                         ->pluck( 'idselo' );
			    $query->whereIn( 'idselo', $ids );
		    }
	    }

        return $query;
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
