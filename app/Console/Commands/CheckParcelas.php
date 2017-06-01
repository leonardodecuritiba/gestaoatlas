<?php

namespace App\Console\Commands;

use App\Models\StatusParcela;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckParcelas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_parcelas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check parcelas';

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
        return DB::table('parcelas')->where('idstatus_parcela', 1)
            ->where('idstatus_parcela', StatusParcela::_STATUS_ABERTO_)
            ->where('data_vencimento', '<', Carbon::now())
            ->update(['idstatus_parcela' => StatusParcela::_STATUS_VENCIDO_]);
    }
}
