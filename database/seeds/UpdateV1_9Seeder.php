<?php

use Illuminate\Database\Seeder;

class UpdateV1_9Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    php artisan db:seed --class=UpdateV1_9Seeder
	    $start = microtime( true );


	    $data = [
		    //máximo número de lacres que usuário pode ter antes de requisitar novos
		    ['meta_key' => 'requests_max_patterns', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
		    //máximo número de selos que usuário pode ter antes de requisitar novos
		    ['meta_key' => 'requests_max_tools', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
		    //máximo número de lacres que usuário pode ter antes de requisitar novos
		    ['meta_key' => 'requests_max_patterns_req', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
		    //máximo número de selos que usuário pode ter antes de requisitar novos
		    ['meta_key' => 'requests_max_tools_req', 'meta_value' => '10', 'created_at' => \Carbon\Carbon::now()->toDateTimeString()],
	    ];
	    \App\Models\Ajustes\Ajuste::insert($data);

//	    $this->call( StatusRequestSeeder::class );
//	    $this->call( TypeRequestSeeder::class );
//	    $this->call( ToolsSeeder::class );


	    $this->command->info( 'Update V1.9 complete: in ' . round( ( microtime( true ) - $start ), 3 ) . 's ...' );

    }
}
