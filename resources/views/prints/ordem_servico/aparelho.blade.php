<table class="table table-condensed table-bordered">
    <tr>
        <td style="height: 3px !important;">
            <table class="table table-condensed table-bordered">
                @if($aparelhoManutencao->has_instrumento())
                    @include('prints.ordem_servico.instrumento')
                @else
                    @include('prints.ordem_servico.equipamento')
                @endif
                <tr>
                    <th colspan="3">Defeito:</th>
                    <th colspan="4">Solução:</th>
                </tr>
                <tr>
                    <td colspan="3">{{$aparelhoManutencao->defeito}}</td>
                    <td colspan="4">{{$aparelhoManutencao->solucao}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table border="1" class="table table-condensed table-bordered">
                @if($aparelhoManutencao->has_servico_prestados())
                    <?php
                    $Servicos = $aparelhoManutencao->servico_prestados;
                    $Total = $aparelhoManutencao->total_servicos_real;
                    ?>
                    @include('prints.ordem_servico.servico')
                @endif
                @if($aparelhoManutencao->has_pecas_utilizadas())
                    <?php
                    $Pecas = $aparelhoManutencao->pecas_utilizadas;
                    $Total = $aparelhoManutencao->total_pecas_real;
                    ?>
                    @include('prints.ordem_servico.peca')
                @endif
                @if($aparelhoManutencao->has_kits_utilizados())
                    <?php
                    $Kits = $aparelhoManutencao->kits_utilizados;
                    $Total = $aparelhoManutencao->total_kits_real;
                    ?>
                    @include('prints.ordem_servico.kit')
                @endif
            </table>
        </td>
    </tr>
</table>