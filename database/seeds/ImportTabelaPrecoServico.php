<?php

use Illuminate\Database\Seeder;

class ImportTabelaPrecoServico extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportTabelaPrecoServico
        $start = microtime(true);
	    $a     = 'export_01_09_2017-16_43.xls';
        echo "*** Iniciando o Upload (" . $a . ") ***";
        $file = storage_path('uploads' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . $a); //servidor
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
                $custo_final = \App\Helpers\DataHelper::getReal2Float($row->valor);
	            $preco       = ( $custo_final > 0 ) ? \App\Helpers\DataHelper::getReal2Float( $row['tonin'] ) : 0;
	            $margem      = ( $preco > 0 ) ? ( ( ( $preco / $custo_final ) - 1 ) * 100 ) : 0;
                $data        = [
	                'idtabela_preco' => 5,
	                'idservico'      => $row->idservico,
	                'margem'         => $margem,
	                'preco'          => $preco,
	                'margem_minimo'  => $margem,
	                'preco_minimo'   => $preco
                ];
                \App\TabelaPrecoServico::create($data);

	            $tabelas = [
		            'tabela_gricki'       => 1,
		            'tabela_savegnago'    => 2,
		            'abre_mercado_franca' => 3,
		            'tabela_geral'        => 4,
	            ];

	            foreach ( $tabelas as $key => $idtabela_preco ) {
		            $preco  = ( $custo_final > 0 ) ? \App\Helpers\DataHelper::getReal2Float( $row->{$key} ) : 0;
		            $margem = ( $preco > 0 ) ? ( ( ( $preco / $custo_final ) - 1 ) * 100 ) : 0;
		            $data   = [
			            'margem'        => $margem,
			            'preco'         => $preco,
			            'margem_minimo' => $margem,
			            'preco_minimo'  => $preco
		            ];
//	                echo($data);
		            \App\TabelaPrecoServico::where( 'idtabela_preco', $idtabela_preco )
		                                   ->where( 'idservico', $row->idservico )
		                                   ->update( $data );
	            }
	            $this->command->info( "****************** (" . $row->idservico . ") ******************" );
            });
        })->ignoreEmpty();
	    $this->command->info( "*** Importacao ImportTabelaPrecoServico (" . $a . ") realizada com sucesso em " . round( ( microtime( true ) - $start ), 3 ) . "s ***" );

    }
}
