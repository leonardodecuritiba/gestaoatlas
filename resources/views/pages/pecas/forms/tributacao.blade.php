<div class="x_title">
    <h2>Tributação</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">CFOP: </label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select name="idcfop" class="select2_single form-control" tabindex="-1" required>
                    <option value="">Escolha a Categoria</option>
                    @foreach($Page->extras['cfop'] as $sel)
                        <option value="{{$sel->id}}"
                                @if(isset($Peca->peca_tributacao->idcfop) && $Peca->peca_tributacao->idcfop == $sel->id) selected @endif
                        >{{$sel->numeracao}}</option>
                    @endforeach
                </select>
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">CST: </label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select name="idcst" class="select2_single form-control" tabindex="-1" required>
                    <option value="">Escolha a Categoria</option>
                    @foreach($Page->extras['cst'] as $sel)
                        <option value="{{$sel->id}}"
                                @if(isset($Peca->peca_tributacao->idcst) && $Peca->peca_tributacao->idcst == $sel->id) selected @endif
                        >{{$sel->numeracao}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Natureza de Operação: </label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select name="idnatureza_operacao" class="select2_single form-control" tabindex="-1" required>
                    <option value="">Escolha a Natureza de Operação</option>
                    @foreach($Page->extras['natureza_operacao'] as $sel)
                        <option value="{{$sel->id}}"
                                @if(isset($Peca->peca_tributacao->idnatureza_operacao) && $Peca->peca_tributacao->idnatureza_operacao == $sel->id) selected @endif
                        >({{$sel->numero}}) {{$sel->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">NCM: </label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select name="idncm" class="select2_single form-control" tabindex="-1" required>
                    <option value="1">1</option>
                    {{--@foreach($Page->extras['ncm'] as $sel)--}}
                    {{--<option value="{{$sel->idncm}}"--}}
                    {{--@if(isset($Peca->peca_tributacao->idncm) && $Peca->peca_tributacao->idncm == $sel->idncm) selected @endif--}}
                    {{-->{{$sel->codigo}}</option>--}}
                    {{--@endforeach--}}
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS BC.</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_base_calculo" type="text" class="form-control show-porcento"
                       placeholder="icms_base_calculo ICMS (%)"
                       value="{{(isset($Peca->peca_tributacao->icms_base_calculo))?$Peca->peca_tributacao->icms_base_calculo:old('icms_base_calculo')}}"
                >
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Valor total</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_valor_total" type="text" class="form-control show-porcento"
                       placeholder="icms_valor_total (%)"
                       value="{{(isset($Peca->tributacao->icms_valor_total))?$Peca->tributacao->icms_valor_total:old('icms_valor_total')}}"
                >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS BC. ST</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_base_calculo_st" type="text" class="form-control show-porcento"
                       placeholder="icms_base_calculo_st (%)"
                       value="{{(isset($Peca->peca_tributacao->icms_base_calculo_st))?$Peca->peca_tributacao->icms_base_calculo_st:old('icms_base_calculo_st')}}"
                >
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Valor total st</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_valor_total_st" type="text" class="form-control show-porcento"
                       placeholder="icms_valor_total_st (%)"
                       value="{{(isset($Peca->tributacao->icms_valor_total_st))?$Peca->tributacao->icms_valor_total_st:old('icms_valor_total_st')}}"
                >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Valor IPI</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="valor_ipi" type="text" class="form-control show-porcento" placeholder="valor_ipi (%)"
                       value="{{(isset($Peca->peca_tributacao->valor_ipi))?$Peca->peca_tributacao->valor_ipi:old('valor_ipi')}}"
                >
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Valor Uni. Tributável</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="valor_unitario_tributavel" type="text" class="form-control show-porcento"
                       placeholder="valor_unitario_tributavel (%)"
                       value="{{(isset($Peca->tributacao->valor_unitario_tributavel))?$Peca->tributacao->valor_unitario_tributavel:old('valor_unitario_tributavel')}}"
                >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Sit. Tributária</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_situacao_tributaria" type="text" class="form-control show-porcento"
                       placeholder="icms_situacao_tributaria (%)"
                       value="{{(isset($Peca->peca_tributacao->icms_situacao_tributaria))?$Peca->peca_tributacao->icms_situacao_tributaria:old('icms_situacao_tributaria')}}"
                >
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Origem</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_origem" type="text" class="form-control show-porcento" placeholder="icms_origem (%)"
                       value="{{(isset($Peca->tributacao->icms_origem))?$Peca->tributacao->icms_origem:old('icms_origem')}}"
                >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">PIS Sit. Tributária</label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <input name="pis_situacao_tributaria" type="text" class="form-control show-porcento"
                       placeholder="pis_situacao_tributaria (%)"
                       value="{{(isset($Peca->peca_tributacao->pis_situacao_tributaria))?$Peca->peca_tributacao->pis_situacao_tributaria:old('pis_situacao_tributaria')}}"
                >
            </div>
        </div>

        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">NCM: </label>--}}

        {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
        {{--<select name="idncm" class="select2_single-ajax form-control" tabindex="-1" required>--}}
        {{--<option value="">Código NCM</option>--}}
        {{--@foreach($Page->extras['ncm'] as $sel)--}}
        {{--<option--}}
        {{--data-aliquota_nacional="{{$sel->aliquota_nacional}}"--}}
        {{--data-aliquota_importacao="{{$sel->aliquota_importacao}}"--}}
        {{--value="{{$sel->idncm}}"--}}
        {{--@if(isset($Peca->tributacao->idncm) && $Peca->tributacao->idncm == $sel->idncm) selected @endif--}}
        {{-->{{$sel->codigo}} @if(isset($sel->descricao)){{' - '.$sel->descricao}}@endif</option>--}}
        {{--@endforeach--}}
        {{--</select>--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. Nac. (%): <span class="required">*</span></label>--}}
        {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
        {{--<input name="aliquota_nacional" type="text" maxlength="50" class="form-control show-porcento" required--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_nacional))?$Peca->tributacao->aliquota_nacional:old('aliquota_nacional')}}">--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. Import. (%): <span class="required">*</span></label>--}}
        {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
        {{--<input name="aliquota_importacao" type="text" maxlength="50" class="form-control show-porcento" required--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_importacao))?$Peca->tributacao->aliquota_importacao:old('aliquota_importacao')}}">--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Peso Líquido </label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="peso_liquido" type="text" class="form-control show-peso" placeholder="Peso Líquido"--}}
        {{--value="{{(isset($Peca->tributacao->peso_liquido))?$Peca->tributacao->peso_liquido:old('peso_liquido')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Peso Bruto </label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="peso_bruto" type="text" class="form-control show-peso" placeholder="Peso Bruto"--}}
        {{--value="{{(isset($Peca->tributacao->peso_bruto))?$Peca->tributacao->peso_bruto:old('peso_bruto')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
        {{--<div class="col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2 col-xs-12">--}}
        {{--<div class="checkbox">--}}
        {{--<label>--}}
        {{--<input name="isencao_icms" type="checkbox" class="flat"--}}
        {{--@if(isset($Peca->tributacao->isencao_icms) && ($Peca->tributacao->isencao_icms == 1)) checked="checked" @endif--}}
        {{--> Isento ICMS--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-3 col-sm-3 col-xs-12">--}}
        {{--<div class="checkbox">--}}
        {{--<label>--}}
        {{--<input name="ipi_venda" type="checkbox" class="flat"--}}
        {{--@if(isset($Peca->tributacao->ipi_venda) && ($Peca->tributacao->ipi_venda == 1)) checked="checked" @endif--}}
        {{--> IPI na Venda?--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-3 col-sm-3 col-xs-12">--}}
        {{--<div class="checkbox">--}}
        {{--<label>--}}
        {{--<input name="reducao_icms" type="checkbox" class="flat"--}}
        {{--@if(isset($Peca->tributacao->reducao_icms) && ($Peca->tributacao->reducao_icms == 1)) checked="checked" @endif--}}
        {{--> Redução ICMS--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="ln_solid"></div>--}}
        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS </label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="icms" type="text" class="form-control show-valor" placeholder="ICMS"--}}
        {{--value="{{(isset($Peca->tributacao->icms))?$Peca->tributacao->icms:old('icms')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Red. de BC do ICMS </label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="reducao_bc_icms" type="text" class="form-control show-porcento" placeholder="Redução de BC do ICMS (%)"--}}
        {{--value="{{(isset($Peca->tributacao->reducao_bc_icms))?$Peca->tributacao->reducao_bc_icms:old('reducao_bc_icms')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Red. de BC do ICMS ST</label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="reducao_bc_icms_st" type="text" class="form-control show-porcento" placeholder="Redução de BC do ICMS (%)"--}}
        {{--value="{{(isset($Peca->tributacao->reducao_bc_icms_st))?$Peca->tributacao->reducao_bc_icms_st:old('reducao_bc_icms_st')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. ICMS Cupom Fiscal</label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="aliquota_icms" type="text" class="form-control show-porcento" placeholder="Alíquota ICMS Cupom Fiscal (%)"--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_icms))?$Peca->tributacao->aliquota_icms:old('aliquota_icms')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="ln_solid"></div>--}}

        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. II </label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="aliquota_ii" type="text" class="form-control show-porcento" placeholder="Alíquota II (%)"--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_ii))?$Peca->tributacao->aliquota_ii:old('aliquota_ii')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Import.</label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="icms_importacao" type="text" class="form-control show-valor" placeholder="ICMS Importação (%)"--}}
        {{--value="{{(isset($Peca->tributacao->icms_importacao))?$Peca->tributacao->icms_importacao:old('icms_importacao')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. COFINS Import.</label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="aliquota_cofins_importacao" type="text" class="form-control show-porcento" placeholder="Alíquota COFINS Importação (%)"--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_cofins_importacao))?$Peca->tributacao->aliquota_cofins_importacao:old('aliquota_cofins_importacao')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. PIS Import.</label>--}}
        {{--<div class="col-md-4 col-sm-4 col-xs-12">--}}
        {{--<input name="aliquota_pis_importacao" type="text" class="form-control show-porcento" placeholder="Alíquota PIS Importação (%)"--}}
        {{--value="{{(isset($Peca->tributacao->aliquota_pis_importacao))?$Peca->tributacao->aliquota_pis_importacao:old('aliquota_pis_importacao')}}"--}}
        {{-->--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>
</div>