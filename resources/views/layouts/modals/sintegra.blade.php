<div class="modal fade" id="captchaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loading"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Leitor Captcha</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal form-label-left">
                    <img />
                    <br /><br />
                    <input type="hidden" name="cookie" />
                    <input type="hidden" name="paramBot" />
                    <div class="form-group">
                        <input type="text" name="captcha" class="form-control" maxlength="5" placeholder="Informe os caracteres da imagem acima"/>
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button class="btn btn-success pull-right" id="consultarCNPJ">Consultar</button>
            </div>
        </div>
    </div>
</div>