<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando o Upload (import_clientes.xlsx) ***";
        $file = storage_path('uploads') . '\import\import_clientes.xlsx';
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);
        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {

                $data_contato = ['telefone', 'celular', 'skype', 'cep', 'estado', 'cidade', 'bairro', 'logradouro', 'numero', 'complemento'];
                foreach ($data_contato as $col) {
                    $contato[$col] = $row->$col;
                }
                $contato['numero'] = strval($contato['numero']);
                //salvar contato
                $Contato = \App\Contato::create($contato);

                $data_pjuridica = ['cnpj', 'ie', 'isencao_ie', 'razao_social', 'nome_fantasia', 'ativ_economica', 'sit_cad_vigente', 'sit_cad_status', 'data_sit_cad', 'reg_apuracao', 'data_credenciamento', 'ind_obrigatoriedade', 'data_ini_obrigatoriedade'];
                foreach ($data_pjuridica as $col) {
                    $pjuridica[$col] = $row->$col;
                }
                $pjuridica['ie'] = strval($pjuridica['ie']);
                if ($pjuridica['ie'] == "ISENTO") {
                    $pjuridica['ie'] = "";
                    $pjuridica['isencao_ie'] = 1;
                } else {
                    $pjuridica['isencao_ie'] = ($pjuridica['isencao_ie'] == NULL) ? 0 : 1;
                }
                //salvar pjuridica
                $PessoJuridica = \App\PessoaJuridica::create($pjuridica);

                $data_cliente = ['idsegmento', 'idtabela_preco', 'idregiao', 'idforma_pagamento', 'centro_custo', 'email_orcamento', 'email_nota', 'limite_credito', 'nome_responsavel'];
                foreach ($data_cliente as $col) {
                    $cliente[$col] = $row->$col;
                }

                $agora = \Carbon\Carbon::now();
                $Cliente = \App\Cliente::create([
                    'idcontato' => $Contato->idcontato,
                    'idpjuridica' => $PessoJuridica->idpjuridica,
                    'idsegmento' => ($cliente['idsegmento'] != NULL) ? $cliente['idsegmento'] : 1,
                    'idtabela_preco' => ($cliente['idtabela_preco'] != NULL) ? $cliente['idtabela_preco'] : 1,
                    'idregiao' => ($cliente['idregiao'] != NULL) ? $cliente['idregiao'] : 1,
                    'idforma_pagamento' => ($cliente['idforma_pagamento'] != NULL) ? $cliente['idforma_pagamento'] : 1,
                    'idcolaborador_criador' => 1,
                    'idcolaborador_validador' => 1,
                    'validated_at' => $agora->toDateTimeString(),
                    'nome_responsavel' => ($cliente['nome_responsavel'] != NULL) ? $cliente['nome_responsavel'] : 'Sem Nome',
                    'distancia' => 0,
                    'pedagios' => 0,
                    'outros_custos' => 0
                ]);
            });

        })->ignoreEmpty();
        echo "\n*** Importacao IMPORTSEEDER (part1) realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";


        $start = microtime(true);
        echo "*** Iniciando o Upload (import_produtos_teste.xlsx) ***";
        $file = storage_path('uploads') . '\import\import_produtos_teste.xlsx';
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $Tabelas_preco = \App\TabelaPreco::all();
            $sheet->each(function ($row) use ($Tabelas_preco) {

                $margem_minimo = 10;
                $margem = $margem_minimo + 5;

                $data_col = ['idfornecedor', 'idmarca', 'idgrupo', 'idunidade', 'tipo', 'codigo', 'codigo_auxiliar', 'codigo_barras', 'descricao',
                    'descricao_tecnico', 'foto', 'sub_grupo', 'garantia', 'comissao_tecnico', 'comissao_vendedor', 'custo_final'];
                foreach ($data_col as $col) {
                    $peca[$col] = strval($row->$col);
                }
                $peca['tipo'] = 'peca';
                $grupo = \App\Grupo::where('descricao', strtoupper($peca['idgrupo']))->first();
                if (count($grupo) > 0) {
                    $peca['idgrupo'] = $grupo->idgrupo;
                } else {
                    $Grupo = \App\Grupo::create([
                        'descricao' => strtoupper($peca['idgrupo'])
                    ]);
                    $peca['idgrupo'] = $Grupo->idgrupo;
                }
                $Peca = \App\Peca::create($peca);
                foreach ($Tabelas_preco as $tabela_preco) {
                    $valor = $Peca->custo_final_float();
                    $preco = $valor + ($margem * $valor) / 100;
                    $preco_minimo = $valor + ($margem_minimo * $valor) / 100;
                    $data = [
                        'idtabela_preco' => $tabela_preco->idtabela_preco,
                        'idpeca' => $Peca->idpeca,
                        'margem' => $margem,
                        'preco' => $preco,
                        'margem_minimo' => $margem_minimo,
                        'preco_minimo' => $preco_minimo,
                    ];
                    \App\TabelaPrecoPeca::create($data);
//                    echo "* inserido\n";
                }
            });
        })->ignoreEmpty();
        echo "\n*** Importacao IMPORTSEEDER (part2) realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }
}