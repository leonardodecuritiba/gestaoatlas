<div class="x_title">
    <h2>Principal</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content" id="peca-container">
    @if(isset($Peca->foto) && ($Peca->foto != ""))
        <div class="form-group">
            <div class="col-md-offset-4 col-sm-offset-4 col-xs-4 col-md-4 col-sm-4 col-xs-12">
                <div class="peca_image">
                    <img src="{{$Peca->getFoto()}}" width="70%" />
                </div>
            </div>
        </div>
        <div class="ln_solid"></div>
    @endif
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Foto:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <input name="foto" type="file" class="form-control">
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Fornecedor:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <select name="idfornecedor" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha o Fornecedor</option>
                @foreach($Page->extras['fornecedores'] as $sel)
                    <option value="{{$sel->idfornecedor}}"
                            @if((isset($Peca->idfornecedor) && $Peca->idfornecedor==$sel->idfornecedor) || (old('idfornecedor') == $sel->idfornecedor)) selected @endif
                    >{{$sel->getType()->nome_principal}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Tipo:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <select name="tipo" class="select2_single form-control" tabindex="-1" required>
                <option value="peca"
                        @if((isset($Peca->tipo) && $Peca->tipo=='peca') || (old('tipo') == 'peca')) selected @endif
                >Peça
                </option>
                <option value="produto"
                        @if((isset($Peca->tipo) && $Peca->tipo=='produto') || (old('tipo') == 'produto')) selected @endif
                >Produto
                </option>
            </select>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Garantia (meses):</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <input name="garantia" type="text" class="form-control show-meses" placeholder="Garantia" required
                   value="{{(isset($Peca->garantia))?$Peca->garantia:old('garantia')}}"
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Cód. de Barras:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <input name="codigo_barras" type="text" class="form-control" placeholder="Código de Barras"
                   value="{{(isset($Peca->codigo_barras))?$Peca->codigo_barras:old('codigo_barras')}}"
            >
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Cód. Auxiliar:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <input name="codigo_auxiliar" type="text" class="form-control" placeholder="Código Auxiliar"
                   value="{{(isset($Peca->codigo_auxiliar))?$Peca->codigo_auxiliar:old('codigo_auxiliar')}}"
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Descrição:</label>
        <div class="col-md-10 col-sm-10 col-xs-12 form-group">
            <input name="descricao" type="text" class="form-control" placeholder="Descrição" required
                   value="{{(isset($Peca->descricao))?$Peca->descricao:old('descricao')}}"
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Desc. Técnico:</label>
        <div class="col-md-10 col-sm-10 col-xs-12 form-group">
            <input name="descricao_tecnico" type="text" class="form-control" placeholder="Descrição Técnico" required
                   value="{{(isset($Peca->descricao_tecnico))?$Peca->descricao_tecnico:old('descricao_tecnico')}}"
            >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Marca:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <select name="idmarca" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha a Marca</option>
                @foreach($Page->extras['marcas'] as $sel)
                    <option value="{{$sel->idmarca}}"
                            @if((isset($Peca->idmarca) && $Peca->idmarca==$sel->idmarca) || (old('idmarca') == $sel->idmarca)) selected @endif
                    >{{$sel->descricao}}</option>
                @endforeach
            </select>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Unidade:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <select name="idunidade" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha a Unidade</option>
                @foreach($Page->extras['unidades'] as $sel)
                    <option value="{{$sel->idunidade}}"
                            @if((isset($Peca->idunidade) && $Peca->idunidade==$sel->idunidade) || (old('idunidade') == $sel->idunidade)) selected @endif
                    >{{$sel->codigo}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Grupo:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            {{--<input name="grupo" type="text" class="form-control" placeholder="Grupo" >--}}
            <select name="idgrupo" class="select2_single form-control" tabindex="-1" required>
                <option value="">Escolha o Grupo</option>
                @foreach($Page->extras['grupos'] as $sel)
                    <option value="{{$sel->idgrupo}}"
                            @if((isset($Peca->idgrupo) && $Peca->idgrupo==$sel->idgrupo) || (old('idgrupo') == $sel->idgrupo)) selected @endif
                    >{{$sel->descricao}}</option>
                @endforeach
            </select>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Subgrupo:</label>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
            <input name="sub_grupo" type="text" class="form-control" placeholder="Subgrupo"
                   value="{{(isset($Peca->sub_grupo))?$Peca->sub_grupo:old('sub_grupo')}}"
            >
        </div>
    </div>
</div>
