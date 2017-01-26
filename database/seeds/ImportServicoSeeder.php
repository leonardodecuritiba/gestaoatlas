<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ImportServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportServicoSeeder
        $start = microtime(true);
        echo "*** Iniciando o Upload (import_servicos_v3.xlsx) ***";
        $file = storage_path('uploads') . '\import\import_servicos_v3.xlsx';
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $Tabelas_preco = \App\TabelaPreco::all();
            $i = 0;
            $sheet->each(function ($row) use ($Tabelas_preco, $i) {
                //				cfop venda	C S T venda	  	 gricki 	 savegnago 	 geral 	 porcentagem
                $data_col = ['nome', 'descricao', 'idgrupo', 'idunidade', 'valor', 'gricki', 'savegnago', 'geral'];
                echo "****************** ('.$i.') ****************** \n";
                $i++;
                foreach ($data_col as $col) {
                    $servico[$col] = strval($row->$col);
                }
                //INSERINDO O GRUPO
                $servico['idgrupo'] = $this->insert_grupo($servico);

                //INSERINDO A UNIDADE
                $servico['idunidade'] = $this->insert_unidade($servico);


                //INSERIR SERVICO
                $data = [
                    'idgrupo' => $servico['idgrupo'],
                    'idunidade' => $servico['idunidade'],
                    'nome' => $servico['nome'],
                    'descricao' => $servico['descricao'],
                    'valor' => $servico['valor'],
                ];
                $Servico = \App\Servico::create($data);
                $precos = [
                    $servico['gricki'],
                    $servico['savegnago'],
                    $servico['geral'],
                ];
                $valor = $servico['valor'];

                //INSERIR Tabelas_preco
                foreach ($Tabelas_preco as $i => $tabela_preco) {
                    $margem = (($precos[$i] - $valor) / $valor) * 100;
                    $preco = $precos[$i];
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idservico' => $Servico->idservico,
                        'margem' => $margem,
                        'preco' => $preco,
                        'margem_minimo' => $margem,
                        'preco_minimo' => $preco,
                    ];
                    \App\TabelaPrecoServico::create($data);
                }
            });
        })->ignoreEmpty();

        echo "\n*** Importacao IMPORTSEEDER (import_produtos_v2) realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }

    public function insert_grupo($servico)
    {
        $grupo = \App\Grupo::where('descricao', strtoupper($servico['idgrupo']))->first();
        if (count($grupo) > 0) {
//            echo "grupo existente: ".$servico['idgrupo']."\n";
            $idgrupo = $grupo->idgrupo;
        } else {
            echo "*****************************************  grupo inexistente: " . $servico['idgrupo'] . "*****************************************\n";
            $Grupo = \App\Grupo::create([
                'descricao' => strtoupper($servico['idgrupo'])
            ]);
            $idgrupo = $Grupo->idgrupo;
        }
        return $idgrupo;
    }

    public function insert_unidade($servico)
    {
        $unidade = \App\Unidade::where('codigo', strtoupper($servico['idunidade']))->first();
        if (count($unidade) > 0) {
//            echo "Unidade existente: ".$servico['idunidade']."\n";
            $idunidade = $unidade->idunidade;
        } else {
            echo "*****************************************  Unidade inexistente: " . $servico['idunidade'] . "*****************************************\n";
            $Unidade = \App\Unidade::create([
                'codigo' => strtoupper($servico['idunidade']),
                'descricao' => strtoupper($servico['idunidade'])
            ]);
            $idunidade = $Unidade->idunidade;
        }
        return $idunidade;
    }
}