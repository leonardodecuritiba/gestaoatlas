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
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Grupo:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            {{--<input name="grupo" type="text" class="form-control" placeholder="Grupo" >--}}
            <select name="idgrupo" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha o Grupo</option>
                @foreach($Page->extras['grupos'] as $opt)
                    <option value="{{$opt->idgrupo}}"
                            @if(isset($Servico->idgrupo) && $Servico->idgrupo==$opt->idgrupo) selected @endif
                    >{{$opt->descricao}}</option>
                @endforeach
            </select>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Unidade:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <select name="idunidade" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha a Unidade</option>
                @foreach($Page->extras['unidades'] as $opt)
                    <option value="{{$opt->idunidade}}"
                            @if(isset($Servico->idunidade) && $Servico->idunidade==$opt->idunidade) selected @endif
                    >{{$opt->codigo}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="x_panel" id="tabela_preco">
    @include('pages.servicos.forms.tabela_preco')
</div>

