    <div class="x_title">
        <h2>Dados de Pessoa Jurídica</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">CNPJ <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->cnpj:old('cnpj')}}"
                           type="text" class="form-control show-cnpj" name="cnpj" placeholder="CNPJ" value="10555180000121" required>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#captchaModal" type="button">Consultar pelo CNPJ</button>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Inscrição Estadual <span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->ie:old('ie')}}"
                           type="text" class="form-control show-ie" name="ie" placeholder="Inscrição Estadual"
                           @if(($existe_entidade) && ($Entidade->pessoa_juridica->isencao_ie == 1)) disabled @else required @endif >
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input name="isencao_ie" type="checkbox" class="flat"
                                   @if(($existe_entidade) && ($Entidade->pessoa_juridica->isencao_ie == 1)) checked="checked" @endif
                            > Isenção IE
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Razão Social <span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->razao_social:old('razao_social')}}"
                           type="text" class="form-control" name="razao_social" placeholder="Razão Social" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Fantasia <span class="required">*</span></label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input value="{{($existe_entidade)?$Entidade->pessoa_juridica->nome_fantasia:old('nome_fantasia')}}"
                           type="text" class="form-control" name="nome_fantasia" placeholder="Nome Fantasia" required>
                </div>
            </div>
            {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Atividade Econômica </label>--}}
            {{--<div class="col-md-10 col-sm-10 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->ativ_economica:old('ativ_economica')}}"--}}
            {{--type="text" class="form-control" name="ativ_economica" placeholder="Atividade Econômica">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Situação Cadastral Vigente </label>--}}
            {{--<div class="col-md-6 col-sm-6 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->sit_cad_vigente:old('sit_cad_vigente')}}"--}}
            {{--type="text" class="form-control" name="sit_cad_vigente"--}}
            {{--placeholder="Situação Cadastral Vigente">--}}
            {{--</div>--}}
            {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->sit_cad_status:old('sit_cad_status')}}"--}}
            {{--type="text" class="form-control" name="sit_cad_status" placeholder="Situação">--}}
            {{--</div>--}}
            {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_sit_cad:old('data_sit_cad')}}"--}}
            {{--type="text" class="form-control data-to-now" name="data_sit_cad"--}}
            {{--placeholder="Data Sit. Cadastral">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Regime de Apuração </label>--}}
            {{--<div class="col-md-5 col-sm-5 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->reg_apuracao:old('reg_apuracao')}}"--}}
            {{--type="text" class="form-control" name="reg_apuracao" placeholder="Regime de Apuração">--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12">Data de Credenciamento como emissor de--}}
            {{--NF-e </label>--}}
            {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_credenciamento:old('data_credenciamento')}}"--}}
            {{--type="text" class="form-control data-to-now" name="data_credenciamento"--}}
            {{--placeholder="Data Cred.">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
            {{--<label class="control-label col-md-2 col-sm-2 col-xs-12">Indicador de Obrigatoriedade de NF-e </label>--}}
            {{--<div class="col-md-5 col-sm-5 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->ind_obrigatoriedade:old('ind_obrigatoriedade')}}"--}}
            {{--type="text" class="form-control" name="ind_obrigatoriedade"--}}
            {{--placeholder="Indicador de Obrigatoriedade de NF-e">--}}
            {{--</div>--}}
            {{--<label class="control-label col-md-3 col-sm-3 col-xs-12">Data de Início da Obrigatoriedade de--}}
            {{--NF-e </label>--}}
            {{--<div class="col-md-2 col-sm-2 col-xs-12">--}}
            {{--<input value="{{($existe_entidade)?$Entidade->pessoa_juridica->data_ini_obrigatoriedade:old('data_ini_obrigatoriedade')}}"--}}
            {{--type="text" class="form-control data-to-now" name="data_ini_obrigatoriedade"--}}
            {{--placeholder="Data Ini.">--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>