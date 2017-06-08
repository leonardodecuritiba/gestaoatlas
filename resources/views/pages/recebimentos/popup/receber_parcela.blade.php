<div id="modalPagarParcela" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="modalShow"
     aria-hidden="true">
    <div class="modal-dialog">
        {!! Form::open(['route' => 'parcelas.baixar', 'method' => 'POST', 'data-parsley-validate']) !!}
        <input type="hidden" name="id">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Baixar parcela</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Valor: <span class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input name="valor_parcela" type="text" maxlength=s"50" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Vencimento: <span
                                    class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input name="data_vencimento" type="text" maxlength="50" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Forma de Pagamento: <span
                                    class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input name="idforma_pagamento" type="text" maxlength="50" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Status: <span
                                    class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <select name="idstatus_parcela" class="form-control select2_single">
                                @foreach($Page->extras['status_parcelas'] as $status_parcela)
                                    <option value="{{$status_parcela->id}}">{{$status_parcela->descricao}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Data de Pagamento: <span
                                    class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input type="text" class="form-control data-every" name="data_pagamento"
                                   placeholder="Data de Pagamento"
                                   value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar
                </button>
                <a target="_blank" class="btn btn-default btn-open pull-left"><i class="fa fa-eye"></i> Faturamento</a>
                <button class="btn btn-success pull-right"><i class="fa fa-money"></i> Baixar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>