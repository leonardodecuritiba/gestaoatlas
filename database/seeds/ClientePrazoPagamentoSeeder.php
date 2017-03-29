<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as DB;

class ClientePrazoPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//    php artisan db:seed --class=ClientePrazoPagamentoSeeder
    public function run()
    {
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";

        $forma_pagamento = [
            ['id' => 0, 'extras' => ''],
            ['id' => 1, 'extras' => ['10']],
            ['id' => 1, 'extras' => ['30']],
        ];

        DB::table('clientes')->where('idforma_pagamento_tecnica', '<', 5)->update([
            'prazo_pagamento_tecnica' => json_encode($forma_pagamento[0]),
        ]);

        DB::table('clientes')->where('idforma_pagamento_tecnica', 5)->update([
            'prazo_pagamento_tecnica' => json_encode($forma_pagamento[1]),
        ]);

        DB::table('clientes')->where('idforma_pagamento_tecnica', 6)->update([
            'idforma_pagamento_tecnica' => 5,
            'prazo_pagamento_tecnica' => json_encode($forma_pagamento[2]),
        ]);

        DB::table('clientes')->where('idforma_pagamento_tecnica', 10)->update([
            'idforma_pagamento_tecnica' => 5,
            'prazo_pagamento_tecnica' => json_encode($forma_pagamento[1]),
        ]);

        DB::table('clientes')->whereNull('prazo_pagamento_comercial')->update([
            'idforma_pagamento_comercial' => 5,
            'prazo_pagamento_comercial' => json_encode($forma_pagamento[0]),
        ]);

        DB::table('formas_pagamentos')->where('idforma_pagamento', '>', 7)->delete();
        DB::table('formas_pagamentos')->where('idforma_pagamento', 5)->update(['descricao' => 'BOLETO']);
        DB::table('formas_pagamentos')->where('idforma_pagamento', 6)->update(['descricao' => 'DINHEIRO']);
        DB::table('formas_pagamentos')->where('idforma_pagamento', 7)->update(['descricao' => 'TRANSFERÊNCIA']);

        echo "\n*** ClientePrazoPagamentoSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
