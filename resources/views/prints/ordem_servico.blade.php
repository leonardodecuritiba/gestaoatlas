@extends('prints.template')
@section('body_content')
    <?php
    $Cliente = $OrdemServico->cliente;
    $ClienteType = $Cliente->getType();
    $Contato = $Cliente->contato;
    ?>
    <table border="0" class="table table-condensed table-bordered">
        @include('prints.ordem_servico.empresa')
    </table>
    <table border="1" class="table table-condensed table-bordered">
        @include('prints.ordem_servico.cliente')
    </table>
    <table border="1" class="table table-condensed table-bordered">
        @include('prints.ordem_servico.cabecalho_os')
    </table>

    <table border="1" class="table table-condensed table-bordered">
        @foreach($OrdemServico->aparelho_manutencaos_totais()  as $aparelhoManutencao)
            @include('prints.ordem_servico.aparelho')
        @endforeach
    </table>

    <table border="1" class="table table-condensed table-bordered">
        @include('prints.ordem_servico.fechamento')
    </table>

    <table border="1" class="table table-condensed table-bordered">
        <tr class="fundo_titulo">
            <th class="linha_titulo" colspan="7">TERMOS / AVISOS</th>
        </tr>
        @include('prints.ordem_servico.termos')
        @include('prints.ordem_servico.assinatura')
    </table>
@endsection