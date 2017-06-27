<div class="modal fade" id="modalPopupAparelho" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            {!! Form::open(['route' => ['ordem_servicos.instrumentos.adiciona','_IDOS_','_ID_'],
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <div class="modal-header">
                <h2></h2>
            </div>
            <div class="modal-body">
                <div class="profile_details">
                    <div class="well profile_view">
                        <div class="col-xs-12">
                            <h4 class="brief"><i></i></h4>
                            <div class="right col-xs-6 text-center">
                                <img src="" class="img-circle img-responsive">
                            </div>
                            <div class="perfil left col-xs-6">
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-info"></i> Marca: <b id="marca"></b></li>
                                    <li><i class="fa fa-info"></i> Modelo: <b id="modelo"></b></li>
                                    <li><i class="fa fa-info"></i> Número de Série: <b id="numero_serie"></b></li>
                                </ul>
                                <ul class="list-unstyled instrumento">
                                    <li><i class="fa fa-info"></i> Inventário: <b id="inventario"></b></li>
                                    <li><i class="fa fa-info"></i> Patrimônio: <b id="patrimonio"></b></li>
                                    <li><i class="fa fa-info"></i> Ano: <b id="ano"></b></li>
                                    <li><i class="fa fa-info"></i> Portaria: <b id="portaria"></b></li>
                                    <li><i class="fa fa-info"></i> Divisão: <b id="divisao"></b></li>
                                    <li><i class="fa fa-info"></i> Capacidade: <b id="capacidade"></b></li>
                                    <li><i class="fa fa-info"></i> IP: <b id="ip"></b></li>
                                    <li><i class="fa fa-info"></i> Endereço: <b id="endereco"></b></li>
                                    <li><i class="fa fa-info"></i> Setor: <b id="setor"></b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="numero_chamado" class="esconda">
                    <div class="form-group">
                        <div class="alert alert-danger fade in" role="alert">
                            <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong>
                            Caso o nº do chamado lançado esteja incorreto
                            ou seja nº fictício, o usuário arcará com custos aplicados à esse instrumento/equipamento.
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nº Chamado: <span
                                    class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input name="numero_chamado" type="text" maxlength="50" class="form-control">
                        </div>
                    </div>
                </div>

                <div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar
                </button>
                <button class="btn btn-success btn-ok pull-right"><i class="fa fa-check"></i> Selecionar</button>
            </div>
            {{Form::close()}}
        </div>
    </div>
</div>