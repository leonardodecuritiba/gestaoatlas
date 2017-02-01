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
                                @if((isset($Peca->peca_tributacao->idcfop) && $Peca->peca_tributacao->idcfop == $sel->id) || (old('idcfop') == $sel->id)) selected @endif
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
                                @if((isset($Peca->peca_tributacao->idcst) && $Peca->peca_tributacao->idcst == $sel->id) || (old('idcst') == $sel->id)) selected @endif
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
                                @if((isset($Peca->peca_tributacao->idnatureza_operacao) && $Peca->peca_tributacao->idnatureza_operacao == $sel->id) || (old('idnatureza_operacao') == $sel->id)) selected @endif
                        >({{$sel->numero}}) {{$sel->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <label class="control-label col-md-2 col-sm-2 col-xs-12">NCM: </label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select name="idncm" class="select2_single-ajax form-control" tabindex="-1" required>
                    <option value="">Escolha o NCM</option>
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
                       value="{{(isset($Peca->peca_tributacao->icms_valor_total))?$Peca->peca_tributacao->icms_valor_total:old('icms_valor_total')}}"
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
            <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Valor Total ST</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="icms_valor_total_st" type="text" class="form-control show-porcento"
                       placeholder="icms_valor_total_st (%)"
                       value="{{(isset($Peca->peca_tributacao->icms_valor_total_st))?$Peca->peca_tributacao->icms_valor_total_st:old('icms_valor_total_st')}}"
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
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Valor Un. Tributável</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input name="valor_unitario_tributavel" type="text" class="form-control show-porcento"
                       placeholder="valor_unitario_tributavel (%)"
                       value="{{(isset($Peca->peca_tributacao->valor_unitario_tributavel))?$Peca->peca_tributacao->valor_unitario_tributavel:old('valor_unitario_tributavel')}}"
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
                       value="{{(isset($Peca->peca_tributacao->icms_origem))?$Peca->peca_tributacao->icms_origem:old('icms_origem')}}"
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
    </div>
</div>