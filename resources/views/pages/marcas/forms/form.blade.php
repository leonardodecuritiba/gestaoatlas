<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="100" class="form-control col-md-7 col-xs-12" required
               value="{{(isset($Marca->descricao))?$Marca->descricao:old('descricao')}}">
    </div>
</div>

