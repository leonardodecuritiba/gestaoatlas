<?php

namespace App\Console\Commands;

use App\Colaborador;
use App\Models\Requests\Request;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $Data = Request::all();
        return Excel::create('requests', function ($excel) use ($Data) {
            $excel->sheet('Sheet 1', function($sheet) use($Data) {
                $sheet->row(1, array(
	                'created_at',
	                'type',
	                'status',
	                'requester_id',
	                'manager_id',
	                'reason',
	                'parameters',
	                'response',
	                'end_at',
                ));

                $i = 2;

                foreach ($Data as $data) {
                	$requester = Colaborador::find($data->idrequester);
                	$manager = Colaborador::find($data->idmanager);
                    $data_export = [

                        'created_at'    => $data->getOriginal('created_at'),
                        'type'          => $data->idtype,
                        'status'        => $data->idstatus,

                        'requester_id'  => ($requester != NULL) ? $requester->iduser : NULL,
                        'manager_id'    => ($manager != NULL) ? $manager->iduser : NULL,

                        'reason'        => $data->reason,
                        'parameters'    => $data->parameters,
                        'response'      => $data->response,
                        'end_at'        => $data->enddate,
                    ];

                    $sheet->row($i, $data_export);
                    $i++;
                }
            });

        })->store('xls');
    }
}
