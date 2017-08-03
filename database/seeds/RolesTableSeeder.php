<?php

use App\Permission;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=RolesTableSeeder
        $data = array([
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Administrador do sistema',
            'created_at' => \Carbon\Carbon::now(),
        ], [
            'name' => 'tecnico',
            'display_name' => 'Técnico',
            'description' => 'Técnico do sistema tem acesso restrito',
            'created_at' => \Carbon\Carbon::now(),
        ], [
            'name' => 'gestor',
            'display_name' => 'Gestor',
            'description' => 'Gestor de Selos/Lacres do sistema tem acesso restrito',
            'created_at' => \Carbon\Carbon::now(),
        ], [
            'name' => 'vendedor',
            'display_name' => 'Vendedor',
            'description' => 'Vendedor do sistema tem acesso restrito',
            'created_at' => \Carbon\Carbon::now(),
        ], [
            'name' => 'financeiro',
            'display_name' => 'Financeiro',
            'description' => 'Financeiro do sistema tem acesso restrito',
            'created_at' => \Carbon\Carbon::now(),
        ], [
            'name' => 'gerente',
            'display_name' => 'Gerente',
            'description' => 'Gerente do sistema tem acesso restrito',
            'created_at' => \Carbon\Carbon::now(),
        ]);

        echo "\n*** RolesTableSeeder completo em " . round((microtime(true) - $start), 3) . "s ***";
    }
}
