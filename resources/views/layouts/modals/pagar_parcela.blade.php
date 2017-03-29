<div id="modalPagarParcela" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="modalShow"
     aria-hidden="true">
    <div class="modal-dialog">
        {!! Form::open(['route' => 'parcelas.pagar', 'method' => 'POST', 'data-parsley-validate']) !!}
        <input type="hidden" name="id">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Pagar parcela</h4>
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
                        <label class="control-label col-md-5 col-sm-5 col-xs-12">Data de Pagamento: <span
                                    class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input type="text" class="form-control data-every" name="data_pagamento"
                                   placeholder="Data de Pagamento"
                                   value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar
                </button>
                <button class="btn btn-success pull-right"><i class="fa fa-money"></i> Pagar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        //MODAL DA FORMA DE PAGAMENTO
        $('#modalPagarParcela').on('show.bs.modal', function (event) {
            var $button = $(event.relatedTarget);
            var modal = $(this);
            var $parcela = $($button).data('parcela');
            console.log($parcela);
            $(modal).find('input[name=id]').val($parcela.id);
            $(modal).find('input[name=valor_parcela]').val($($button).data('valor_real'));
            $(modal).find('input[name=data_vencimento]').val($parcela.data_vencimento);
            $(modal).find('input[name=idforma_pagamento]').val($parcela.forma_pagamento.descricao);
        });
    });
</script>