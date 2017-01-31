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
</div>

