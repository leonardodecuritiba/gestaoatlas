<tr>
    <td colspan="7">
        <table border="1" class="table table-condensed table-bordered">
            <tr class="fundo_titulo">
                <th class="linha_titulo" colspan="8">FECHAMENTO DE VALORES</th>
            </tr>
            @if(count($OrdemServico->fechamentoServicos())>0)
                <?php
                $Servicos = $OrdemServico->fechamentoServicos();
                $Total = $OrdemServico->fechamentoServicosTotalReal();
                ?>
                @include('prints.ordem_servico.servico')
            @endif

            @if(count($OrdemServico->fechamentoPecas())>0)
                <?php
                $Pecas = $OrdemServico->fechamentoPecas();
                $Total = $OrdemServico->fechamentoPecasTotalReal();
                ?>
                @include('prints.ordem_servico.peca')
            @endif

            @if(count($OrdemServico->fechamentoKits())>0)
                <?php
                $Kits = $OrdemServico->fechamentoKits();
                $Total = $OrdemServico->fechamentoKitsTotalReal();
                ?>
                @include('prints.ordem_servico.kit')
            @endif
            <tr class="fundo_titulo_3">
                <th class="linha_titulo" colspan="8">OUTROS</th>
            </tr>
            <tr class="campo">
                <th colspan="7">DESCRIÇÃO</th>
                <th>V. TOTAL</th>
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


