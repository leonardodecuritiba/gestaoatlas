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

    <table border="0" class="table table-condensed table-bordered">
        <tr>
            <td>
                <table border="0" class="table table-condensed table-bordered">
                    @include('prints.ordem_servico.cliente')
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" class="table table-condensed table-bordered">
                    @include('prints.ordem_servico.cabecalho_os')
                </table>
            </td>
        </tr>
    </table>

    <table border="0" class="table table-condensed table-bordered">
        @foreach($OrdemServico->aparelho_manutencaos_totais()  as $aparelhoManutencao)
            @include('prints.ordem_servico.aparelho')
        @endforeach
    </table>

    <table border="0" class="table table-condensed table-bordered">
        <tr>
            <td>
                <table border="0" class="table table-condensed table-bordered">
                    @include('prints.ordem_servico.fechamento')
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" class="table table-condensed table-bordered">
                    @include('prints.ordem_servico.termos')
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" class="table table-condensed table-bordered">
                    @include('prints.ordem_servico.assinatura')
                </table>
            </td>
        </tr>
    </table>

@endsection