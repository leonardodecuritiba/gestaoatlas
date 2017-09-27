<div class="modal fade" id="modalRequerer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">Requerer <b></b></h4>
            </div>
            {!! Form::open(['route' => [$Page->extras['type'] . '.requerer'],
                    //'id'    => 'form-selolacre',
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <div class="modal-body">
                <input type="hidden" name="opcao">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Quantidade <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control show-inteiro"
                               name="quantidade" placeholder="Quantidade" data-parsley-trigger="change" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Razão <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea class="form-control" rows="3"
                                  name="reason" placeholder="Razão" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success pull-rigth"><i class="fa fa-check fa-2"></i> Salvar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>