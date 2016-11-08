<div class="x_title">
    <h2>Dados de Pessoa Física</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">CPF <span class="required">*</span></label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->pessoa_fisica->cpf:old('cpf')}}" type="text" class="form-control show-cpf" name="cpf" placeholder="CPF" required>
            </div>
        </div>
    </div>
</div>