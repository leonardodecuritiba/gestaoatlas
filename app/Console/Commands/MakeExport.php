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
        $this->call('command:export_users');
        $this->call('command:export_lacres');
        $this->call('command:export_selos');
	    $path = storage_path('exports');
	    exec('zip -r ' . $path . '.zip ' . $path);
    }
}
