<div class="x_panel">
    <div class="x_title">
        <h2>Número de Clientes encontrados: <b>{{$Clientes->count()}}</b></h2>
        <div class="clearfix"></div>


    </div>
    <div class="x_content">
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Quantidade O.S</th>
                        <th>Serviços</th>
                        <th>Peças</th>
                        <th>Deslocamento</th>
                        <th>Pedágios</th>
                        <th>Total</th>
                        {{--<th>Limite</th>--}}
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($Clientes as $selecao)
                        <?php $clientType = $selecao->cliente->getType(); ?>
                        <?php $Valores = $selecao->getValoresObj(); ?>
                        <tr>
                            <td><a target="_blank"
                                   href="{{route('clientes.show', $clientType->idcliente)}}"><b>{{$clientType->nome_principal}}</b></a>
                            </td>
                            <td>
                                {{$selecao->qtd_os}}
                            </td>
                            <td>{{$Valores->valor_total_servicos}}</td>
                            <td>{{$Valores->valor_total_pecas}}</td>
                            <td>{{$Valores->valor_deslocamento}}</td>
                            <td>{{$Valores->valor_pedagios}}</td>
                            <td>{{$Valores->valor_final}}</td>
                            {{--<td>{{$selecao->limite_credito_comercial}}</td>--}}
                            {{--<td> ?? </td>--}}
                            <td>
                                <a class="btn btn-primary btn-xs"
                                   target="_blank"
                                   href="{{route('ordem_servicos.show',$selecao->idordem_servico)}}">
                                    <i class="fa fa-eye"></i> Abrir</a>
                                {{--@role('admin')--}}
                                {{--<a class="btn btn-danger btn-xs"--}}
                                {{--data-nome="Ordem de Serviço #{{$selecao->idordem_servico}}"--}}
                                {{--data-href="{{route('ordem_servicos.destroy',$selecao->idordem_servico)}}"--}}
                                {{--data-toggle="modal"--}}
                                {{--data-target="#modalDelecao"><i class="fa fa-trash-o"></i> Remover</a>--}}
                                {{--@endrole--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>