<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Kit: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="nome" type="text" maxlength="100" class="form-control" required
               value="{{(isset($Kit->nome))?$Kit->nome:old('nome')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="200" class="form-control" required
               value="{{(isset($Kit->descricao))?$Kit->descricao:old('descricao')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Observação: </label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <textarea name="observacao" maxlength="200" class="form-control"
        >{{(isset($Kit->observacao))?$Kit->observacao:old('observacao')}}</textarea>
    </div>
</div>



