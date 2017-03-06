<div class="profile_details">
    <div class="well">
        <div class="perfil">
            <h4>Cliente:
                <a target="_blank"
                   href="{{route('clientes.show', $OrdemServico->idcliente)}}"><i>{{$OrdemServico->cliente->getType()->nome_principal}}</i></a>
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
                    <li><i class="fa fa-calendar-o"></i> Data de Fechamento: <b>{{$OrdemServico->fechamento}}</b></li>
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
            <?php $valores = json_decode($OrdemServico->getValores());?>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Total em Serviços: <b class="pull-right"
                                                                      id="valor_total_servicos">{{$valores->valor_total_servicos}}</b>
                </li>
                <li><i class="fa fa-money"></i> Total em Peças/Produtos: <b class="pull-right"
                                                                            id="valor_total_pecas">{{$valores->valor_total_pecas}}</b>
                </li>
                <li><i class="fa fa-money"></i> Total em Kits: <b class="pull-right"
                                                                  id="valor_total_kits">{{$valores->valor_total_kits}}</b>
                </li>
                @if(isset($valores->valor_desconto))
                    <li class="red"><i class="fa fa-money"></i> Descontos: <b class="pull-right"
                                                                              id="valor_total_kits">{{$valores->valor_desconto}}</b>
                    </li>
                @endif
                @if(isset($valores->valor_acrescimo))
                    <li class="blue"><i class="fa fa-money"></i> Acréscimos: <b class="pull-right"
                                                                                id="valor_total_kits">{{$valores->valor_acrescimo}}</b>
                    </li>
                @endif
                <li>
                    <div class="ln_solid"></div>
                </li>
                <li><i class="fa fa-money"></i> Valor Total: <b class="pull-right"
                                                                id="valor_total">{{$valores->valor_total}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Deslocamentos: <b class="pull-right"
                                                                  id="valor_deslocamento">{{$valores->valor_deslocamento}}</b>
                </li>
                <li><i class="fa fa-money"></i> Pedágios: <b class="pull-right"
                                                             id="pedagios">{{$valores->pedagios}}</b>
                </li>
                <li><i class="fa fa-money"></i> Outros Custos: <b class="pull-right"
                                                                  id="outros_custos">{{$valores->outros_custos}}</b>
                </li>
            </ul>
            <ul class="list-unstyled product_price">
                <li><i class="fa fa-money"></i> Valor Final: <b class="pull-right green"
                                                                id="valor_final">{{$valores->valor_final}}</b>
                </li>
            </ul>
        </div>
    </div>
</div>