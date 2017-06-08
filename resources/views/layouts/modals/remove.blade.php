<style>
    .removido{
        /*rgba(255,0,0,0.7)    /* 70% opaque red */
        color: red;
        background: rgba(255, 0, 0, 0.1);
        filter:alpha(opacity=0.4); /* IE */
        -moz-opacity:0.1; /* Mozilla */
        opacity: 0.4; /* CSS3 */
    }
</style>
<div class="modal fade" data-modal-color="red" id="modalRemocao" data-backdrop="static"
     data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmar exclusão</h4>
            </div>
            <div class="modal-body"><p></p></div>
            <div class="modal-footer">
                <a class="btn btn-link pull-left btn-ok">Remover</a>
                <button type="button" class="btn btn-link pull-right" data-token="{{ csrf_token() }}"
                        data-dismiss="modal">Cancelar
                </button>
            </div>
        </div>
    </div>
</div>