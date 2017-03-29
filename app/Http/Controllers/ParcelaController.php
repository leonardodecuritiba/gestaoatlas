<?php

namespace App\Http\Controllers;

use App\Helpers\BoletoHelper;
use App\Models\Parcela;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ParcelaController extends Controller
{
    public function pagar($idparcela)
    {

    }

    public function gerarBoleto($idparcela)
    {
        $Parcela = Parcela::findOrFail($idparcela);
        $Boleto = new BoletoHelper();
        $Boleto->setBoletoParcela($Parcela);
        return $Boleto->gerarPDF();

    }

    public function estornar($idparcela)
    {
        //
    }

}
