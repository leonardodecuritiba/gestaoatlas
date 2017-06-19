<div class="profile_details">
    <div class="well">
        <div class="perfil">
            <h4>Cliente:
                <a target="_blank"
                   href="{{route('clientes.show', $OrdemServico->idcliente)}}"><i>{{$OrdemServico->cliente->getType()->nome_principal}}</i></a>
                @if($OrdemServico->status() && $OrdemServico->idfaturamento == NULL)
                    @role('admin')
                    <a class="btn btn-danger pull-right"
                       href="{{route('ordem_servicos.reabrir',$OrdemServico->idordem_servico)}}">
                        <i class="fa fa-trash fa-2"></i> Reabrir O.S.</a>
                    @endrole
                @endif
            </h4>
            <ul class="list-unstyled">
                <li><i class="fa fa-info"></i> Nº da O.S.: <b>{{$OrdemServico->idordem_servico}}</b>
                </li>
                <li><i class="fa fa-calendar"></i> Data de Abertura:
                    <b>{{$OrdemServico->created_at}}</b></li>
                <li><i class="fa fa-warning"></i> Situação:
                    <b>{{$OrdemServico->situacao->descricao}}</b></li>
                <li><i class="fa fa-user"></i> Colaborador: <b>{{$OrdemServico->colaborador->nome}}</b>
                </li>
                @if($OrdemServico->status())
                    <li><i class="fa fa-calendar-o"></i> Data de Finalização: <b>{{$OrdemServico->data_finalização}}</b>
                    </li>
                    <li><i class="fa fa-info"></i> Nº do chamado: <b>{{$OrdemServico->numero_chamado}}</b></li>
                    <li><i class="fa fa-user"></i> Responsável: <b>{{$OrdemServico->responsavel}}</b></li>
                    <li><i class="fa fa-info"></i> CPF: <b>{{$OrdemServico->responsavel_cpf}}</b></li>
                    <li><i class="fa fa-info"></i> Cargo: <b>{{$OrdemServico->responsavel_cargo}}</b></li>
                @endif
                @if($OrdemServico->custos_isento)
                    <li class="red"><i class="fa fa-exclamation-triangle"></i> <b>Isento de Custos com Deslocamentos</b>
                    </li>
                @endif
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Total em Serviços: <b class="pull-right"
                                                                      id="valor_total_servicos">{{$OrdemServico->fechamentoServicosTotalReal()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Total em Peças/Produtos: <b class="pull-right"
                                                                            id="valor_total_pecas">{{$OrdemServico->fechamentoPecasTotalReal()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Total em Kits: <b class="pull-right"
                                                                  id="valor_total_kits">{{$OrdemServico->fechamentoKitsTotalReal()}}</b>
                </li>
                <li class="red"><i class="fa fa-money"></i> Descontos: <b class="pull-right"
                                                                          id="valor_descontos">{{$OrdemServico->getDescontoTecnicoReal()}}</b>
                </li>
                <li class="blue"><i class="fa fa-money"></i> Acréscimos: <b class="pull-right"
                                                                            id="valor_acrescimos">{{$OrdemServico->getAcrescimoTecnicoReal()}}</b>
                </li>
                <li>
                    <div class="ln_solid"></div>
                </li>
                <li><i class="fa fa-money"></i> Valor Total: <b class="pull-right"
                                                                id="valor_total">{{$OrdemServico->fechamentoValorTotalReal()}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Deslocamentos: <b class="pull-right"
                                                                  id="valor_deslocamento">{{$OrdemServico->getCustosDeslocamentoReal()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Pedágios: <b class="pull-right"
                                                             id="pedagios">{{$OrdemServico->getPedagiosReal()}}</b>
                </li>
                <li><i class="fa fa-money"></i> Outros Custos: <b class="pull-right"
                                                                  id="outros_custos">{{$OrdemServico->getOutrosCustosReal()}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Valor Final: <b class="pull-right green"
                                                                id="valor_final">{{$OrdemServico->getValorFinalReal()}}</b>
                </li>
            </ul>
        </div>
    </div>
</div>