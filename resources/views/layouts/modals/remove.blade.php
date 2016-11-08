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
<div class="modal fade" id="modalRemocao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">Confirmar exclusão</div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a class="btn btn-danger btn-ok">Remover</a>
                <button type="button" class="btn btn-default" data-token="{{ csrf_token() }}" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>