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