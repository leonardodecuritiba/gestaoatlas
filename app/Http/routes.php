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



//Route::get('importar_banco', 'Controller@importar');

Route::auth();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', 'HomeController@index')->name('busca.index');

    Route::get('/index', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

    Route::resource('clientes', 'ClientesController');
    Route::patch('clientes/{cliente}', 'ClientesController@update')->name('clientes.update');
    Route::get('cliente/validar/{id}', 'ClientesController@validar')->name('cliente.validar');

    Route::resource('instrumentos', 'InstrumentosController');
    Route::resource('fornecedores', 'FornecedoresController');
    Route::resource('pecas', 'PecasController');
    Route::get('exportar/pecas', 'PecasController@exportar')->name('pecas.exportar');

    //Serviços
    Route::resource('servicos', 'ServicosController');

    Route::resource('colaboradores', 'ColaboradoresController');
    Route::post('pwd/{colaborador}/colaboradores', 'ColaboradoresController@upd_pass')->name('colaboradores.upd_pass');
    Route::post('selolacre/{idtecnico}', 'ColaboradoresController@selolacre_store')->name('selolacre.store');
    Route::post('selolacre-remanejar/{idtecnico}', 'ColaboradoresController@selolacre_remanejar')->name('selolacre.remanejar');

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
    Route::get('ordem_servicos/listar/{situacao_ordem_servico}', 'OrdemServicoController@index')->name('ordem_servicos.index');
    Route::get('ordem-servicos-centro-custo/listar/{situacao_ordem_servico}', 'OrdemServicoController@index_centro_custo')->name('ordem_servicos.index_centro_custo');
    Route::get('ordem-servicos-centro-custo/show/{situacao_ordem_servico}/{idcentro_custo}', 'OrdemServicoController@show_centro_custo')->name('ordem_servicos.show_centro_custo');
    Route::get('ordem_servicos/abrir/{clienteid}', 'OrdemServicoController@abrir')->name('ordem_servicos.abrir'); //Abrir O.S
    Route::get('ordem_servicos/resumo/{idordem_servico}', 'OrdemServicoController@resumo')->name('ordem_servicos.resumo'); //Fechar (RESUMO) O.S
    Route::post('ordem_servicos/fechar/{idordem_servico}', 'OrdemServicoController@fechar')->name('ordem_servicos.fechar'); //Fechar O.S
    Route::get('ordem_servicos/imprimir/{idordem_servico}', 'OrdemServicoController@imprimir')->name('ordem_servicos.imprimir'); //Imprimir O.S
    Route::get('ordem_servicos/encaminhar/{idordem_servico}', 'OrdemServicoController@encaminhar')->name('ordem_servicos.encaminhar'); //Imprimir O.S
    Route::get('ordem_servicos/destroy/{idordem_servico}', 'OrdemServicoController@destroy')->name('ordem_servicos.destroy'); //Imprimir O.S
    Route::get('ordem_servicos/cliente/{idcliente}', 'OrdemServicoController@get_ordem_servicos_cliente')->name('ordem_servicos.cliente');
    Route::get('ordem_servicos/por_colaborador/{idcolaborador}/{tipo}', 'OrdemServicoController@get_ordem_servicos_colaborador')->name('ordem_servicos.por_colaborador');

    Route::get('busca/ordem_servicos/{idordem_servico}/instrumentos', 'OrdemServicoController@buscaInstrumentos')->name('ordem_servicos.instrumentos.busca');
    Route::get('adiciona/ordem_servicos/{idordem_servico}/{idinstrumento}/instrumentos', 'OrdemServicoController@adicionaInstrumento')->name('ordem_servicos.instrumentos.adiciona');
    Route::get('remove/ordem_servicos/{idaparelho_manutencao}/instrumento', 'OrdemServicoController@removeInstrumento')->name('ordem_servicos.instrumentos.remove');

//    Route::get('busca/ordem_servicos/{idordem_servico}/equipamentos', 'OrdemServicoController@buscaInstrumentos')->name('ordem_servicos.instrumentos.busca');
    Route::get('adiciona/ordem_servicos/{idordem_servico}/{idequipamento}/equipamentos', 'OrdemServicoController@adicionaEquipamento')->name('ordem_servicos.equipamentos.adiciona');
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
    Route::resource('fechamentos', 'FechamentoController');
    Route::get('listar-fechamentos/{status}', 'FechamentoController@index')->name('fechamentos.index');
    Route::post('parcela/pagar', 'ParcelaController@pagar')->name('parcelas.pagar');
    Route::get('parcela/boleto/{idparcela}', 'ParcelaController@gerarBoleto')->name('parcelas.boleto');
    Route::get('parcela/estornar/{idparcela}', 'ParcelaController@estornar')->name('parcelas.estornar');


    //RELATÓRIOS
    Route::get('relatorios/ipem', 'RelatoriosController@ipem')->name('relatorios.ipem');
    Route::get('relatorios/ipem/imprimir', 'RelatoriosController@ipemPrint')->name('relatorios.ipem.print');
});

Route::group(['prefix' => 'cron-jobs'], function () {

    Route::get('run-fechamento', 'FechamentoController@run');
});
Route::group(['prefix' => 'teste'], function () {
    Route::get('run-fechamento-temp', 'FechamentoController@run_temp');
    Route::get('nfe/{idordemservico}', function (\Illuminate\Http\Request $request) {
        return \App\OrdemServico::find($request->idordemservico);
        $NFE = new \App\Models\Nfe($debug = 1, \App\OrdemServico::find($request->idordemservico));
        $NFE->send_teste();
    });
    Route::get('get_cest2', function () {
        return \App\Models\Nfe::consulta2();
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
});
//Testando o envio de email
Route::get('sendemail', function () {
    $user = array(
        'email' => "silva.zanin@gmail.com",
        'name' => "LEO",
        'mensagem' => "olá",
    );
    Mail::raw($user['mensagem'], function($message) use ($user) {
        $message->to($user['email'], $user['name'])->subject('Welcome!');
        $message->from('xxx@gmail.com', 'Atendimento');
    });

    return "Your email has been sent successfully";
});


/**/
Route::get('ncm_ajax', function(){

    //    {results: [{"Code":"123360000"},{"Code":""},{"Code":""},{"Code":""}], more: false }
    // Make sure we have a result
    for ($i = 0; $i < 10; $i++) {
        $data[] = array('id' => $i, 'text' => 'texto ' . $i);
    }
    // return the result in json
    echo json_encode($data);
})->name('ncm_ajax');



