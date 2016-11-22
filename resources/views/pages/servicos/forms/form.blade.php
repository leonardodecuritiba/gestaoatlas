<div class="x_panel">
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome: <span class="required">*</span></label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="nome" type="text" maxlength="100" class="form-control" required
                   value="{{(isset($Servico->nome))?$Servico->nome:old('nome')}}">
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Valor: <span class="required">*</span></label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="valor" id="valor-ref" type="text" maxlength="100" class="form-control show-valor" required
                   value="{{(isset($Servico->valor))?$Servico->valor:old('valor')}}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: </label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <input name="descricao" type="text" maxlength="255" class="form-control"
                   value="{{(isset($Servico->descricao))?$Servico->descricao:old('descricao')}}">
        </div>
    </div>
</div>

<div class="x_panel" id="tabela_preco">
    @include('pages.servicos.forms.tabela_preco')
</div>

