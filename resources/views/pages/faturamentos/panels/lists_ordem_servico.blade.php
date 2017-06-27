<div class="x_panel">
    <div class="x_title">
        <h2>Número de O.S. encontradas: <b>{{$OrdemServicos->count()}}</b></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                <table border="0" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Situação</th>
                        <th>ID</th>
                        <th>Nº Chamado</th>
                        <th>Abertura</th>
                        <th>Finalização</th>
                        <th>Técnico</th>
                        <th>Serviços</th>
                        <th>Peças</th>
                        <th>Kits</th>
                        <th>Total</th>
                        <th>Cliente</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($OrdemServicos as $selecao)
                        <tr>
                            <td>
                                <button class="btn btn-xs btn-{{$selecao->getStatusType()}}"
                                >{{$selecao->situacao->descricao}}</button>
                            </td>
                            <td>{{$selecao->idordem_servico}}</td>
                            <td>{{$selecao->numero_chamado}}</td>
                            <td>{{$selecao->getDataAbertura()}}</td>
                            <td>{{$selecao->getDataFinalizada()}}</td>
                            <td>{{$selecao->colaborador->nome}}</td>
                            <td>{{$selecao->fechamentoServicosTotalReal()}}</td>
                            <td>{{$selecao->fechamentoPecasTotalReal()}}</td>
                            <td>{{$selecao->fechamentoKitsTotalReal()}}</td>
                            <td>{{$selecao->fechamentoValorTotalReal()}}</td>
                            <td>{{$selecao->cliente->getType()->nome_principal}}</td>
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