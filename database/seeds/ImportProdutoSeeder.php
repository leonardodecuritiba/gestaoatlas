<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ImportProdutoSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportProdutoSeeder
        $start = microtime(true);
        echo "*** Iniciando o Upload (import_produtos_v3.xlsx) ***";
        $file = storage_path('uploads') . '\import\import_produtos_v3.xlsx';
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $Tabelas_preco = \App\TabelaPreco::all();
            $i = 0;
            $sheet->each(function ($row) use ($Tabelas_preco, $i) {
                //				cfop venda	C S T venda	  	 gricki 	 savegnago 	 geral 	 porcentagem

                $data_col = ['idfornecedor', 'idmarca', 'idgrupo', 'idunidade',
                    'tipo', 'codigo', 'codigo_auxiliar', 'codigo_barras', 'descricao', 'descricao_tecnico', 'sub_grupo', 'garantia', 'comissao_vendedor', 'comissao_tecnico',
                    'ncm', 'icms_base_calculo', 'icms_valor_total', 'icms_base_calculo_st', 'icms_valor_total_st', 'valor_ipi', 'valor_unitario_tributavel',
                    'icms_situacao_tributaria', 'icms_origem', 'pis_situacao_tributaria', 'valor_frete', 'valor_seguro', 'custo_final',
                    'cfop_venda', 'cst_venda', 'gricki', 'savegnago', 'geral', 'porcentagem'];
                echo "****************** ('.$i.') ****************** \n";
                $i++;
                foreach ($data_col as $col) {
                    $peca[$col] = strval($row->$col);
                }

                //INSERINDO O FORNECEDOR
                $peca['idfornecedor'] = $this->insert_fornecedor($peca);

                //INSERINDO A MARCA
                $peca['idmarca'] = $this->insert_marca($peca);

                //INSERINDO O GRUPO
                $peca['idgrupo'] = $this->insert_grupo($peca);

                //INSERINDO A UNIDADE
                $peca['idunidade'] = $this->insert_unidade($peca);

                //INSERINDO O NCM
                $peca['idncm'] = $this->insert_ncm($peca);

                //INSERINDO O CFOP
                $peca['idcfop'] = $this->insert_cfop($peca);

                //INSERINDO O CST
                $peca['idcst'] = $this->insert_cst($peca);

                //INSERIR PEÇA-TRIBUTAÇÃO
                $data = [
                    'idcfop' => $peca['idcfop'],
                    'idcst' => $peca['idcst'],
                    'idnatureza_operacao' => 1,
                    'idncm' => $peca['idncm'],
                    'icms_base_calculo' => $peca['icms_base_calculo'],
                    'icms_valor_total' => $peca['icms_valor_total'],
                    'icms_base_calculo_st' => $peca['icms_base_calculo_st'],
                    'icms_valor_total_st' => $peca['icms_valor_total_st'],
                    'valor_ipi' => $peca['valor_ipi'],
                    'valor_unitario_tributavel' => $peca['valor_unitario_tributavel'],
                    'icms_situacao_tributaria' => $peca['icms_situacao_tributaria'],
                    'icms_origem' => $peca['icms_origem'],
                    'pis_situacao_tributaria' => $peca['pis_situacao_tributaria'],
                    'valor_frete' => $peca['valor_frete'],
                    'valor_seguro' => $peca['valor_seguro'],
                    'custo_final' => $peca['custo_final']
                ];
                $PecaTributacao = \App\PecaTributacao::create($data);
                $peca['idpeca_tributacao'] = $PecaTributacao->id;

                //INSERIR PEÇA
                $data = [
                    'idfornecedor' => $peca['idfornecedor'],
                    'idpeca_tributacao' => $peca['idpeca_tributacao'],
                    'idmarca' => $peca['idmarca'],
                    'idgrupo' => $peca['idgrupo'],
                    'idunidade' => $peca['idunidade'],

                    'tipo' => $peca['tipo'],
                    'codigo' => $peca['codigo'],
                    'codigo_auxiliar' => $peca['codigo_auxiliar'],
                    'codigo_barras' => $peca['codigo_barras'],
                    'descricao' => $peca['descricao'],
                    'descricao_tecnico' => $peca['descricao_tecnico'],
                    'sub_grupo' => $peca['sub_grupo'],
                    'garantia' => $peca['garantia'],
                    'comissao_tecnico' => $peca['comissao_tecnico'],
                    'comissao_vendedor' => $peca['comissao_vendedor'],
                ];
                $Peca = \App\Peca::create($data);

                //INSERIR Tabelas_preco
                $margem = $peca['porcentagem'];
                $valor = $peca['custo_final'];
                $preco = ((1 + $margem) * $valor);
                foreach ($Tabelas_preco as $i => $tabela_preco) {
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idpeca' => $Peca->idpeca,
                        'margem' => $margem * 100,
                        'preco' => $preco,
                        'margem_minimo' => $margem * 100,
                        'preco_minimo' => $preco,
                    ];
                    \App\TabelaPrecoPeca::create($data);
                }
            });
        })->ignoreEmpty();

        echo "\n*** Importacao IMPORTSEEDER (import_produtos_v2) realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }

    public function insert_fornecedor($peca)
    {
        $pessoa_juridica_id = \App\PessoaJuridica::where('razao_social', 'like', '%' . $peca['idfornecedor'] . '%')
            ->orWhere('nome_fantasia', 'like', '%' . $peca['idfornecedor'] . '%')->pluck('idpjuridica');
        $fornecedor = \App\Fornecedor::whereIn('idpjuridica', $pessoa_juridica_id)->first();
        if (count($fornecedor) > 0) {
//            echo "fornecedor existente: ".$peca['idfornecedor']."\n";
            $idfornecedor = $fornecedor->idfornecedor;
        } else {
            //contato
            echo "***************************************** fornecedor inexistente: " . $peca['idfornecedor'] . "*****************************************\n";
            $contato = App\Contato::create();
            $fornecedor['idcontato'] = $contato->idcontato;

            //pjuridica
            $pjuridica = [
                'cnpj' => 0,
                'ie' => 0,
                'isencao_ie' => 1,
                'razao_social' => strtoupper($peca['idfornecedor']),
                'nome_fantasia' => strtoupper($peca['idfornecedor']),
            ];
            $pjuridica = App\PessoaJuridica::create($pjuridica);
            $fornecedor['idpjuridica'] = $pjuridica->idpjuridica;

            //fornecedor
            $fornecedor['idsegmento_fornecedor'] = NULL;
            $fornecedor['grupo'] = '';
            $fornecedor['email_orcamento'] = '';
            $fornecedor['nome_responsavel'] = 'SEM NOME';
            $Fornecedor = App\Fornecedor::create($fornecedor);
            $idfornecedor = $Fornecedor->idfornecedor;
        }
        return $idfornecedor;

    }

    public function insert_marca($peca)
    {
        if ($peca['idmarca'] != '') {
            $marca = \App\Marca::where('descricao', strtoupper($peca['idmarca']))->first();
            if (count($marca) > 0) {
//                echo "Marca existente: ".$peca['idmarca']."\n";
                $idmarca = $marca->idmarca;
            } else {
                echo "***************************************** Marca inexistente: " . $peca['idmarca'] . "*****************************************\n";
                $Marca = \App\Marca::create([
                    'descricao' => strtoupper($peca['idmarca'])
                ]);
                $idmarca = $Marca->idmarca;
            }
        } else {
            $idmarca = NULL;
        }
        return $idmarca;
    }

    public function insert_grupo($peca)
    {
        $grupo = \App\Grupo::where('descricao', strtoupper($peca['idgrupo']))->first();
        if (count($grupo) > 0) {
//            echo "grupo existente: ".$peca['idgrupo']."\n";
            $idgrupo = $grupo->idgrupo;
        } else {
            echo "*****************************************  grupo inexistente: " . $peca['idgrupo'] . "*****************************************\n";
            $Grupo = \App\Grupo::create([
                'descricao' => strtoupper($peca['idgrupo'])
            ]);
            $idgrupo = $Grupo->idgrupo;
        }
        return $idgrupo;
    }

    public function insert_unidade($peca)
    {
        $unidade = \App\Unidade::where('codigo', strtoupper($peca['idunidade']))->first();
        if (count($unidade) > 0) {
//            echo "Unidade existente: ".$peca['idunidade']."\n";
            $idunidade = $unidade->idunidade;
        } else {
            echo "*****************************************  Unidade inexistente: " . $peca['idunidade'] . "*****************************************\n";
            $Unidade = \App\Unidade::create([
                'codigo' => strtoupper($peca['idunidade']),
                'descricao' => strtoupper($peca['idunidade'])
            ]);
            $idunidade = $Unidade->idunidade;
        }
        return $idunidade;
    }

    public function insert_ncm($peca)
    {
        $ncm = \App\Helpers\DataHelper::getOnlyNumbers($peca['ncm']);
        $data = \App\Ncm::where('codigo', $ncm)->first();
        if (count($data) > 0) {
//            echo "NCM existente: ".$ncm."\n";
            $idncm = $data->idncm;
        } else {
            echo "*****************************************  NCM inexistente: " . $ncm . "*****************************************\n";
            $Data = \App\Ncm::create([
                'codigo' => $ncm,
                'descricao' => 'SEM DESCRIÇÃO'
            ]);
            $idncm = $Data->idncm;
        }
        return $idncm;
    }

    public function insert_cfop($peca)
    {
        $cfop = \App\Helpers\DataHelper::getOnlyNumbers($peca['cfop_venda']);
        $data = \App\Cfop::where('numeracao', $cfop)->first();
        if (count($data) > 0) {
            $idcfop = $data->id;
        } else {
            echo "*****************************************  CFOP inexistente: " . $cfop . "*****************************************\n";
            $Data = \App\Cfop::create([
                'numeracao' => $cfop
            ]);
            $idcfop = $Data->id;
        }
        return $idcfop;
    }

    public function insert_cst($peca)
    {
        $cst = \App\Helpers\DataHelper::getOnlyNumbers($peca['cst_venda']);
        $data = \App\Cst::where('numeracao', $cst)->first();
        if (count($data) > 0) {
            $idcst = $data->id;
        } else {
            echo "*****************************************  CST inexistente: " . $cst . "*****************************************\n";
            $Data = \App\Cst::create([
                'numeracao' => $cst
            ]);
            $idcst = $Data->id;
        }
        return $idcst;
    }
}