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