<?php

namespace App\Models\Inputs;

use App\Colaborador;
use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
	use SoftDeletes;
	const _API_URL_BASE_ = 'http://fipeapi.appspot.com/api';
	const _API_VERSION_ = '1';
	static public $_API_OPTIONS_ = array(
		'Carros', 'Motos', 'Caminhões'
	);

	static public $_API_OPTIONS_VALUE_ = array(
		'carros', 'motos', 'caminhoes'
	);

	public $timestamps = true;
	protected $fillable = [
		'tipo',
		'id_api',
		'ano_modelo',
		'marca',
		'name',
		'veiculo',
		'preco',
		'combustivel',
		'referencia',
		'fipe_codigo',
		'key',

		'renavam',
		'plate',
		'km',
		'tires',
		'oil',
		'filter',
		'wash',
	];

	// ************************** RELASHIONSHIP **********************************
	static public function consultar($ref, $debug, $type) {
		if ($debug) {
			$_SERVER_ = self::_URL_HOMOLOGACAO_;
			$_TOKEN_  = self::_TOKEN_HOMOLOGACAO_;
		} else {
			$_SERVER_ = self::_URL_PRODUCAO_;
			$_TOKEN_  = self::_TOKEN_PRODUCAO_;
		}

		if (!strcmp($type, 'nfe')) {
			$URL = $_SERVER_ . "/nfe2/consultar?ref=" . $ref . "&token=" . $_TOKEN_;
		} else {
			$URL = $_SERVER_ . "/nfse/" . $ref . "?token=" . $_TOKEN_;
		}
//        return $URL;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array());


		$body   = curl_exec($ch);
		$result = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$body = Yaml::parse($body);

//        return $body;
//        erros: [
//{
//    codigo: "nfse_nao_encontrada",
//mensagem: "Nota fiscal não encontrada"
//}
//]
		if (isset($body['status'])) {
			if (($body['status'] != 'processando_autorizacao') && (!isset($body['uri']))) {
				$body['uri'] = $_SERVER_ . $body['caminho_danfe'];
			}
		}

		$retorno = [
			'url_focus' => $URL,
			'profile'   => ($debug) ? 'Homologação' : 'Produção',
			'ref'       => $ref,
			'type'      => $type,
			'url'       => $_SERVER_,
			'body'      => $body,
			'status'    => $result,
		];

		return ($retorno);
	}

	static public function getJsonMarcas($tipo = 1) {
		$tipo   = self::$_API_OPTIONS_VALUE_[$tipo - 1];
		$params = $tipo . "/" . "marcas.json";

		return self::sendRequestApi($params);
	}

	static public function sendRequestApi($params) {
		$URL = self::_API_URL_BASE_ . "/" . self::_API_VERSION_ . "/" . $params;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array());

		$body = curl_exec($ch);
		curl_close($ch);

		return $body;
	}

	static public function getJsonVeiculos($tipo = "carros", $idmarca) {
		//http://fipeapi.appspot.com/api/1/carros/veiculos/21.json
		$params = $tipo . "/veiculos/" . $idmarca . ".json";

		return self::sendRequestApi($params);
	}

	static public function getJsonModelos($tipo = "carros", $idmarca, $idveiculo) {
		//http://fipeapi.appspot.com/api/1/carros/veiculo/21/4828.json
		$params = $tipo . "/veiculo/" . $idmarca . "/" . $idveiculo . ".json";

		return self::sendRequestApi($params);
	}

	static public function getJsonVeiculo($tipo = "carros", $idmarca, $idveiculo, $modelo) {
		//GET: http://fipeapi.appspot.com/api/1/carros/veiculo/21/4828/2013-1.json
		$params = $tipo . "/veiculo/" . $idmarca . "/" . $idveiculo . "/" . $modelo . ".json";

		return self::sendRequestApi($params);
	}

	public function getEscolhido() {
		return $this->attributes['veiculo'] . ' - ' . $this->attributes['combustivel'] . ' (' . $this->attributes['ano_modelo'] . ')';
	}

	public function setPlateAttribute($value) {
		return $this->attributes['plate'] = DataHelper::getOnlyNumbersLetters($value);
	}

	public function getPlateAttribute($value) {
		return DataHelper::mask($value, '###-####');
	}

	public function setWashAttribute($value) {
		return $this->attributes['wash'] = DataHelper::setDate($value);
	}

	public function getWashAttribute($value) {
		return DataHelper::getPrettyDate($value);
	}

	// ************************** RELASHIONSHIP **********************************
	public function collaborator() {
		return $this->belongsToMany( Colaborador::class, 'vehicle_stocks', 'idvehicle', 'idcolaborador' );
	}
	/*
		public function getMeasureAttribute($value)
		{
			return DataHelper::getFloat2Real($value);
		}

		public function getCostAttribute($value)
		{
			return DataHelper::getFloat2Real($value);
		}

		public function getCost()
		{
			return DataHelper::getFloat2RealMoeda($this->attributes['cost']);
		}
		public function getBrandText()
		{
			return $this->brand->description;
		}
		public function getCategoryText()
		{
			return $this->category->description;
		}
		public function getUnityText()
		{
			return $this->unity->codigo;
		}


		public function brand()
		{
			return $this->belongsTo('App\Models\Commons\Brand', 'idbrand');
		}

		public function category()
		{
			return $this->belongsTo('App\Models\Inputs\Tool\ToolCategory', 'idcategory');
		}

		public function unity()
		{
			return $this->belongsTo('App\Unidade', 'idunit','idunidade');
		}
		*/

}
