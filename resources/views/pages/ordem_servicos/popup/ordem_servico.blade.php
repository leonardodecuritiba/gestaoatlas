<div class="modal fade" id="modalPopup" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ordem de Serviço</h2>
            </div>
            <div class="modal-body">
                @include('pages.ordem_servicos.parts.cliente_show')
            </div>
            <div class="modal-footer">
                <a class="btn btn-success pull-left"
                    href="{{route('ordem_servicos.resumo',$OrdemServico->idordem_servico)}}">
                    <i class="fa fa-check fa-2"></i> Fechar O.S.
                </a>
                <a class="btn btn-warning pull-left" target="_blank"
                   href="{{route('clientes.show',$OrdemServico->idcliente)}}">
                    <i class="fa fa-eye"></i> Consultar Cliente</a>
                <a class="btn btn-danger pull-left"
                   data-nome="Ordem de Serviço #{{$OrdemServico->idordem_servico}}"
                   data-href="{{route('ordem_servicos.destroy',$OrdemServico->idordem_servico)}}"
                   data-toggle="modal"
                   data-target="#modalDelecao">
                    <i class="fa fa-trash-o"></i> Remover O.S.</a>
                <button class="btn btn-default pull-rigth" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>