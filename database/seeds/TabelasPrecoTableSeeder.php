<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class TabelasPrecoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=TabelasPrecoTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders das Tabelas Preco  ***\n";
        $margem_minimo = 10;
        $margem = $margem_minimo + 5;
        $Tabelas_preco = \App\TabelaPreco::all();
        foreach ($Tabelas_preco as $tabela_preco) {
            echo "Tabela Preco(" . $tabela_preco->idtabela_preco . ") \n";
            echo "------------------------------------------------\n";
            echo "Inserção de Pecas ------------------------------\n";
            $Pecas = \App\Peca::all();
            if (count($Pecas) > 1) {
                foreach ($Pecas as $peca) {
                    echo "Peca(" . $peca->idpeca . ") \n";
                    $valor = $peca->custo_final_float();
                    $preco = $valor + ($margem * $valor) / 100;
                    $preco_minimo = $valor + ($margem_minimo * $valor) / 100;
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idpeca' => $peca->idpeca,
                        'margem' => $margem,
                        'preco' => $preco,
                        'margem_minimo' => $margem_minimo,
                        'preco_minimo' => $preco_minimo,
                    ];
//                    print_r($data);exit;
                    DB::table('tabela_precos_peca')->insert($data);
                    echo "* inserido\n";
                }
                echo "\n------------------------------------------------\n";
            } else {
                echo "SEM PECAS -\n";
                echo "\n------------------------------------------------\n";
            }
            echo "\n------------------------------------------------\n";
            echo "Insercao de Kits ------------------------------\n";
            $Kits = \App\Kit::all();
            if (count($Kits) > 1) {
                foreach ($Kits as $kit) {
                    echo "Kit(" . $kit->idpeca . ") \n";
                    $valor = $kit->valor_total_float();
                    $preco = $valor + ($margem * $valor) / 100;
                    $preco_minimo = $valor + ($margem_minimo * $valor) / 100;
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idkit' => $kit->idkit,
                        'margem' => $margem,
                        'preco' => $preco,
                        'margem_minimo' => $margem_minimo,
                        'preco_minimo' => $preco_minimo,
                    ];
                    DB::table('tabela_precos_kit')->insert($data);
                    echo "* inserido\n";
                }
                echo "\n------------------------------------------------\n";
            } else {
                echo "SEM KITS -\n";
                echo "\n------------------------------------------------\n";
            }

            echo "Insercao de Servicos ------------------------------\n";
            $Servicos = \App\Servico::all();
            if (count($Servicos) > 1) {
                foreach ($Servicos as $servico) {
                    echo "Servico(" . $servico->idservico . ") \n";
                    $valor = $servico->valor_float();
                    $preco = $valor + ($margem * $valor) / 100;
                    $preco_minimo = $valor + ($margem_minimo * $valor) / 100;
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idservico' => $servico->idservico,
                        'margem' => $margem,
                        'preco' => $preco,
                        'margem_minimo' => $margem_minimo,
                        'preco_minimo' => $preco_minimo,
                    ];
                    DB::table('tabela_precos_servico')->insert($data);
                    echo "* inserido\n";
                }
                echo "\n------------------------------------------------\n";
            } else {
                echo "SEM SERVICOS -\n";
                echo "\n------------------------------------------------\n";
            }


        }


        echo "\n*** Importacao realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";
        exit;

    }
}
