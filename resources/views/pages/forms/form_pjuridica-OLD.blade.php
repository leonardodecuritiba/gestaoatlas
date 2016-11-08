    <div class="x_title">
        <h2>Dados de Pessoa Jurídica</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">CNPJ <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->numero_inscricao:old('numero_inscricao')}}"
                           type="text" class="form-control show-cnpj" name="numero_inscricao" placeholder="Número de inscrição" value="10.555.180/0001-21" required>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#captchaModal" type="button">Consultar CNPJ</button>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Fantasia <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->nome_fantasia:old('nome_fantasia')}}"
                           type="text" class="form-control" name="nome_fantasia" placeholder="Nome Fantasia" required>
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Data de abertura <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_abertura:old('data_abertura')}}"
                           type="text" class="form-control" name="data_abertura" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Empresarial <span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->nome_empresarial:old('nome_empresarial')}}"
                           type="text" class="form-control" name="nome_empresarial" placeholder="Nome Empresarial" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Atividade Principal <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->cod_atividade_principal:old('cod_atividade_principal')}}"
                           type="text" class="form-control" name="cod_atividade_principal" placeholder="Código" required>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->desc_atividade_principal:old('desc_atividade_principal')}}"
                           type="text" class="form-control" name="desc_atividade_principal" placeholder="Descriçao" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Atividade Secundária </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->cod_atividade_secundaria:old('cod_atividade_secundaria')}}"
                           type="text" class="form-control" name="cod_atividade_secundaria" placeholder="Código">
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->desc_atividade_secundaria:old('desc_atividade_secundaria')}}"
                           type="text" class="form-control" name="desc_atividade_secundaria" placeholder="Descriçao">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Natureza Jurídica <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->cod_natureza_juridica:old('cod_natureza_juridica')}}"
                           type="text" class="form-control" name="cod_natureza_juridica" placeholder="Código" required>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->desc_natureza_juridica:old('desc_natureza_juridica')}}"
                           type="text" class="form-control" name="desc_natureza_juridica" placeholder="Descriçao" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Ent. Fed. Responsável <span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->ent_fed_responsavel:old('ent_fed_responsavel')}}"
                           type="text" class="form-control" name="ent_fed_responsavel" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Situação Cadastral <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->situacao_cadastral:old('situacao_cadastral')}}"
                           type="text" class="form-control" name="situacao_cadastral" required>
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Data da Situação Cadastral <span class="required">*</span></label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_situacao_cadastral:old('data_situacao_cadastral')}}"
                           type="text" class="form-control" name="data_situacao_cadastral" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Motivo da Situação Cadastral <span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->mot_situacao_cadastral:old('mot_situacao_cadastral')}}"
                           type="text" class="form-control" name="mot_situacao_cadastral" placeholder="Motivo Situação Cadastral">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Situação Especial </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->situacao_especial:old('situacao_especial')}}"
                           type="text" class="form-control" name="situacao_especial">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Data da Situação Especial </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_situacao_especial:old('data_situacao_especial')}}"
                           type="text" class="form-control" name="data_situacao_especial">
                </div>
            </div>
        </div>
    </div>
