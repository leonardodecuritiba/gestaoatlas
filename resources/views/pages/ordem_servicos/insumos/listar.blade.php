{{--SERVIÇOS--}}
<div class="row">
    <div class="x_panel">
        <div class="x_title">
            <h2>Serviços</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                @if($AparelhoManutencao->has_servico_prestados())
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th width="20%">Preço</th>
                            <th width="20%">Preço Mínimo</th>
                            <th width="20%">Valor Cobrado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->servico_prestados as $servico_prestado)
                            <?php
                            $tabela_preco = $servico_prestado->servico->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            ?>
                            <tr>
                                <td>
                                    {{$servico_prestado->servico->nome}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco_minimo}}
                                </td>
                                <td>
                                    R$ {{$servico_prestado->valor}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">
                            </td>
                            <td>
                                <p class="green">R$ {{$AparelhoManutencao->getTotalServicosReal()}}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    <div class="jumbotron">
                        <h2>Nenhum serviço utilizado</h2>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{--PEÇAS/PRODUTOS--}}
<div class="row">
    <div class="x_panel">
        <div class="x_title">
            <h2>Peças/Produtos</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                @if($AparelhoManutencao->has_pecas_utilizadas())
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th width="20%">Preço</th>
                            <th width="20%">Preço Mínimo</th>
                            <th width="20%">Valor Cobrado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->pecas_utilizadas as $peca_utilizada)
                            <?php
                            $tabela_preco = $peca_utilizada->peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            ?>
                            <tr>
                                <td>
                                    {{$peca_utilizada->peca->descricao}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco_minimo}}
                                </td>
                                <td>
                                    R$ {{$peca_utilizada->valor}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">
                            </td>
                            <td>
                                <p class="green">R$ {{$AparelhoManutencao->getTotalPecasReal()}}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    <div class="jumbotron">
                        <h2>Nenhuma peça/produto utilizada</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{--KITS--}}
<div class="row">
    <div class="x_panel">
        <div class="x_title">
            <h2>Kits</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                @if($AparelhoManutencao->has_kits_utilizados())
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th width="20%">Preço</th>
                            <th width="20%">Preço Mínimo</th>
                            <th width="20%">Valor Cobrado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->kits_utilizados as $kit_utilizado)
                            <?php
                            $tabela_preco = $kit_utilizado->kit->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                            ?>
                            <tr>
                                <td>
                                    {{$kit_utilizado->nome()}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco_minimo}}
                                </td>
                                <td>
                                    R$ {{$kit_utilizado->valor}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">
                            </td>
                            <td>
                                <p class="green">R$ {{$AparelhoManutencao->getTotalKitsReal()}}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    <div class="jumbotron">
                        <h2>Nenhum kit utilizado</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>