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
        <tr class="fundo_titulo">
            <th class="linha_titulo" colspan="6">Ordem Serviço n° {{$OrdemServico->idordem_servico}}</th>
        </tr>
        @include('prints.ordem_servico.cliente')
    </table>
    @foreach($OrdemServico->aparelho_manutencaos_totais()  as $aparelhoManutencao)
        @include('prints.ordem_servico.aparelho')
    @endforeach

    @include('prints.ordem_servico.fechamento')

@endsection