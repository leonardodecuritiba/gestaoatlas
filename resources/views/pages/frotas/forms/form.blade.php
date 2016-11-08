<div class="form-group">
    <label class="control-label col-md-1 col-sm-1 col-xs-12">Veículo: <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input name="veiculo" type="text" maxlength="10" class="form-control col-md-7 col-xs-12" required="required"
               value="{{(isset($Frota->veiculo))?$Frota->veiculo:''}}">
    </div>
    <label class="control-label col-md-1 col-sm-1 col-xs-12">Ano: <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input name="ano" type="text" maxlength="4" class="form-control" required="required"
               value="{{(isset($Frota->ano))?$Frota->ano:''}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-1 col-sm-1 col-xs-12">Responsável: <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input name="idcolaborador" type="text" maxlength="10" class="form-control col-md-7 col-xs-12" required="required"
               value="{{(isset($Frota->idcolaborador))?$Frota->colaborador->nome:''}}">
    </div>
    <label class="control-label col-md-1 col-sm-1 col-xs-12">Placa: <span class="required">*</span></label>
    <div class="col-md-5 col-sm-5 col-xs-12">
        <input name="placa" type="text" maxlength="7" class="form-control" required="required"
               value="{{(isset($Frota->placa))?$Frota->placa:''}}">
    </div>
</div>
<div class="ln_solid"></div>

