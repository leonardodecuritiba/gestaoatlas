<?php

use Illuminate\Database\Seeder;

class ToolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=ToolsSeeder
        $start = microtime(true);
        echo "*** Iniciando os ToolsSeeder ***";
        $data = array(
            ['description' => 'TRAMONTINA'],
            ['description' => 'IRWIN'],
            ['description' => 'FERROS C&A']
        );
        foreach ($data as $dt) {
            \App\Models\Commons\Brand::create($dt);
        }
        echo "*** BRANDS - ok ***\n";

        $data = array(
            ['description' => 'ALICATE'],
            ['description' => 'CHAVE DE FENDA'],
            ['description' => 'FERROS DE SOLDA']
        );
        foreach ($data as $dt) {
            \App\Models\Inputs\Tool\ToolCategory::create($dt);
        }
        echo "*** TOOL CATEGORIES - ok ***\n";

        echo "\n*** ToolsSeeder completo em " . round((microtime(true) - $start), 3) . "s ***\n";
    }
}
