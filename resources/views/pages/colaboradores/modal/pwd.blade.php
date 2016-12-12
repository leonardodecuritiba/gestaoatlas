<div class="modal fade" id="modalPWD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">Atualizar Senha</h4>
            </div>
            {!! Form::open(['route' => ['colaboradores.upd_pass',Auth::user()->iduser],
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Senha <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="password" class="form-control" name="password" placeholder="Senha" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar Senha <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Senha"
                               required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success pull-rigth"><i class="fa fa-check fa-2"></i> Atualizar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>