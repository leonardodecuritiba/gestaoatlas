{!! Form::open(['route' => ['ordem_servicos.add_insumos',$OrdemServico->idordem_servico ],
                'method' => 'POST',
                'files' => true,
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
<input name="idaparelho_manutencao" type="hidden" value="{{$AparelhoManutencao->idaparelho_manutencao}}">

{{--SERVIÇOS--}}
@if(count($Servicos)>0)
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Serviços</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Desconto</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->servico_prestados as $servico_prestado)
                            <?php
                            $tabela_preco = $servico_prestado->servico->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                            ?>
                            <tr>
                                <td>
                                    {{$servico_prestado->servico->nome}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco}}
                                </td>
                                <td>
                                    {{$servico_prestado->quantidade}}
                                </td>
                                <td>
                                    {{$servico_prestado->desconto_real()}}
                                </td>
                                <td>
                                    {{$servico_prestado->valor_total_real()}}
                                </td>
                                <td>
                                    <a class="btn btn-danger"
                                       data-nome="{{$servico_prestado->servico->nome}}"
                                       data-href="{{route('servico_prestados.destroy',$servico_prestado->idservico_prestado)}}"
                                       data-toggle="modal"
                                       data-target="#modalRemocao"
                                       data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                        <i class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <select class="select2_single form-control" name="idservico" id="idservico"
                                        tabindex="-1">
                                    <option value="">Selecione</option>
                                    @foreach($Servicos as $servico)
                                        <?php
                                        $tabela_preco = $servico->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        //                                                        print_r($tabela_preco);
                                        ?>
                                        {{--<option>{{$tabela_preco}}</option>--}}
                                        <option value="{{$servico->idservico}}"
                                                data-preco="{{$tabela_preco->preco}}"
                                                data-preco-float="{{$tabela_preco->preco_float()}}">{{$servico->nome}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input disabled id="valor" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                <input id="quantidade" value="1" type="text"
                                       class="form-control show-inteiro-positivo calc-total"
                                       placeholder="Quantidade">
                            </td>

                            <td>
                                <input id="desconto" value="0,00" type="text"
                                       class="form-control show-valor calc-total"
                                       placeholder="Desconto"
                                       @role('tecnico') disabled @endrole>
                            </td>
                            <td>
                                <input disabled id="total" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                <a class="btn btn-success add"
                                   data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                    <i class="fa fa-check fa-lg"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@else
    <div class="jumbotron">
        <h2>Nenhum Serviço cadastrado no sistema!</h2>
    </div>
@endif
{{--{{dd(1)}}--}}
{{--PEÇAS/PRODUTOS--}}
{{--@include('pages.ordem_servicos.insumos.pecas')--}}
@if(count($Pecas)>0)
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Peças/Produtos</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Desconto</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->pecas_utilizadas as $peca_utilizada)
                            <?php
                            $tabela_preco = $peca_utilizada->peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                            ?>
                            <tr>
                                <td>
                                    {{$peca_utilizada->peca->descricao}}
                                </td>
                                <td>
                                    R$ {{$peca_utilizada->valor}}
                                </td>
                                <td>
                                    {{$peca_utilizada->quantidade}}
                                </td>
                                <td>
                                    {{$peca_utilizada->desconto_real()}}
                                </td>
                                <td>
                                    {{$peca_utilizada->valor_total_real()}}
                                </td>
                                <td>
                                    <a class="btn btn-danger"
                                       data-nome="{{$peca_utilizada->nome}}"
                                       data-href="{{route('pecas_utilizadas.destroy',$peca_utilizada->id)}}"
                                       data-toggle="modal"
                                       data-target="#modalRemocao"
                                       data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                        <i class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <select class="select2_single form-control" id="idpeca" name="idpeca"
                                        tabindex="-1">
                                    <option value="">Selecione</option>
                                    @foreach($Pecas as $peca)
                                        <?php
                                        $tabela_preco = $peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        ?>
                                        <option value="{{$peca->idpeca}}"
                                                data-preco="{{$tabela_preco->preco}}"
                                                data-preco-float="{{$tabela_preco->preco_float()}}">
                                            {{$peca->descricao}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input disabled id="valor" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                <input id="quantidade" value="1" type="text"
                                       class="form-control show-inteiro-positivo calc-total"
                                       placeholder="Quantidade">
                            </td>
                            <td>
                                <input id="desconto" value="0,00" type="text"
                                       class="form-control show-valor calc-total"
                                       placeholder="Desconto"
                                       @role('tecnico') disabled @endrole>
                            </td>
                            <td>
                                <input disabled id="total" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                <a class="btn btn-success add"
                                   data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                    <i class="fa fa-check fa-lg"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@else
    <div class="jumbotron">
        <h2>Nenhuma Peça cadastrada no sistema!</h2>
    </div>
@endif

{{--KITS--}}
@if(count($Kits)>0)
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Kits</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table border="0" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="40%">Nome</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Desconto</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($AparelhoManutencao->kits_utilizados as $kit_utilizado)
                            <?php
                            $tabela_preco = $kit_utilizado->kit->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                            ?>
                            <tr>
                                <td>
                                    {{$kit_utilizado->nome()}}
                                </td>
                                <td>
                                    R$ {{$tabela_preco->preco}}
                                </td>
                                <td>
                                    {{$kit_utilizado->quantidade}}
                                </td>
                                <td>
                                    {{$kit_utilizado->desconto_real()}}
                                </td>
                                <td>
                                    {{$kit_utilizado->valor_total_real()}}
                                </td>
                                <td>
                                    {{--<a class="btn btn-default"--}}
                                    {{--data-toggle-tooltip="tooltip" data-placement="top" title="Ver detalhes"--}}
                                    {{--><i class="fa fa-eye fa-lg"></i></a>--}}
                                    {{--<a class="btn btn-primary"--}}
                                    {{--data-toggle-tooltip="tooltip" data-placement="top" title="Editar">--}}
                                    {{--<i class="fa fa-edit fa-lg"></i></a>--}}
                                    <a class="btn btn-danger"
                                       data-nome="{{$kit_utilizado->nome()}}"
                                       data-href="{{route('kits_utilizados.destroy',$kit_utilizado->id)}}"
                                       data-toggle="modal"
                                       data-target="#modalRemocao"
                                       data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                        <i class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <select class="select2_single form-control" id="idkit" name="idkit"
                                        tabindex="-1">
                                    <option value="">Selecione</option>
                                    @foreach($Kits as $kit)
                                        <?php
                                        $tabela_preco = $peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        ?>
                                        <option value="{{$kit->idkit}}"
                                                data-preco="{{$tabela_preco->preco}}"
                                                data-preco-float="{{$tabela_preco->preco_float()}}">{{$kit->descricao}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input disabled id="valor" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                <input id="quantidade" value="1" type="text"
                                       class="form-control show-inteiro-positivo calc-total"
                                       placeholder="Quantidade">
                            </td>
                            <td>
                                <input id="desconto" value="0,00" type="text"
                                       class="form-control show-valor calc-total"
                                       placeholder="Desconto"
                                       @role('tecnico') disabled @endrole>
                            </td>
                            <td>
                                <input disabled id="total" class="form-control show-valor-fixo">
                            </td>
                            <td>
                                {{--<a class="btn btn-default"--}}
                                {{--data-toggle-tooltip="tooltip" data-placement="top" title="Ver detalhes"--}}
                                {{--><i class="fa fa-eye fa-lg"></i></a>--}}
                                <a class="btn btn-success add"
                                   data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                    <i class="fa fa-check fa-lg"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@else
    <div class="jumbotron">
        <h2>Nenhum Kit cadastrado no sistema!</h2>
    </div>
@endif

{{--FECHAR--}}
<section class="row">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-12 pull-right ">
                <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
            </div>
        </div>
    </div>
</section>
{!! Form::close() !!}