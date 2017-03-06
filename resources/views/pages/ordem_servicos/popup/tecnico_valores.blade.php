<div class="modal fade" id="modalTecnicoValores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">Aplicar Desconto/Acréscimo</h4>
            </div>
            {!! Form::open(['route' => ['ordem_servicos.aplicar_valores',$OrdemServico->idordem_servico],
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <div class="modal-body">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo: <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="tipo" class="form-control select2_single">
                            <option value="0" data-valor="{{$OrdemServico->desconto_tecnico}}">Desconto</option>
                            <option value="1" data-valor="{{$OrdemServico->acrescimo_tecnico}}">Acréscimo</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Porcentagem (%): </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control show-porcento"
                               value="{{$OrdemServico->get_desconto_tecnico_real()}}" name="valor"
                               placeholder="Porcentagem (%)">
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