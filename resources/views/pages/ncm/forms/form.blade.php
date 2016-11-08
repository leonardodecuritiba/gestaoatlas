<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Código: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="codigo" type="text" maxlength="50" class="form-control" required
               value="{{(isset($Ncm->codigo))?$Ncm->codigo:old('codigo')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="50" class="form-control" required
               value="{{(isset($Ncm->descricao))?$Ncm->descricao:old('descricao')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. IPI (%):</label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input name="aliquota_ipi" type="text" maxlength="50" class="form-control show-porcento" 
               value="{{(isset($Ncm->aliquota_ipi))?$Ncm->aliquota_ipi:old('aliquota_ipi')}}">
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. PIS (%):</label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input name="aliquota_pis" type="text" maxlength="50" class="form-control show-porcento"
               value="{{(isset($Ncm->aliquota_pis))?$Ncm->aliquota_pis:old('aliquota_pis')}}">
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. COFINS (%):</label>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <input name="aliquota_cofins" type="text" maxlength="50" class="form-control show-porcento"
               value="{{(isset($Ncm->aliquota_cofins))?$Ncm->aliquota_cofins:old('aliquota_cofins')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. Nacional (%): <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <input name="aliquota_nacional" type="text" maxlength="50" class="form-control show-porcento" required
               value="{{(isset($Ncm->aliquota_nacional))?$Ncm->aliquota_nacional:old('aliquota_nacional')}}">
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. Importação (%): <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <input name="aliquota_importacao" type="text" maxlength="50" class="form-control show-porcento" required
               value="{{(isset($Ncm->aliquota_importacao))?$Ncm->aliquota_importacao:old('aliquota_importacao')}}">
    </div>
</div>






