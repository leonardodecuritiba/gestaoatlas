<?php

use Illuminate\Database\Seeder;
use App\Models\Instrumentos\InstrumentoMarca;

class InstrumentoMarcaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=InstrumentoMarcaTableSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os InstrumentoMarcaTable ***";
//        $marcas = ['1','2','5','3','39','41','42',44];
        $marcas = ['FILIZOLA', 'ELGIN', 'TOLEDO', 'TOLEDO PRIX', 'DIGITRON', 'C&F', 'DIGIPESO', 'BALMAK'];
        foreach ($marcas as $marca) {
            InstrumentoMarca::create(['descricao' => $marca]);
        }
        echo "\n*** Instrumento Marca completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
