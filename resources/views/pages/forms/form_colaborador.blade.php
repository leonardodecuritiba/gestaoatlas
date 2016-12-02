<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome <span class="required">*</span></label>
        <div class="col-md-5 col-sm-5 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->nome:old('nome')}}"
                   type="text" class="form-control" name="nome" placeholder="Nome" required>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Nascimento <span class="required">*</span></label>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->data_nascimento:old('data_nascimento')}}"
                   type="text" class="form-control data-to-now" name="data_nascimento" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Email <span class="required">*</span></label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->user->email:old('email')}}"
                   type="text" class="form-control" name="email" placeholder="Email" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">RG <span class="required">*</span></label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->rg:old('rg')}}"
                   type="text" class="form-control show-rg" name="rg" placeholder="RG" required>
        </div>
        <label class="control-label col-md-2 col-sm-2 col-xs-12">CPF <span class="required">*</span></label>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->cpf:old('cpf')}}"
                   type="text" class="form-control show-cpf" name="cpf" placeholder="CPF" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">CNH <span class="required">*</span></label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->cnh:old('cnh')}}"
                   type="file" class="form-control" name="cnh" placeholder="CNH" @if(!$existe_entidade) required @endif>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12">Cart. Trabalho <span class="required">*</span></label>
        <div class="col-md-10 col-sm-10 col-xs-12">
            <input value="{{($existe_entidade)?$Entidade->carteira_trabalho:old('carteira_trabalho')}}"
                   type="file" class="form-control" name="carteira_trabalho" placeholder="Carteira de Trabalho"
                   @if(!$existe_entidade) required @endif>
        </div>
    </div>
</div>

