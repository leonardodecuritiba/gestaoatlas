<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('command:export_clientes');
        $this->call('command:export_equipamentos');
        $this->call('command:export_fornecedores');
        $this->call('command:export_grupos');
        $this->call('command:export_marcas');
        $this->call('command:export_ncm');

        $this->call('command:export_pecas');
        $this->call('command:export_regioes');
        $this->call('command:export_segmentos');
        $this->call('command:export_segmentos_fornecedores');
        $this->call('command:export_servicos');

        $this->call('command:export_tabela_precos');
        $this->call('command:export_tabela_preco_pecas');
        $this->call('command:export_tabela_preco_servicos');

        $this->call('command:export_instrument_brands');
        $this->call('command:export_instrument_models');
        $this->call('command:export_instrument_setors');
        $this->call('command:export_pams');
        $this->call('command:export_instruments');

        $this->call('command:export_users');
        $this->call('command:export_lacres');
        $this->call('command:export_selos');

        $this->call('command:export_pagamentos');
        $this->call('command:export_faturamentos');
        $this->call('command:export_ordem_servicos');
        $this->call('command:export_aparelho_manutencao');

        $this->call('command:export_selo_instrumentos');
        $this->call('command:export_lacre_instrumentos');

	    $this->call('command:export_servicos_prestados');
	    $this->call('command:export_pecas_utilizadas');

	    $this->call('command:export_requests');
	    $this->call('command:export_parcelas');

	    $path = storage_path('exports');

	    $host = env('DB_HOST');
	    $username = env('DB_USERNAME');
	    $password = env('DB_PASSWORD');
	    $database = env('DB_DATABASE');

	    $filename = $path . DIRECTORY_SEPARATOR . 'dump.sql';
	    $command = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $filename);
	    exec($command);
	    exec('zip -r ' . $path . '.zip ' . $path);


    }
}
