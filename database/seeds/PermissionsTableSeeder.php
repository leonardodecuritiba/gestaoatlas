<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=PermissionsTableSeeder
        $start = microtime(true);
        echo "*** Iniciando os Seeders ***";
        $admin = \App\Role::find(1);
        $tecnico = \App\Role::find(2);

        // --- Crud Ajustes ------------------------------
        $perm = new Permission();
        $perm->name         = 'crud-ajustes';
        $perm->display_name = 'Crud Ajustes'; // optional
        $perm->save();
        $admin->attachPermission($perm);

        // --- Crud Pessoas ------------------------------
        $perm = new Permission();
        $perm->name         = 'crud-pessoas';
        $perm->display_name = 'Crud Pessoas'; // optional
        $perm->save();
        $admin->attachPermission($perm);

        // --- Crud Insumos ------------------------------
        $perm = new Permission();
        $perm->name         = 'crud-insumos';
        $perm->display_name = 'Crud Insumos'; // optional
        $perm->save();
        $admin->attachPermission($perm);

        // --- Crud Atividades ------------------------------
        $perm = new Permission();
        $perm->name         = 'crud-atividades';
        $perm->display_name = 'Crud Atividades'; // optional
        $perm->save();
        $admin->attachPermission($perm);

        // --- Listagem Clientes ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-clientes';
        $perm->display_name = 'Listagem de Clientes'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);

        // --- Criação de Clientes ------------------------------
        $perm = new Permission();
        $perm->name         = 'store-clientes';
        $perm->display_name = 'Criação de Clientes'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);

        // --- Listagem Fornecedores ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-fornecedores';
        $perm->display_name = 'Listagem de Fornecedores'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);

        // --- Listagem de Peças ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-pecas';
        $perm->display_name = 'Listagem de Peças'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);

        // --- Listagem de Kits ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-kits';
        $perm->display_name = 'Listagem de Kits'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);

        // --- Listagem de Orçamentos ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-orcamentos';
        $perm->display_name = 'Listagem de Orçamentos'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);
        $admin->attachPermission($perm);

        // --- Criação de Orçamentos ------------------------------
        $perm = new Permission();
        $perm->name         = 'store-orcamentos';
        $perm->display_name = 'Criação de Orçamentos'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);


        // --- Listagem de Ordens de Serviço ------------------------------
        $perm = new Permission();
        $perm->name         = 'list-ordens-servicos';
        $perm->display_name = 'Listagem de Ordens de Serviço'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);
        $admin->attachPermission($perm);

        // --- Criação de Ordens de Serviço ------------------------------
        $perm = new Permission();
        $perm->name         = 'store-ordens-servicos';
        $perm->display_name = 'Criação de Ordens de Serviço'; // optional
        $perm->save();
        $tecnico->attachPermission($perm);



        echo "\n*** PermissionsTableSeeder completo em ".round((microtime(true) - $start), 3)."s ***";
    }
}
