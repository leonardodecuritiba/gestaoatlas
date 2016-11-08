<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="meta_key" type="text" maxlength="100" class="form-control col-md-7 col-xs-12" disabled
               value="{{(isset($Ajuste->meta_key))?$Ajuste->meta_key:old('meta_key')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Valor: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="meta_value" type="text" maxlength="100" class="form-control show-valor" required
               value="{{(isset($Ajuste->meta_value))?$Ajuste->meta_value:old('meta_value')}}">
    </div>
</div>

