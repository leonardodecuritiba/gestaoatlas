<div class="modal fade" id="modalPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                <div class="profile_details">
                    <div class="well">
                        <h2 class="brief"><i>Ordem de Serviço</i></h2>
                        <div class="perfil">
                            <h4>Cliente: <i></i></h4>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-info"></i> Número: <b id="idordem_servico"></b></li>
                                <li><i class="fa fa-calendar"></i> Data de Abertura: <b id="data_abertura"></b></li>
                                <li><i class="fa fa-warning"></i> Situação: <b id="situacao"></b></li>
                                <li><i class="fa fa-user"></i> Colaborador: <b id="colaborador"></b></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success pull-left"
                    href="{{route('ordem_servicos.resumo',$OrdemServico->idordem_servico)}}">
                    <i class="fa fa-check fa-2"></i> Fechar O.S.
                </a>
                <button class="btn btn-default pull-rigth" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>