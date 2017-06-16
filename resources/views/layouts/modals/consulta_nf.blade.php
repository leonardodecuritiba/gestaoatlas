<div class="modal fade" id="consultaNF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loading"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Consultar <b></b> - <i></i></h4>
            </div>
            <div class="modal-body">
                <div class="profile_details">
                    <div class="well profile_view" style="width: 100%">
                        <div class="col-xs-12">
                            <div class="perfil">
                                <ul class="list-unstyled listas_nf">
                                    <li><i class="fa fa-info"></i> Ref.: <b id="ref"></b></li>
                                    <li><i class="fa fa-info"></i> Status: <b id="status"></b></li>
                                    <li><i class="fa fa-info"></i> Status SEFAZ: <b id="status_sefaz"></b></li>
                                    <li><i class="fa fa-info"></i> Mensagem SEFAZ: <b id="mensagem_sefaz"></b></li>
                                    <span id="nfe" class="esconda">
                                        <li><i class="fa fa-info"></i> Chave: <b id="chave_nfe"></b></li>
                                        <li><i class="fa fa-info"></i> Número/Série: <b id="numero_serie"></b></li>
                                        <li>
                                            <a target="_blank" id="url_pdf" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-file"></i> Abrir PDF</a>
                                        </li>
                                        <li>
                                            <a target="_blank" id="url_xml" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-file-text"></i> Abrir XML</a>
                                        </li>
                                    </span>
                                    <span id="nfse" class="esconda">
                                        <li><i class="fa fa-info"></i> Código Verificação: <b
                                                    id="codigo_verificacao"></b></li>
                                        <li><i class="fa fa-info"></i> Data Emissão: <b id="data_emissao_"></b></li>
                                        <li><i class="fa fa-info"></i> Número: <b id="numero"></b></li>
                                        <li>
                                            <a target="_blank" id="url_pdf" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-file"></i> Abrir PDF</a>
                                        </li>
                                        <li>
                                            <a target="_blank" id="url_xml" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-file-text"></i> Abrir XML</a>
                                        </li>
                                    </span>
                                    <span id="email" class="esconda">
                                        <li>
                                            {!! Form::open(array('route'=>['notas_fiscais.enviar', 'XXX'] ))!!}
                                            <a class="btn btn-success btn-xs btn-enviar-nota-cliente"><i
                                                        class="fa fa-envelope"></i> Enviar Para Cliente</a>
                                            {!!Form::close()!!}

                                        </li>
                                    </span>
                                </ul>
                                <ul class="list-unstyled erros_nf esconda">
                                    <li><i class="fa fa-info"></i> Ref.: <b id="ref"></b></li>
                                    <li><i class="fa fa-info"></i> Código: <b id="codigo"></b></li>
                                    <li><i class="fa fa-info"></i> Mensagem: <b id="mensagem"></b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                    <a id="btn-refresh" class="btn btn-success pull-left esconda"><i class="fa fa-refresh"></i> Reenviar</a>
                    <a id="btn-cancel" class="btn btn-warning pull-left esconda"><i class="fa fa-times fa-2"></i>
                        Cancelar</a>
                    <button class="btn btn-danger pull-right" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>