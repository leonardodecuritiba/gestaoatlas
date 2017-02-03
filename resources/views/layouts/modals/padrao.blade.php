<div class="modal fade" id="modalPadrao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header panel-heading"></div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button class="btn pull-right btn-ok"></button>
                <button class="btn btn-default pull-left" data-token="{{ csrf_token() }}" data-dismiss="modal">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>