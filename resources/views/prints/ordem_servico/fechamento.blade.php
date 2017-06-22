<table border="1" class="table table-condensed table-bordered">
    <tr>
        <td colspan="7">
            <table border="1" class="table table-condensed table-bordered">
                <tr class="fundo_titulo">
                    <th class="linha_titulo" colspan="7">Fechamento de Valores</th>
                </tr>
                @if($aparelhoManutencao->has_servico_prestados())
                    <?php
                    $Servicos = $OrdemServico->fechamentoServicos();
                    $Total = $OrdemServico->fechamentoServicosTotalReal();
                    ?>
                    @include('prints.ordem_servico.servico')
                @endif

                @if($aparelhoManutencao->has_pecas_utilizadas())
                    <?php
                    $Pecas = $OrdemServico->fechamentoPecas();
                    $Total = $OrdemServico->fechamentoPecasTotalReal();
                    ?>
                    @include('prints.ordem_servico.peca')
                @endif

                @if($aparelhoManutencao->has_kits_utilizados())
                    <?php
                    $Kits = $OrdemServico->fechamentoKits();
                    $Total = $OrdemServico->fechamentoKitsTotalReal();
                    ?>
                    @include('prints.ordem_servico.kit')
                @endif
                <tr class="fundo_titulo_3">
                    <th class="linha_titulo" colspan="7">Outros</th>
                </tr>
                <tr>
                    <th colspan="6">Descrição</th>
                    <th>V. Total</th>
                </tr>
                <tr>
                    <td colspan="6">Deslocamento</td>
                    <th>{{$OrdemServico->getCustosDeslocamentoReal()}}</th>
                </tr>
                <tr>
                    <td colspan="6">Pedágios</td>
                    <th>{{$OrdemServico->getPedagiosReal()}}</th>
                </tr>
                <tr>
                    <td colspan="6">Outros Custos</td>
                    <th>{{$OrdemServico->getOutrosCustosReal()}}</th>
                </tr>
                <tr>
                    <td colspan="6">Descontos</td>
                    <th>{{$OrdemServico->getDescontoTecnicoReal()}}</th>
                </tr>
                <tr>
                    <td colspan="6">Acréscimos</td>
                    <th>{{$OrdemServico->getAcrescimoTecnicoReal()}}</th>
                </tr>
                <tr>
                    <th colspan="6">Total da Ordem de Serviço</th>
                    <th>{{$OrdemServico->getValorFinalReal()}}</th>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="fundo_titulo">
        <th class="linha_titulo" colspan="7">Termos / Avisos</th>
    </tr>

    @include('prints.ordem_servico.termos')


    @include('prints.ordem_servico.assinatura')
</table>

