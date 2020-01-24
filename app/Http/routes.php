<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('billing/{id}', function ($id) {
    $Billing = \App\Models\Faturamento::find($id);
//        return $this->_BILLING_->getAllPecas();

    $item_n = 1;
    foreach ($Billing->getAllPecas() as $item) {
        $NfeItens[] = [
            "numero_item" => $item_n, //Número (índice) do item na nota fiscal, começando por 1. (obrigatório) Integer[1-3] Tag XML nItem
            "codigo_produto" => $item->idpeca, //Código interno do produto. Se não existir deve ser usado o CFOP no formato CFOP9999. (obrigatório) String[1-60] Tag XML cProd
//                    "codigo_barras_comercial" => $pecas_utilizada->peca->codigo_barras, //Código GTIN/EAN do produto. Integer[0,8,12,13,14] Tag XML cEAN
            "descricao" => $item->peca->descricao, //Descrição do produto. (obrigatório) String[1-120] Tag XML xProd
            "codigo_ncm" => $item->peca->peca_tributacao->ncm->codigo, //Código NCM do produto. Integer[2,8] Tag XML NCM
            "codigo_cest" => $item->peca->peca_tributacao->cest, //Código Especificador da Substituição Tributária. Integer[7] Tag XML CEST
//                    "codigo_ex_tipi " => **, //Código EX TIPI do produto. Integer[2-3] Tag XML EXTIPI
            "cfop" => $item->peca->peca_tributacao->cfop->numeracao, //CFOP do produto. (obrigatório) Integer[4] Tag XML CFOP
            "unidade_comercial" => $item->peca->unidade->codigo, //Unidade comercial. (obrigatório) String[1-6] Tag XML uCom


            "inclui_no_total" => "1", //Valor do item (valor_bruto) compõe valor total da NFe (valor_produtos)? (obrigatório) Tag XML indTot
            //Valores permitidos:
            // 0: não
            // 1: sim
            "icms_origem" => $item->peca->peca_tributacao->icms_origem, //Origem da mercadoria. (obrigatório)
            //Valores permitidos:
            //0: nacional
            //1: estrangeira (importação direta)
            //2: estrangeira (adquirida no mercado interno)
            //3: nacional com mais de 40% de conteúdo estrangeiro
            //4: nacional produzida através de processos produtivos básicos
            //5: nacional com menos de 40% de conteúdo estrangeiro
            //6: estrangeira (importação direta) sem produto nacional similar
            //7: estrangeira (adquirida no mercado interno) sem produto nacional similar
            "icms_situacao_tributaria" => $item->peca->peca_tributacao->icms_situacao_tributaria,
//                    "icms_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->icms_situacao_tributaria, //Situação tributária do ICMS. (obrigatório)
            //Valores permitidos
            //00: tributada integralmente
            //10: tributada e com cobrança do ICMS por substituição tributária
            //20: tributada com redução de base de cálculo
            //30: isenta ou não tributada e com cobrança do ICMS por substituição tributária
            //40: isenta
            //41: não tributada
            //50: suspensão
            //51: diferimento (a exigência do preenchimento das informações do ICMS diferido fica a critério de cada UF)
            //60: cobrado anteriormente por substituição tributária
            //70: tributada com redução de base de cálculo e com cobrança do ICMS por substituição tributária
            //90: outras (regime Normal)
            //101: tributada pelo Simples Nacional com permissão de crédito
            //102: tributada pelo Simples Nacional sem permissão de crédito
            //103: isenção do ICMS no Simples Nacional para faixa de receita bruta
            //201: tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por substituição tributária
            //202: tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por substituição tributária
            //203: isenção do ICMS nos Simples Nacional para faixa de receita bruta e com cobrança do ICMS por substituição tributária
            //300: imune
            //400: não tributada pelo Simples Nacional
            //500: ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação
            //900: outras (regime Simples Nacional)
            "pis_situacao_tributaria" => $item->peca->peca_tributacao->pis_situacao_tributaria,
//                    "pis_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->pis_situacao_tributaria, //Situação tributária do PIS.(obrigatório)
            //Valores permitidos
            //01: operação tributável: base de cálculo = valor da operação (alíquota normal - cumulativo/não cumulativo)
            //02: operação tributável: base de cálculo = valor da operação (alíquota diferenciada)
            //03: operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto
            //04: operação tributável: tributação monofásica (alíquota zero)
            //05: operação tributável: substituição tributária
            //06: operação tributável: alíquota zero
            //07: operação isenta da contribuição
            //08: operação sem incidência da contribuição
            //09: operação com suspensão da contribuição
            //49: outras operações de saída
            //50: operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno
            //51: operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno
            //52: operação com direito a crédito: vinculada exclusivamente a receita de exportação
            //53: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno
            //54: operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação
            //55: operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação
            //56: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
            //60: crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno
            //61: crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno
            //62: crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação
            //63: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno
            //64: crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação
            //65: crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação
            //66: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
            //67: crédito presumido: outras operações
            //70: operação de aquisição sem direito a crédito
            //71: operação de aquisição com isenção
            //72: operação de aquisição com suspensão
            //73: operação de aquisição a alíquota zero
            //74: operação de aquisição sem incidência da contribuição
            //75: operação de aquisição por substituição tributária
            //98: outras operações de entrada
            //99: outras operações
            "cofins_situacao_tributaria" => $item->peca->peca_tributacao->cofins_situacao_tributaria,
//                    "cofins_situacao_tributaria" => $pecas_utilizada->peca->peca_tributacao->cofins_situacao_tributaria //(obrigatório)
            //Valores permitidos
            //01: operação tributável: base de cálculo = valor da operação (alíquota normal - cumulativo/não cumulativo)
            //02: operação tributável: base de cálculo = valor da operação (alíquota diferenciada)
            //03: operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto
            //04: operação tributável: tributação monofásica (alíquota zero)
            //05: operação tributável: substituição tributária
            //06: operação tributável: alíquota zero
            //07: operação isenta da contribuição
            //08: operação sem incidência da contribuição
            //09: operação com suspensão da contribuição
            //49: outras operações de saída
            //50: operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno
            //51: operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno
            //52: operação com direito a crédito: vinculada exclusivamente a receita de exportação
            //53: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno
            //54: operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação
            //55: operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação
            //56: operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
            //60: crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno
            //61: crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno
            //62: crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação
            //63: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno
            //64: crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação
            //65: crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação
            //66: crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação
            //67: crédito presumido: outras operações
            //70: operação de aquisição sem direito a crédito
            //71: operação de aquisição com isenção
            //72: operação de aquisição com suspensão
            //73: operação de aquisição a alíquota zero
            //74: operação de aquisição sem incidência da contribuição
            //75: operação de aquisição por substituição tributária
            //98: outras operações de entrada
            //99: outras operações


            //MESMA COISA DO CAMPO quantidade_comercial E unidade_tributave

            "quantidade_comercial" => $item->quantidade_comercial, //Quantidade comercial. (obrigatório) Decimal[11.0-4] Tag XML qCom
            "valor_unitario_comercial" => $item->valor, //Valor unitário comercial. (obrigatório) Decimal[11.0-10] Tag XML vUnCom
            "valor_bruto" => $item->valor_bruto, //Valor bruto. Deve ser igual ao produto de Valor unitário comercial com quantidade comercial. Decimal[13.2] Tag XML vProd
//                    "codigo_barras_tributavel" => "**", //Código GTIN/EAN tributável. Integer[0,8,12,13,14] Tag XML cEANTrib
            "unidade_tributavel" => $item->peca->unidade->codigo, //Unidade tributável. (obrigatório) String[1-6] Tag XML uTrib
            "quantidade_tributavel" => $item->quantidade_comercial, //Quantidade tributável. (obrigatório) Decimal[11.0-4] Tag XML qTrib
            "valor_unitario_tributavel" => $item->valor, //Valor unitário tributável. (obrigatório) Decimal[11.0-10] Tag XML vUnTrib

            //O valor do frete vai ser incluído dentro do produto mesmo (compo é hoje) ou vai depender da O.S?
            "valor_frete" => $item->peca->peca_tributacao->valor_frete_float(), //Valor do frete. Decimal[13.2] Tag XML vFrete
            "valor_seguro" => $item->peca->peca_tributacao->valor_seguro_float(), //Valor do seguro. Decimal[13.2] Tag XML vSeg
            "valor_desconto" => $item->desconto_total, //Valor do desconto. Decimal[13.2] Tag XML vSeg
//                    "valor_outras_despesas" =>  ***, //Valor de outras despesas acessórias. Decimal[13.2] Tag XML vOutro
            "valor_ipi" => $item->peca->peca_tributacao->valor_ipi_float(),

        ];
        $item_n++;
    }

    return $NfeItens;
});


Route::get('export-equipamentos', 'EquipamentosController@exportarFile'); //EXPORTAR FORNECEDORES
Route::get('export-clientes', 'ClientesController@exportarFile'); //EXPORTAR FORNECEDORES
Route::get('export-fornecedores', 'FornecedoresController@exportarFile'); //EXPORTAR FORNECEDORES
Route::get('export-pecas', 'PecasController@exportarFile'); //EXPORTAR FORNECEDORES




//Route::get('importar_banco', 'Controller@importar');

Route::auth();

Route::group(['middleware' => ['auth']], function() {
	Route::get('/', 'HomeController@index')->name('busca.index');

	Route::get('/index', 'HomeController@index');
	Route::get('/home', 'HomeController@index');

	Route::resource('clientes', 'ClientesController');
	Route::patch('clientes/{cliente}', 'ClientesController@update')->name('clientes.update');
	Route::get('cliente/validar/{id}', 'ClientesController@validar')->name('cliente.validar');
	Route::get('clientes-exportar', 'ClientesController@exportar');

	Route::resource('instrumentos', 'InstrumentosController');
	Route::resource('fornecedores', 'FornecedoresController');
	Route::resource('pecas', 'PecasController');

	//Serviços
	Route::resource('servicos', 'ServicosController');

	Route::resource('colaboradores', 'ColaboradoresController');
	Route::post('pwd/{colaborador}/colaboradores', 'ColaboradoresController@upd_pass')->name('colaboradores.upd_pass');
	/*
	|--------------------------------------------------------------------------
	| Selos/Lacres Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource('selolacres', 'SeloLacreController');
	Route::post('lancar-selos', 'SeloLacreController@lancarSelos')->name('selolacres.lancar_selos');
	Route::post('lancar-lacres', 'SeloLacreController@lancarLacres')->name('selolacres.lancar_lacres');

	//REQUISIÇÕES
	Route::get('selolacres-requisicoes', 'SeloLacreController@listRequests')->name('selolacres.requisicoes');
	Route::get('selolacres-relatorios', 'SeloLacreController@getReports')->name('selolacres.relatorio');

	//REQUISIÇÕES - ADMIM
	Route::get('selolacres-listagem', 'SeloLacreController@index')->name('selolacres.listagem');
	Route::post('selolacres-repasse', 'SeloLacreController@postFormPassRequest')->name('selolacres.repasse');
	Route::post('selolacres-negar', 'SeloLacreController@deniedRequest')->name('selolacres.deny');

	//REQUISIÇÕES -TÉCNICO
	Route::get('selolacres-requisicao', 'SeloLacreController@getFormRequest')->name('selolacres.requisicao');
	Route::post('selolacres-requerer', 'SeloLacreController@postFormRequest')->name('selolacres.requerer');
	/*
	|--------------------------------------------------------------------------
	| Tools Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource('tools', 'Inputs\ToolsController');
	Route::get( 'tools-stocks', 'Inputs\ToolsController@stocks' )->name( 'tools.stocks' );
	Route::post('tools-stocks-store', 'Inputs\ToolsController@stocksStore' )->name( 'tools.stocksStore' );

	//REQUISIÇÕES - ADMIM
	Route::get('tools-requisicoes', 'Inputs\ToolsController@listRequests')->name('tools.requisicoes');
	Route::post('tools-repasse', 'Inputs\ToolsController@postFormPassRequest')->name('tools.repasse');
	Route::post('tools-negar', 'Inputs\ToolsController@deniedRequest')->name('tools.deny');

	//REQUISIÇÕES -TÉCNICO
	Route::get('tools-requisicao', 'Inputs\ToolsController@getFormRequest')->name('tools.requisicao');
	Route::post('tools-requerer', 'Inputs\ToolsController@postFormRequest')->name('tools.requerer');
	/*
	|--------------------------------------------------------------------------
	| Tools Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	//	Route::resource('parts', 'Inputs\ToolsController');
	Route::get( 'parts-stocks', 'Inputs\PartsController@stocks' )->name( 'parts.stocks' );
	Route::post('parts-stocks-store', 'Inputs\PartsController@stocksStore' )->name( 'parts.stocksStore' );

	//REQUISIÇÕES - ADMIM
	Route::get('parts-requisicoes', 'Inputs\PartsController@listRequests')->name('parts.requisicoes');
	Route::post('parts-repasse', 'Inputs\PartsController@postFormPassRequest')->name('parts.repasse');
	Route::post('parts-negar', 'Inputs\PartsController@deniedRequest')->name('parts.deny');

	//REQUISIÇÕES -TÉCNICO
	Route::get('parts-requisicao', 'Inputs\PartsController@getFormRequest')->name('parts.requisicao');
	Route::post('parts-requerer', 'Inputs\PartsController@postFormRequest')->name('parts.requerer');

	/*
	|--------------------------------------------------------------------------
	| Pattern Routes
	|--------------------------------------------------------------------------
	|
	|
	*/

	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource('patterns', 'Inputs\PatternsController');
	Route::get( 'patterns-stocks', 'Inputs\PatternsController@stocks' )->name( 'patterns.stocks' );
	Route::post( 'patterns-stocks-store', 'Inputs\PatternsController@stocksStore' )->name( 'patterns.stocksStore' );

	//REQUISIÇÕES - ADMIM
	Route::get('patterns-requisicoes', 'Inputs\PatternsController@listRequests')->name('patterns.requisicoes');
	Route::post('patterns-repasse', 'Inputs\PatternsController@postFormPassRequest')->name('patterns.repasse');
	Route::post('patterns-negar', 'Inputs\PatternsController@deniedRequest')->name('patterns.deny');

	//REQUISIÇÕES -TÉCNICO
	Route::get('patterns-requisicao', 'Inputs\PatternsController@getFormRequest')->name('patterns.requisicao');
	Route::post('patterns-requerer', 'Inputs\PatternsController@postFormRequest')->name('patterns.requerer');
	/*
	|--------------------------------------------------------------------------
	| Pattern Routes
	|--------------------------------------------------------------------------
	|
	|
	*/

	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource('parts', 'Inputs\PartsController');
	Route::get( 'parts-stocks', 'Inputs\PartsController@stocks' )->name( 'parts.stocks' );
	Route::post('parts-stocks-store', 'Inputs\PartsController@stocksStore' )->name( 'parts.stocksStore' );

	//REQUISIÇÕES - ADMIM
	Route::get('parts-requisicoes', 'Inputs\PartsController@listRequests')->name('parts.requisicoes');
	Route::post('parts-repasse', 'Inputs\PartsController@postFormPassRequest')->name('parts.repasse');
	Route::post('parts-negar', 'Inputs\PartsController@deniedRequest')->name('parts.deny');

	//REQUISIÇÕES -TÉCNICO
	Route::get('parts-requisicao', 'Inputs\PartsController@getFormRequest')->name('parts.requisicao');
	Route::post('parts-requerer', 'Inputs\PartsController@postFormRequest')->name('parts.requerer');
	/*
	|--------------------------------------------------------------------------
	| Voids Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource( 'voids', 'Inputs\Voids\VoidsController' );
	//	Route::get( 'tools-stocks', 'Inputs\ToolsController@stocks' )->name( 'tools.stocks' );
	//	Route::post('tools-stocks-store', 'Inputs\ToolsController@stocksStore' )->name( 'tools.stocksStore' );
	//Admin
	//    Route::get('selolacres-listagem', 'SeloLacreController@index')->name('selolacres.listagem');

	/*
	|--------------------------------------------------------------------------
	| Vehicles Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	//    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
	//    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

	Route::resource('vehicles', 'Inputs\VehiclesController');
	Route::get('vehicles-requisicoes', 'Inputs\VehiclesController@listRequests')->name('vehicles.requisicoes');
	//Admin
	//    Route::get('selolacres-listagem', 'SeloLacreController@index')->name('selolacres.listagem');
	Route::get('getJsonMarcas', 'AjaxController@consulta_veiculos_marcas')->name('getJsonMarcas');
	Route::get('getJsonVeiculos', 'AjaxController@consulta_veiculos_veiculos')->name('getJsonVeiculos');
	Route::get('getJsonModelo', 'AjaxController@consulta_veiculos_modelo')->name('getJsonModelo');
	Route::get('getJsonVeiculo', 'AjaxController@consulta_veiculos_veiculo')->name('getJsonVeiculo');

	/*
	|--------------------------------------------------------------------------
	| Instruments Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	Route::resource( 'instruments', 'Inputs\InstrumentsController' );
	Route::get( 'instruments-requisicoes', 'Inputs\InstrumentsController@listRequests' )->name( 'instruments.requisicoes' );
	//Admin
	//    Route::get('selolacres-listagem', 'SeloLacreController@index')->name('selolacres.listagem');

	/*
	|--------------------------------------------------------------------------
	| Instruments Routes
	|--------------------------------------------------------------------------
	|
	|
	*/
	Route::resource( 'budgets', 'Budgets\BudgetsController' );
    Route::get('budgets/create', 'Budgets\BudgetsController@create')->name('budgets.create');

	Route::match(['post','get'], 'budgets-new/select', 'Budgets\BudgetsController@select' )->name('budgets.select');

	Route::get( 'budgets-open/{client_id}', 'Budgets\BudgetsController@open' )->name('budgets.open');

	Route::get( 'budgets-summary/{id}', 'Budgets\BudgetsController@summary' )->name('budgets.summary');

	Route::get( 'budgets-print/{id}', 'Budgets\BudgetsController@_print' )->name('budgets.print');

	Route::get( 'budgets-send/{id}', 'Budgets\BudgetsController@send' )->name('budgets.send');

	Route::post( 'budgets-save/{id}', 'Budgets\BudgetsController@save' )->name('budgets.save');

	Route::post( 'budgets-close/{id}', 'Budgets\BudgetsController@close' )->name('budgets.close');

	Route::get( 'budgets-reopen/{id}', 'Budgets\BudgetsController@reopen' )->name('budgets.reopen');
	/*
	|--------------------------------------------------------------------------
	| BudgetParts Routes
	|--------------------------------------------------------------------------
	|
	|
	*/

	Route::resource( 'budget_parts', 'Budgets\BudgetPartsController' );
	/*
	|--------------------------------------------------------------------------
	| BudgetParts Routes
	|--------------------------------------------------------------------------
	|
	|
	*/

	Route::resource( 'budget_services', 'Budgets\BudgetServicesController' );

});
/*
|--------------------------------------------------------------------------
| Equipments Routes
|--------------------------------------------------------------------------
|
|
*/
Route::resource( 'equipments', 'Inputs\EquipmentsController' );
Route::get( 'equipments-requisicoes', 'Inputs\EquipmentsController@listRequests' )->name( 'equipments.requisicoes' );
//Admin
//    Route::get('selolacres-listagem', 'SeloLacreController@index')->name('selolacres.listagem');


Route::group(['middleware' => ['auth']], function () {
    //    Route::get('/', 'AdminController@welcome');
    //    Route::get('/manage', ['middleware' => ['permission:manage-admins'], 'uses' => 'AdminController@manageAdmins']);
    //Ajustes
    //Clientes
    Route::resource('segmentos', 'SegmentosController');
    Route::resource('equipamentos', 'EquipamentosController');
    Route::resource('regioes', 'RegioesController');
    Route::resource('segmentos_fornecedores', 'SegmentosFornecedoresController');

    //Peças/Produtos
    Route::resource('grupos', 'GruposController');
    Route::resource('marcas', 'MarcasController');
    Route::resource('unidades', 'UnidadesController');
    Route::resource('tabela_precos', 'TabelaPrecosController');

    //Tributação
    Route::resource('formas_pagamentos', 'FormasPagamentosController');
//            Route::resource('categoria_tributacao', 'CategoriaTributacaoController');
//              Route::resource('origem_tributacao',    'OrigemTributacaoController');
    Route::resource('cst', 'CstController');
    Route::resource('cfop', 'CfopController');
    Route::resource('ncm', 'NcmController');
    Route::resource('natureza_operacao', 'NaturezaOperacaoController');
    Route::match(['get', 'post'], 'importar/ncm/', 'NcmController@importar')->name('ncm.importar');
    Route::post('importar/store/ncm/', 'NcmController@storeImportar')->name('ncm.storeImportar');

    //Ajustes
    Route::resource('ajustes', 'AjustesController');


    //Kits
    Route::resource('kits', 'KitsController');
    Route::post('pecakit_remover/kits/{id}', 'KitsController@pecaKitDestroy')->name('kits.pecakit.destroy');


    Route::get('getNcm', 'AjaxController@getNcm')->name('getNcm');
    Route::get('getSelosDisponiveis', 'AjaxController@getSelosDisponiveis')->name('getSelosDisponiveis');
    Route::get('getLacresDisponiveis', 'AjaxController@getLacresDisponiveis')->name('getLacresDisponiveis');
    Route::get('get_ajaxSelect2', 'AjaxController@ajaxSelect2')->name('get_ajaxSelect2');

    Route::get('busca/ordem_servicos', 'OrdemServicoController@buscaClientes')->name('ordem_servicos.busca');
    Route::resource('ordem_servicos', 'OrdemServicoController');
//    Route::get('ordem_servicos/listar/{situacao_ordem_servico}', 'OrdemServicoController@index')->name('ordem_servicos.index');
    Route::get('ordens-servicos/listar', 'OrdemServicoController@index')->name('ordem_servicos.index');
    Route::get('ordem-servicos-centro-custo/listar', 'OrdemServicoController@index_centro_custo')->name('ordem_servicos.index_centro_custo');

    Route::get('ordem-servicos-centro-custo', 'OrdemServicoController@show_centro_custo')->name('ordem_servicos.show_centro_custo');

    Route::get('ordem_servicos/abrir/{clienteid}', 'OrdemServicoController@abrir')->name('ordem_servicos.abrir'); //Abrir O.S
    Route::get('ordem_servicos/resumo/{idordem_servico}', 'OrdemServicoController@resumo')->name('ordem_servicos.resumo'); //Fechar (RESUMO) O.S
    Route::post('ordem_servicos/finalizar/{idordem_servico}', 'OrdemServicoController@finalizar')->name('ordem_servicos.finalizar'); //Fechar O.S

    Route::get('ordem_servicos/exportar/{idordem_servico}', 'OrdemServicoController@exportar')->name('ordem_servicos.exportar'); //Imprimir O.S
    Route::get('ordem_servicos/imprimir/{idordem_servico}', 'OrdemServicoController@imprimir')->name('ordem_servicos.imprimir'); //Imprimir O.S

    Route::get('ordem_servicos/encaminhar/{idordem_servico}', 'OrdemServicoController@encaminhar')->name('ordem_servicos.encaminhar'); //Imprimir O.S
    Route::get('ordem_servicos/destroy/{idordem_servico}', 'OrdemServicoController@destroy')->name('ordem_servicos.destroy'); //Imprimir O.S
    Route::get('ordem_servicos/cliente/{idcliente}', 'OrdemServicoController@get_ordem_servicos_cliente')->name('ordem_servicos.cliente');
    Route::get('ordem_servicos/por_colaborador/{idcolaborador}/{tipo}', 'OrdemServicoController@get_ordem_servicos_colaborador')->name('ordem_servicos.por_colaborador');
    Route::get('ordem_servicos/reabrir/{idordem_servico}', 'OrdemServicoController@reabrir')->name('ordem_servicos.reabrir');

    Route::get('busca/ordem_servicos/{idordem_servico}/instrumentos', 'OrdemServicoController@buscaInstrumentos')->name('ordem_servicos.instrumentos.busca');
    Route::post('adiciona/ordem_servicos/{idordem_servico}/{idinstrumento}/instrumentos', 'OrdemServicoController@adicionaInstrumento')->name('ordem_servicos.instrumentos.adiciona');
    Route::get('remove/ordem_servicos/{idaparelho_manutencao}/instrumento', 'OrdemServicoController@removeInstrumento')->name('ordem_servicos.instrumentos.remove');

//    Route::get('busca/ordem_servicos/{idordem_servico}/equipamentos', 'OrdemServicoController@buscaInstrumentos')->name('ordem_servicos.instrumentos.busca');
    Route::post('adiciona/ordem_servicos/{idordem_servico}/{idequipamento}/equipamentos', 'OrdemServicoController@adicionaEquipamento')->name('ordem_servicos.equipamentos.adiciona');
    Route::get('remove/ordem_servicos/{idaparelho_manutencao}/equipamento', 'OrdemServicoController@removeEquipamento')->name('ordem_servicos.equipamentos.remove');

    Route::post('aparelho_manutencao/{idaparelho_manutencao}/update', 'OrdemServicoController@updateAparelhoManutencao')->name('aparelho_manutencao.update');

    Route::post('aplicar_valores/ordem_servicos/{idordem_servico}', 'OrdemServicoController@aplicarValores')->name('ordem_servicos.aplicar_valores');

    // REMOVER AQUI
    Route::resource('servico_prestados', 'ServicosPrestadosController');
    Route::resource('pecas_utilizadas', 'PecasUtilizadasController');
    Route::resource('kits_utilizados', 'KitsUtilizadosController');
    //ATÉ AQUI
    Route::post('ordem_servicos/add_insumos/{idordem_servico}', 'OrdemServicoController@add_insumos')->name('ordem_servicos.add_insumos');


    Route::resource('frotas', 'FrotasController');

    //Route::get('consulta_cnpj', 'AjaxController@consulta_cnpj')->name('consulta_cnpj');
    Route::get('consulta_sintegra_sp', 'AjaxController@consulta_sintegra_sp')->name('consulta_sintegra_sp');
    Route::get('get_sintegra_params', 'AjaxController@consulta_params')->name('get_sintegra_params');
    Route::get('getAjaxDataByID', 'AjaxController@getAjaxDataByID')->name('getAjaxDataByID');

    //FECHAMENTOS
    Route::get('listar-fechamentos-parciais/{centro_custo}', 'FechamentoController@index')->name('fechamentos.index_parcial');
    Route::get('visualizar-fechamentos/{centro_custo}/{id}', 'FechamentoController@show')->name('fechamentos.show_parcial');

    Route::get('listar-pos-fechamentos', 'FechamentoController@index_pos_fechamento')->name('fechamentos.index_pos');
    Route::get('visualizar-pos-fechamentos/{centro_custo}/{id}', 'FechamentoController@show_pos_fechamento')->name('fechamentos.show_pos');
    Route::get('fechar-por-periodo', 'FechamentoController@indexFecharPeriodo')->name('fechamentos.periodo_index');
    Route::post('fechar-periodo', 'FechamentoController@fecharPeriodo')->name('fechamentos.fechar_periodo');


    //FATURAMENTOS
    Route::resource('faturamentos', 'FaturamentoController');
    Route::get('faturamentos/show_values/{id}', 'FaturamentoController@show_values');
    Route::get('faturamentos/remover/{id}', 'FaturamentoController@remover')->name('faturamentos.remover');
    Route::get('listar-faturamentos', 'FaturamentoController@index')->name('faturamentos.index');
    Route::get('faturamentos/fechar/{id}', 'FaturamentoController@fechar')->name('faturamentos.fechar');
    Route::get('faturar-pos/{centro_custo}/{id}', 'FaturamentoController@faturar_pos')->name('faturamentos.faturar_pos');
    Route::get('faturar/{centro_custo}/{id}', 'FaturamentoController@faturar')->name('faturamentos.faturar');

    //faturamento
    Route::get('gerar-faturamento/{id}', 'FaturamentoController@runByOrdemServicoID')->name('faturamento.gerar');
    Route::get('run-faturamento', 'FaturamentoController@run');


    //NOTAS FISCAIS
    Route::get('listar-notas-fiscais/{tipo}', 'NotasFiscaisController@index')->name('notas_fiscais.index');
    Route::post('enviar-nota-cliente/{idfaturamento}', 'NotasFiscaisController@enviar')->name('notas_fiscais.enviar');

    //RECEBIMENTOS
    Route::resource('recebimentos', 'RecebimentoController');

    //PARCELAS
    Route::post('parcela/baixar', 'RecebimentoController@baixarParcela')->name('parcelas.baixar');
    Route::get('parcela-verificar', 'RecebimentoController@run')->name('parcelas.run');

    Route::get('parcela/boleto/{idparcela}', 'ParcelaController@gerarBoleto')->name('parcelas.boleto');
    Route::get('parcela/estornar/{idparcela}', 'ParcelaController@estornar')->name('parcelas.estornar');


    Route::get('nf/{idfaturamento}/{debug}/{type}', 'FaturamentoController@sendNF')->name('faturamentos.nf.send');
    Route::post('cancel-nf/{idfaturamento}/{debug}/{type}', 'FaturamentoController@cancelNF')->name('faturamentos.nf.cancel');
    Route::get('resend-nf/{idfaturamento}/{debug}/{type}', 'FaturamentoController@resendNF')->name('faturamentos.nf.resend');
    Route::get('nf/consulta/{idfaturamento}/{debug}/{type}', 'FaturamentoController@getNF')->name('faturamentos.nf.get');


    //RELATÓRIOS
    Route::get('relatorios/ipem', 'RelatoriosController@ipem')->name('relatorios.ipem');
    Route::get('relatorios/declarar', 'AjaxController@changeSeloDeclare')->name('relatorios.ipem.declarar');
    Route::get('relatorios/ipem/imprimir', 'RelatoriosController@ipemPrint')->name('relatorios.ipem.print');


    //NOVOS INSTRUMENTOS
    Route::resource('instrumento_marcas', 'Instrumentos\InstrumentosMarcasController');
    Route::resource('instrumento_modelos', 'Instrumentos\InstrumentosModelosController');
    Route::resource('instrumento_setors', 'Instrumentos\InstrumentosSetorsController');
    Route::resource('instrumento_bases', 'Instrumentos\InstrumentosBasesController');

    //EXPORTAÇÃO
    Route::group(['prefix' => 'exportar'], function () {
        Route::get('cod_municipio', 'ClientesController@exportarCodMunicipio');
        Route::get('instrumentos', 'InstrumentosController@exportar')->name('instrumentos.exportar');
        Route::get('instrumentos_base', 'Instrumentos\InstrumentosBasesController@exportar')->name('instrumentos_base.exportar');
        Route::get('instrumentos_setors', 'Instrumentos\InstrumentosSetorsController@exportar')->name('instrumentos_setor.exportar');
        Route::get('pecas', 'PecasController@exportar')->name('pecas.exportar');
	    Route::get( 'servicos', 'ServicosController@exportar' )->name( 'servicos.exportar' );


	    Route::get( 'preco-servicos', 'ServicosController@exportarTabelaPreco' )->name( 'servicos-preco.exportar' );
	    Route::get( 'preco-pecas', 'PecasController@exportarTabelaPreco' )->name( 'pecas-preco.exportar' );
    });

    //IMPORTAÇÃO
    Route::group(['prefix' => 'importar'], function () {
        Route::get('cod_municipio', 'ClientesController@importarCodMunicipio');
        Route::get('contatos', 'InstrumentosController@exportar')->name('instrumentos.exportar');
    });
});

Route::group(['prefix' => 'cron-jobs'], function () {
    Route::get('run-faturamento', 'FaturamentoController@run');
});
Route::group(['prefix' => 'teste'], function () {
	Route::get( 'sintegra', function () {


		return view( 'pages.sintegra.teste' );
	} );
    Route::get('lacres/{id}', function ($id) {
        $Instrumento = \App\Instrumento::find($id);
        return $Instrumento->lacres_instrumento_cliente();
    });
    Route::get('get-hour', function () {
        return \Carbon\Carbon::now()->toDateTimeString();
    });
    Route::get('run-faturamento-temp', 'FaturamentoController@run_temp');

    Route::get('nfse/{idfaturamento}', function (\Illuminate\Http\Request $request) {
        $Fechamento = \App\Models\Faturamento::find($request->idfaturamento);
        $NFSe = new \App\Models\Nfse($debug = 1, $Fechamento);
        $NFSe->_REF_ = 30;
        return $NFSe->emitir();
//        return $NF->send_teste();
    });

    Route::get('modulo11_v1/{valor}', function (\Illuminate\Http\Request $request) {
        $DATA_HELPER = new \App\Helpers\DataHelper();
        $valor = $request->valor;
        $dv = $DATA_HELPER::calculateModulo11($valor);
        $final = $request->valor . $dv;
        echo 'Numeração: ' . $DATA_HELPER->mask($request->valor, '##.###.###') . '<br>';
        echo 'Dígito: ' . $dv . '<br>';
        echo 'Numeração (com DV): ' . $DATA_HELPER->mask($final, '##.###.###-#') . '<br>';
    });
    Route::get('modulo11/{valor}', function (\Illuminate\Http\Request $request) {
        $value = $request->valor;
        $sz = strlen($value);
        $sum = 0;
        foreach (range($sz + 1, 2) as $i => $number) {
            $calc = ($value[$i] * $number);
            $sum += $calc;
//            echo '('.$i.') '.($value[$i]).'x'.$number.' = '.$calc.'<br>';
        }
//        echo '------<br>';
        $res = ($sum / 11);
        $mod = ($sum % 11);
        $final = $value . $mod;
        $DATA_HELPER = new \App\Helpers\DataHelper();
//        echo $sum.'/11 = '. $res.'<br>';
//        echo 'INT = '. intval($res).'<br>';
//        echo 'DIV = '. $mod.'<br>';
        echo 'Numeração: ' . $DATA_HELPER->mask($value, '##.###.###') . '<br>';
        echo 'Dígito: ' . $mod . '<br>';
        echo 'Numeração (com DV): ' . $DATA_HELPER->mask($final, '##.###.###-#') . '<br>';
    });

//Testando o envio de email
    Route::get('sendemail', function () {
        $user = array(
            'email' => "silva.zanin@gmail.com",
            'name' => "LEO",
            'mensagem' => "olá",
        );
        Mail::raw($user['mensagem'], function ($m) use ($user) {
            $m->to($user['email'], $user['name'])->subject('Welcome!');
        });

        return "Your email has been sent successfully";
    });
    Route::get('show-limit/{id}', function ($id) {
        $Client = \App\Cliente::find($id);


        return $Client->ordem_servicos->whereIn('idsituacao_ordem_servico',
		    [
			    \App\OrdemServico::_STATUS_FINALIZADA_,
			    \App\OrdemServico::_STATUS_AGUARDANDO_PECA_,
			    \App\OrdemServico::_STATUS_EQUIPAMENTO_NA_OFICINA_,
			    \App\OrdemServico::_STATUS_FATURAMENTO_PENDENTE_,
		    ]);
    });
});


