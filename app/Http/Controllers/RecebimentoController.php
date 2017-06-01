<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\FormaPagamento;
use App\Helpers\DataHelper;
use App\Models\Faturamento;
use App\Models\Nfe;
use App\Models\Pagamento;
use App\Models\Parcela;
use App\Models\PrazoPagamento;
use App\Models\StatusFechamento;
use App\Models\StatusParcela;
use App\OrdemServico;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RecebimentoController extends Controller
{
    private $Page;
    private $colaborador;
    private $tecnico;

    public function __construct()
    {
        $this->Page = (object)[
            'link' => "recebimentos",
            'primaryKey' => "id",
            'Target' => "Recebimentos",
            'Search' => "Buscar por CPF, CNPJ, Nome Fantasia ou Razão Social...",
            'Targets' => "Recebimentos",
            'Titulo' => "Recebimentos",
            'search_results' => "",
            'search_no_results' => "Nenhum Recebimento encontrado!",
            'msg_abr' => 'Recebimento aberto com sucesso!',
            'msg_upd' => 'Recebimento atualizado com sucesso!',
            'msg_rem' => 'Recebimento removido com sucesso!',
            'msg_rea' => 'Recebimento reaberto com sucesso!',
            'titulo_primario' => "",
            'titulo_secundario' => "",
        ];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->has('data_inicial')) {
            $query = Parcela::whereBetween('data_vencimento',
                [DataHelper::getPrettyToCorrectDate($request->get('data_inicial')), DataHelper::getPrettyToCorrectDate($request->get('data_final'))]);
            $Buscas = $query->get();

//            return $Buscas;
            $this->Page->extras['no_periodo'] = [
                'recebidos' => $query->recebidos()->SumRealValorParcela(),
                'vencidos' => $query->vencidos()->SumRealValorParcela(),
                'em_cartorio' => $query->cartorios()->SumRealValorParcela(),
                'descontado' => $query->descontados()->SumRealValorParcela(),
            ];
        } else {
            $Buscas = Parcela::all();

            $this->Page->extras['no_periodo'] = [
                'recebidos' => Parcela::recebidos()->SumRealValorParcela(),
                'vencidos' => Parcela::vencidos()->SumRealValorParcela(),
                'em_cartorio' => Parcela::cartorios()->SumRealValorParcela(),
                'descontado' => Parcela::descontados()->SumRealValorParcela(),
            ];
        }

        $this->Page->extras['status_parcelas'] = StatusParcela::all();

        $this->Page->extras['a_receber'] = [
            'd10' => Parcela::getAreceber(10),
            'd20' => Parcela::getAreceber(20),
            'd30' => Parcela::getAreceber(30),
            'd60' => Parcela::getAreceber(60),
            'd90' => Parcela::getAreceber(90),
        ];

        return view('pages.' . $this->Page->link . '.index')
            ->with('Page', $this->Page)
            ->with('Buscas', $Buscas);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Recebimento = Recebimento::find($id);
        return view('pages.' . $this->Page->link . '.show')
            ->with('Page', $this->Page)
            ->with('Recebimento', $Recebimento);
    }


    public function baixarParcela(Request $request)
    {
        Pagamento::baixaParcela($request->all());
        return Redirect::route('recebimentos.index');
    }

}
