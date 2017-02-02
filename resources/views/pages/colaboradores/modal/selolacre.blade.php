<div class="modal fade" id="modalAdicionarSeloLacre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Lançar</h4>
            </div>
            {!! Form::open(['route' => ['selolacre.store',$Colaborador->tecnico->idtecnico],
                    'id'    => 'form-selolacre',
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <input type="hidden" name="opcao">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Numeração Inicial <span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control show-inteiro" min="1" name="numeracao_inicial" placeholder="Numeração Inicial" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Numeração Final <span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control show-inteiro" min="2" name="numeracao_final" placeholder="Numeração Final" required>
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
<div class="modal fade" id="modalRepassarSeloLacre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">Remanejar</h4>
            </div>
            {!! Form::open(['route' => ['selolacre.store',$Colaborador->tecnico->idtecnico],
                    'id'    => 'form-selolacre',
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <input type="hidden" name="opcao">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Numeração Inicial <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control show-inteiro" min="1" name="numeracao_inicial"
                               placeholder="Numeração Inicial" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Numeração Final <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control show-inteiro" min="2" name="numeracao_final"
                               placeholder="Numeração Final" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Técnico: <span
                                class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_single form-control" name="tipo_fornecedor" tabindex="-1">
                            @foreach($Page->extras['tecnicos'] as $tecnico)
                                <option value="{{$tecnico->idtecnico}}">{{$tecnico->colaborador->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="red obs"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success pull-rigth"><i class="fa fa-check fa-2"></i> Salvar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>