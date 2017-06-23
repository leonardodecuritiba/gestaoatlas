<table border="1" class="table table-condensed table-bordered">
    <tr>
        <td colspan="7">
            <table border="1" class="table table-condensed table-bordered">
                <tr class="fundo_titulo">
                    <th class="linha_titulo" colspan="8">Fechamento de Valores</th>
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
                    <th class="linha_titulo" colspan="8">Outros</th>
                </tr>
                <tr class="row_cabecalho">
                    <th colspan="7">Descrição</th>
                    <th>V. Total</th>
                </tr>
                <tr>
                    <td colspan="7">Deslocamento</td>
                    <td class="valor">{{$OrdemServico->getCustosDeslocamentoReal()}}</td>
                </tr>
                <tr>
                    <td colspan="7">Pedágios</td>
                    <td class="valor">{{$OrdemServico->getPedagiosReal()}}</td>
                </tr>
                <tr>
                    <td colspan="7">Outros Custos</td>
                    <td class="valor">{{$OrdemServico->getOutrosCustosReal()}}</td>
                </tr>
                <tr>
                    <td colspan="7">Descontos</td>
                    <td class="valor">{{$OrdemServico->getDescontoTecnicoReal()}}</td>
                </tr>
                <tr>
                    <td colspan="7">Acréscimos</td>
                    <td class="valor">{{$OrdemServico->getAcrescimoTecnicoReal()}}</td>
                </tr>
                <tr>
                    <th colspan="7">Total da Ordem de Serviço</th>
                    <th class="valor">{{$OrdemServico->getValorFinalReal()}}</th>
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

