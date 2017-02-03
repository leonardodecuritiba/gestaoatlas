<div class="modal fade" id="modalDelecao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">Confirmar exclusão</div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a class="btn btn-danger pull-left btn-ok">Remover</a>
                <button type="button" class="btn btn-default pull-right" data-token="{{ csrf_token() }}"
                        data-dismiss="modal">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>