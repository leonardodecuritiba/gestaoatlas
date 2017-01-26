<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Código: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="codigo" type="text" maxlength="50" class="form-control" required
               value="{{(isset($CategoriaTributacao->codigo))?$CategoriaTributacao->codigo:old('codigo')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="50" class="form-control" required
               value="{{(isset($CategoriaTributacao->descricao))?$CategoriaTributacao->descricao:old('descricao')}}">
    </div>
</div>

