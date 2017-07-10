<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class ImportInstrumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportInstrumentosSeeder
        $start = microtime(true);
        $filename = 'INSTRUMENTOSexport_28_06_2017-12_03.xls';
        echo "*** Iniciando o Upload (" . $filename . ") ***";
        $file = storage_path('uploads') . '\import\\' . $filename;
        echo "\n*** Upload completo em " . round((microtime(true) - $start), 3) . "s ***";
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                //				cfop venda	C S T venda	  	 gricki 	 savegnago 	 geral 	 porcentagem
                $data_col = [
                    'idinstrumento',
                    'idcliente',
                    'cliente',
                    'descricao',
                    'marca',
                    'modelo',
                    'ano',
                    'capacidade',
                    'divisao',
                    'portaria',
                    'setor',
                    '-',
                    'numero_serie',
                    'patrimonio',
                    'inventario',
                    'ip',
                    'endereco',
                    '-',
                    'idsetor',
                    'idinstrumentobase'
                ];
                //RECUPERANDO O INSTRUMENTO
                $Instrumento = \App\Instrumento::findOrFail($row->idinstrumento);
                if ($Instrumento->count() > 0) {
                    echo "****************** ('.$Instrumento->idinstrumento.') ****************** \n";

//                print_r('<pre>');
//                print_r($Instrumento);
//                print_r('</pre>');
//                exit;
                    //ATUALIZANDO SEGURANCA
                    $Seguranca = \App\Models\Seguranca::create([
                        'idcriador' => $Instrumento->idcolaborador_criador,
                        'idvalidador' => $Instrumento->idcolaborador_validador,
                        'validated_at' => $Instrumento->validated_at,
                    ]);


//                print_r('<pre>');
//                print_r($Seguranca);
//                print_r('</pre>');
//                exit;
                    //ATUALIZANDO BASE
                    //ATUALIZANDO SETOR
                    $Instrumento->update([
                        'idbase' => $row->idinstrumentobase,
                        'idsetor' => $row->idsetor,
                        'idprotecao' => $Seguranca->id,
                    ]);

                }


            });
        })->ignoreEmpty();

        echo "\n*** Importacao IMPORTSEEDER realizada com sucesso em " . round((microtime(true) - $start), 3) . "s ***";

    }

}