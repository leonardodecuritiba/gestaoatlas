<div class="profile_details">
    <div class="well">
        <div class="perfil">
            <h4>Cliente:
                <a target="_blank"
                   href="{{route('clientes.show', $Data->client_id)}}"><i>{{$Data->getClientName()}}</i></a>
                @if($Data->isClosed())
                    @role('admin')
                    <a class="btn btn-danger pull-right"
                       href="{{route('budgets.reopen',$Data->id)}}">
                        <i class="fa fa-refresh fa-2"></i> Reabrir</a>
                    @endrole
                @endif
            </h4>

            <ul class="list-unstyled">
                <li><i class="fa fa-info"></i> Nº do Orçamento: <b>{{$Data->id}}</b>
                </li>
                <li><i class="fa fa-calendar"></i> Data de Abertura:
                    <b>{{$Data->getCreatedAtFormatted()}}</b></li>
                <li><i class="fa fa-warning"></i> Situação:
                    <b>{{$Data->getSituationText()}}</b></li>
                <li><i class="fa fa-user"></i> Colaborador: <b>{{$Data->getCollaboratorName()}}</b>
                </li>
                @if($Data->isClosed())
                    <li><i class="fa fa-calendar-o"></i> Data de Finalização:
                        <b>{{$Data->getClosedAtFormatted()}}</b>
                    </li>
                    <li><i class="fa fa-user"></i> Responsável: <b>{{$Data->responsible}}</b></li>
                    <li><i class="fa fa-info"></i> CPF: <b>{{$Data->getResponsibleCpf()}}</b></li>
                    <li><i class="fa fa-info"></i> Cargo: <b>{{$Data->responsible_office}}</b></li>
                @endif
                @if($Data->cost_exemption)
                    <li class="red"><i class="fa fa-exclamation-triangle"></i> <b>Isento de Custos com Deslocamentos</b>
                    </li>
                @endif
            </ul>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <ul class="list-unstyled">
                    <li><h4>Dados Financeiros (Técnica)</h4></li>
                    <li><i class="fa fa-money"></i> Emissão: <b>{{$Data->client->getTipoEmissao('tecnica')}}</b></li>
                    <li><i class="fa fa-money"></i> Forma: <b>{{$Data->client->getFormaPagamento('tecnica')}}</b></li>
                    <li><i class="fa fa-money"></i> Prazo: <b>{{$Data->client->getPrazoPagamentoText('tecnica')}}</b>
                    </li>
                </ul>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <ul class="list-unstyled">
                    <li><h4>Dados Financeiros (Comercial)</h4></li>
                    <li><i class="fa fa-money"></i> Emissão: <b>{{$Data->client->getTipoEmissao('comercial')}}</b></li>
                    <li><i class="fa fa-money"></i> Forma: <b>{{$Data->client->getFormaPagamento('comercial')}}</b></li>
                    <li><i class="fa fa-money"></i> Prazo: <b>{{$Data->client->getPrazoPagamentoText('comercial')}}</b>
                    </li>
                </ul>
            </div>


            <div class="clearfix"></div>

            <ul class="list-unstyled product_price">
                <li>
                    <i class="fa fa-money"></i> Total em Peças/Produtos: <b class="pull-right" id="valor_total_pecas">{{$Data->getValueTotalFormatted()}}</b>
                </li>
                <li class="red">
                    <i class="fa fa-money"></i> Descontos: <b class="pull-right" id="valor_descontos">{{$Data->getDiscountTechnicianFormatted()}}</b>
                </li>
                <li class="blue">
                    <i class="fa fa-money"></i> Acréscimos: <b class="pull-right" id="valor_acrescimos">{{$Data->getIncreaseTechnicianFormatted()}}</b>
                </li>
                <li>
                    <div class="ln_solid"></div>
                </li>
                <li>
                    <i class="fa fa-money"></i> Valor Total: <b class="pull-right" id="valor_total">{{$Data->getValueTotalFormatted()}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Deslocamentos: <b class="pull-right"
                                                                  id="valor_deslocamento">{{$Data->getCostDisplacementFormatted()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Pedágios: <b class="pull-right"
                                                             id="pedagios">{{$Data->getCostTollFormatted()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Outros Custos: <b class="pull-right"
                                                                  id="outros_custos">{{$Data->getCostOtherFormatted()}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Valor Final: <b class="pull-right green"
                                                                id="valor_final">{{$Data->getValueEndFormatted()}}</b>
                </li>
            </ul>
        </div>
    </div>
</div>