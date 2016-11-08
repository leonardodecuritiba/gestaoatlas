<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Código: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="codigo" type="text" maxlength="100" class="form-control col-md-7 col-xs-12" required
               value="{{(isset($Unidade->codigo))?$Unidade->codigo:old('codigo')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="100" class="form-control col-md-7 col-xs-12" required
               value="{{(isset($Unidade->descricao))?$Unidade->descricao:old('descricao')}}">
    </div>
</div>

