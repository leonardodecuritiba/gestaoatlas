<?php

namespace App\Http\Controllers;

use App\Helpers\BoletoHelper;
use App\Models\Pagamento;
use App\Models\Parcela;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ParcelaController extends Controller
{
    public function pagar(Request $request)
    {
        $Pagamento = Pagamento::baixaParcela($request->all());
        return Redirect::route('faturamentos.show', $Pagamento->faturamento->id);
    }

    public function gerarBoleto($id)
    {
        $Parcela = Parcela::findOrFail($id);
        $Boleto = new BoletoHelper();
        $Boleto->setBoletoParcela($Parcela);
        return $Boleto->gerarPDF();

    }

    public function estornar($idparcela)
    {
        //
    }

}
