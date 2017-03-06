<div class="x_title">
    <h2>Dados do Financeiro</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Desconto Máx. (%) <span
                        class="required">*</span></label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->tecnico->desconto_max:old('desconto_max')}}"
                       type="text" class="form-control show-porcento" name="desconto_max"
                       placeholder="Desconto Máx. (%)"
                       @role('admin') required @else disabled @endif>
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Acréscimo Máx. (%) <span class="required">*</span></label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->tecnico->acrescimo_max:old('acrescimo_max')}}"
                       type="text" class="form-control show-porcento" name="acrescimo_max"
                       placeholder="Acréscimo Máx. (%)"
                       @role('admin') required @else disabled @endif>
            </div>
        </div>
    </div>
</div>