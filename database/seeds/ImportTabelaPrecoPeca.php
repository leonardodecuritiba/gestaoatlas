<?php

use Illuminate\Database\Seeder;

class ImportTabelaPrecoPeca extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=ImportTabelaPrecoPeca
        $start = microtime(true);
	    $a     = 'export_01_09_2017-16_44.xls';
        echo "*** Iniciando o Upload (" . $a . ") ***";
        $file = storage_path('uploads' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . $a); //servidor
//        $file = storage_path('uploads') . '\import\export_21_02_2017-16_19.xls';
        set_time_limit(3600);

        $reader = Excel::load($file, function ($sheet) {
            // Loop through all sheets
            $sheet->each(function ($row) {
	            $Peca        = \App\Peca::find( $row->idpeca );
	            $custo_final = \App\Helpers\DataHelper::getReal2Float( $Peca->peca_tributacao->custo_final );

	            $preco  = ( $custo_final > 0 ) ? \App\Helpers\DataHelper::getReal2Float( $row['tonin'] ) : 0;
	            $margem = ( $preco > 0 ) ? ( ( ( $preco / $custo_final ) - 1 ) * 100 ) : 0;
	            $data   = [
		            'idtabela_preco' => 5,
		            'idpeca'         => $row->idpeca,
		            'margem'         => $margem,
		            'preco'          => $preco,
		            'margem_minimo'  => $margem,
		            'preco_minimo'   => $preco
	            ];
	            \App\TabelaPrecoPeca::create( $data );

	            $tabelas = [
		            'tabela_gricki'       => 1,
		            'tabela_savegnago'    => 2,
		            'abre_mercado_franca' => 3,
		            'tabela_geral'        => 4,
	            ];

	            foreach ( $tabelas as $key => $idtabela_preco ) {
		            $preco  = ( $custo_final > 0 ) ? \App\Helpers\DataHelper::getReal2Float( $row[ $key ] ) : 0;
		            $margem = ( $preco > 0 ) ? ( ( ( $preco / $custo_final ) - 1 ) * 100 ) : 0;
		            $data   = [
			            'margem'        => $margem,
			            'preco'         => $preco,
			            'margem_minimo' => $margem,
			            'preco_minimo'  => $preco
		            ];
//	                print_r($data);
		            \App\TabelaPrecoPeca::where( 'idtabela_preco', $idtabela_preco )->where( 'idpeca', $row->idpeca )->update( $data );
	            }
	            $this->command->info( "****************** (" . $row->idpeca . ") ******************" );

            });
        })->ignoreEmpty();

	    $this->command->info( "*** Importacao IMPORTSEEDER (" . $a . ") realizada com sucesso em " . round( ( microtime( true ) - $start ), 3 ) . "s ***" );

    }
}
