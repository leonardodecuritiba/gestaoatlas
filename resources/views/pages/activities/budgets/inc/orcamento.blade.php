<div class="modal fade" id="modalOrcamento" tabindex="-2" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Orçamento</h2>
            </div>
            <div class="modal-body">
                @include('pages.activities.budgets.inc.details')
            </div>
            <div class="modal-footer">
                <a class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times fa-2"></i> Fechar</a>
                <a class="btn btn-success pull-right"
                    href="{{route('budgets.summary',$Data->id)}}">
                    <i class="fa fa-check fa-2"></i> Finalizar
                </a>
                <a class="btn btn-warning pull-right" target="_blank"
                   href="{{route('clientes.show',$Data->client_id)}}">
                    <i class="fa fa-eye"></i> Cliente</a>
                <a class="btn btn-danger pull-right"
                   data-nome="Orçamento #{{$Data->id}}"
                   data-href="{{route('budgets.destroy',$Data->id)}}"
                   data-toggle="modal"
                   data-target="#modalDelecao">
                    <i class="fa fa-trash-o"></i> Remover</a>
            </div>
        </div>
    </div>
</div>