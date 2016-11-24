<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;

class TotalTesteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=TotalTesteTableSeeder
        $this->call(DatabaseSeeder::class);
        $this->call(ExtrasTableSeeder::class);
        $this->call(TabelasPrecoTableSeeder::class);
        exit;

    }
}
