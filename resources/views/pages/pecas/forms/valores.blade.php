<div class="x_title">
    <h2>Valores</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content" id="peca-container">
    {{--COMISSÕES--}}
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Comissão Téc.:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="comissao_tecnico" type="text" class="form-control show-porcento" placeholder="Comissão Técnico"
                   value="{{(isset($Peca->comissao_tecnico))?$Peca->comissao_tecnico:old('comissao_tecnico')}}"
            >
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Comissão Vend.:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="comissao_vendedor" type="text" class="form-control show-porcento" placeholder="Comissão Vendedor"
                   value="{{(isset($Peca->comissao_vendedor))?$Peca->comissao_vendedor:old('comissao_vendedor')}}"
            >
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Valor Frete:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="valor_frete" type="text" class="form-control show-valor"
                   placeholder="Valor Frete"
                   value="{{(isset($Peca->peca_tributacao->valor_frete))?$Peca->peca_tributacao->valor_frete:old('valor_frete')}}"
            >
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Valor Seguro:</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input name="valor_seguro" type="text" class="form-control show-valor"
                   placeholder="Valor Seguro"
                   value="{{(isset($Peca->peca_tributacao->valor_seguro))?$Peca->peca_tributacao->valor_seguro:old('valor_seguro')}}"
            >
        </div>
    </div>

    {{--CUSTO REAIS--}}
    <div id="custo_reais">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Final:</label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <input name="custo_final" id="valor-ref" type="text" class="form-control show-valor"
                   placeholder="Custo Final"
                   value="{{(isset($Peca->peca_tributacao->custo_final))?$Peca->peca_tributacao->custo_final:old('custo_final')}}"
            >
        </div>
    </div>

    {{--CUSTO REAIS--}}

    {{----}}
    {{--<div id="custo_reais">--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Compra:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_compra" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Compra"--}}
                       {{--value="{{(isset($Peca->custo_compra))?$Peca->custo_compra:old('custo_compra')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Frete:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_frete" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Frete"--}}
                       {{--value="{{(isset($Peca->custo_frete))?$Peca->custo_frete:old('custo_frete')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Imposto:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_imposto" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Imposto"--}}
                       {{--value="{{(isset($Peca->custo_imposto))?$Peca->custo_imposto:old('custo_imposto')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Final:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_final" disabled type="text" class="form-control show-valor" placeholder="Custo Final"--}}
                       {{--value="{{(isset($Peca->custo_final))?$Peca->custo_final:old('custo_final')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="ln_solid"></div>--}}
    {{--</div>--}}

    {{--CUSTO DÓLAR--}}
    {{--<div id="custo_dolar">--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Câmbio:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_dolar_cambio" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Câmbio"--}}
                       {{--value="{{(isset($Peca->custo_dolar_cambio))?$Peca->custo_dolar_cambio:old('custo_dolar_cambio')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_dolar" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar"--}}
                       {{--value="{{(isset($Peca->custo_dolar))?$Peca->custo_dolar:old('custo_dolar')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Frete:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_dolar_frete" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Frete"--}}
                       {{--value="{{(isset($Peca->custo_dolar_frete))?$Peca->custo_dolar_frete:old('custo_dolar_frete')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Imposto:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="custo_dolar_imposto" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Imposto"--}}
                       {{--value="{{(isset($Peca->custo_dolar_imposto))?$Peca->custo_dolar_imposto:old('custo_dolar_imposto')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="preco" disabled type="text" class="form-control show-valor" placeholder="Preço"--}}
                       {{--value="{{(isset($Peca->preco))?$Peca->preco:old('preco')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Frete:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="preco_frete" disabled type="text" class="form-control show-valor" placeholder="Preço Frete"--}}
                       {{--value="{{(isset($Peca->preco_frete))?$Peca->preco_frete:old('preco_frete')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Imposto:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="preco_imposto" disabled type="text" class="form-control show-valor" placeholder="Preço Imposto"--}}
                       {{--value="{{(isset($Peca->preco_imposto))?$Peca->preco_imposto:old('preco_imposto')}}"--}}
                {{-->--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Final:</label>--}}
            {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
                {{--<input name="preco_final" disabled  type="text" class="form-control show-valor" placeholder="Preço Final"--}}
                       {{--value="{{(isset($Peca->preco_final))?$Peca->preco_final:old('preco_final')}}"--}}
                {{-->--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>

