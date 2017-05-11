@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('page_content')
    @include('layouts.modals.pagar_parcela')
    @include('layouts.modals.consulta_nf')

    @if (session()->has('responseNF'))
        <?php $response = session()->pull('responseNF', ''); ?>
        <section class="row">
            <div class="x_panel">
                <section class="x_title">
                    <h2>Retorno da Nota Fiscal <i>(Código: {{$response['code']}})</i></h2>
                    <div class="clearfix"></div>
                </section>
                <section class="x_content">
                    @if($response['error'])
                        <div class="alert fade in alert-danger" role="alert">
                            <ul>
                                @foreach($response['message'] as $message)
                                    <li><b>{{$message->codigo}}:</b> <i>{{$message->mensagem}}</i></li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert fade in alert-success" role="alert">
                            <?php print_r($response['message']); ?>
                        </div>
                    @endif
                </section>
            </div>
        </section>
    @endif
    <div class="page-title">
        <div class="title_left">
            <h3>{{$Page->titulo_primario.$Page->Target}}</h3>
        </div>
    </div>
    <section class="row">
        <div class="x_panel">
            <div class="x_content">
                <div class="alert fade in alert-{{$Fechamento->getStatusType()}}" role="alert">
                    Situação do Fechamento: <b>{{$Fechamento->getStatusText()}}</b>
                </div>
                <div class="profile_details">
                    <div class="well">
                        <div class="perfil">
                            <h4>{{$Fechamento->getTipoFechamento()}}:
                                <a target="_blank"
                                   href="{{route('clientes.show', $Fechamento->idcliente)}}"><i>{{$Fechamento->cliente->getType()->nome_principal}}</i></a>
                                @role('admin')
                                <a class="btn btn-danger pull-right"
                                   href="{{route('fechamentos.remover',$Fechamento->id)}}">
                                    <i class="fa fa-trash fa-2"></i> Excluir Fechamento</a>
                                @endrole
                            </h4>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-calendar"></i> Data do Fechamento:
                                    <b>{{$Fechamento->created_at}}</b></li>
                                <li><i class="fa fa-credit-card"></i> Tipo de Emissão (Técnica):
                                    <b>{{$Fechamento->cliente->tipo_emissao_tecnica->descricao}}</b></li>
                                <li><i class="fa fa-credit-card"></i> Forma de Pagamento (Técnica):
                                    <b>{{$Fechamento->cliente->forma_pagamento_tecnica->descricao}}</b></li>
                                <li><i class="fa fa-info"></i> Pagamento: <b
                                            class="text-{{$Fechamento->getPagoStatusColor()}}">{{$Fechamento->getPagoText()}}</b>
                                </li>
                                <li><i class="fa fa-money"></i> Total Pendente: <b
                                            class="text-danger">{{$Fechamento->getTotalPendenteReal()}}</b>
                                <li><i class="fa fa-money"></i> Total Pago: <b
                                            class="text-success">{{$Fechamento->getTotalPagoReal()}}</b>
                                </li>
                                @if($Fechamento->getStatusNfeHomologacao())
                                    <li>
                                        <a data-toggle="modal"
                                           data-idfechamento="{{$Fechamento->id}}"
                                           data-type="nfe"
                                           data-target="#consultaNF"
                                           data-debug="1"
                                           class="btn btn-warning btn-xs"><i class="fa fa-search"></i> Consultar NFe
                                            TESTE</a>
                                    </li>
                                @else
                                    <li><a href="{{route('fechamentos.nfe.teste',$Fechamento->id)}}"
                                           class="btn btn-xs btn-primary"><i class="fa fa-info fa-2"></i> Gerar NFe
                                            TESTE</a>
                                    </li>
                                @endif
                                @if($Fechamento->getStatusNfeProducao())
                                    <li>
                                        <a data-toggle="modal"
                                           data-idfechamento="{{$Fechamento->id}}"
                                           data-type="nfe"
                                           data-target="#consultaNF"
                                           data-debug="0"
                                           class="btn btn-warning btn-xs"><i class="fa fa-search"></i> Consultar NFe</a>
                                    </li>
                                @elseif($Fechamento->getStatusNfeHomologacao())
                                    <li>
                                        <a href="{{route('fechamentos.nfe',$Fechamento->id)}}"
                                           class="btn btn-xs btn-primary pull-right"><i class="fa fa-info fa-2"></i>
                                            Gerar
                                            NFe</a>
                                    </li>
                                @endif


                                @if($Fechamento->getStatusNFSeHomologacao())
                                    <li>
                                        <a data-toggle="modal"
                                           data-idfechamento="{{$Fechamento->id}}"
                                           data-type="nfse"
                                           data-target="#consultaNF"
                                           data-debug="1"
                                           class="btn btn-warning btn-xs"><i class="fa fa-search"></i> Consultar NFSe
                                            TESTE</a>
                                    </li>
                                @else
                                    <li><a href="{{route('fechamentos.nfse.teste',$Fechamento->id)}}"
                                           class="btn btn-xs btn-primary"><i class="fa fa-info fa-2"></i> Gerar NFSe
                                            TESTE</a>
                                    </li>
                                @endif
                                @if($Fechamento->getStatusNFSeProducao())
                                    <li>
                                        <a data-toggle="modal"
                                           data-idfechamento="{{$Fechamento->id}}"
                                           data-type="nfse"
                                           data-target="#consultaNF"
                                           data-debug="0"
                                           class="btn btn-warning btn-xs"><i class="fa fa-search"></i> Consultar
                                            NFSe</a>
                                    </li>
                                @elseif($Fechamento->getStatusNFSeHomologacao())
                                    <li>
                                        <a href="{{route('fechamentos.nfse',$Fechamento->id)}}"
                                           class="btn btn-xs btn-primary"><i class="fa fa-info fa-2"></i> Gerar
                                            NFSe</a>
                                    </li>
                                @endif
                            </ul>

                            <?php $valores = $Fechamento->getValores();?>
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
                                @if($valores->valor_desconto > 0)
                                    <li class="red"><i class="fa fa-money"></i> Descontos: <b class="pull-right"
                                                                                              id="valor_total_kits">{{$valores->valor_desconto}}</b>
                                    </li>
                                @endif
                                @if($valores->valor_acrescimo > 0)
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
                                                                             id="pedagios">{{$valores->valor_pedagios}}</b>
                                </li>
                                <li><i class="fa fa-money"></i> Outros Custos: <b class="pull-right"
                                                                                  id="outros_custos">{{$valores->valor_outros_custos}}</b>
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
            </div>
        </div>
    </section>
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Pagamento - Parcelas</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table border="0" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Situação</th>
                                <th>ID</th>
                                <th>Número da Parcala</th>
                                <th>Forma de Pagamento</th>
                                <th>Data de Vencimento</th>
                                <th>Data de Pagamento</th>
                                <th>Data de Baixa</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Fechamento->pagamento->parcelas as $selecao)
                                <tr>
                                    <td>
                                        <button class="btn btn-xs btn-{{$selecao->getStatusColor()}}">
                                            {{$selecao->getStatusText()}}
                                        </button>
                                    </td>
                                    <td>{{$selecao->id}}</td>
                                    <td>{{$selecao->getNumeroParcela()}}</td>
                                    <td>{{$selecao->forma_pagamento->descricao}}</td>
                                    <td>{{$selecao->data_vencimento}}</td>
                                    <td>{{$selecao->data_pagamento}}</td>
                                    <td>{{$selecao->data_baixa}}</td>
                                    <td>{{$selecao->valor_parcela_real()}}</td>
                                    <td>
                                        @if($selecao->status == 0)
                                            <a class="btn btn-primary btn-xs"
                                               target="_blank"
                                               href="{{route('parcelas.boleto',$selecao->id)}}">
                                                <i class="fa fa-money"></i> Gerar Boleto</a>
                                            <a data-toggle="modal"
                                               data-parcela="{{$selecao}}"
                                               data-valor_real="{{$selecao->valor_parcela_real()}}"
                                               data-target="#modalPagarParcela"
                                               class="btn btn-info btn-xs"><i class="fa fa-money"></i> Pagar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Número de O.S. encontradas: <b>{{$Fechamento->ordem_servicos->count()}}</b></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table border="0" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Situação</th>
                                <th>ID</th>
                                <th>Nº Chamado</th>
                                <th>Data de Abertura</th>
                                <th>Data de Fechamento</th>
                                <th>Técnico</th>
                                <th>Total</th>
                                <th>Total Peças</th>
                                <th>Cliente</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Fechamento->ordem_servicos as $selecao)
                                <tr>
                                    <td>
                                        <button class="btn btn-xs btn-{{$selecao->getStatusType()}}"
                                        >{{$selecao->situacao->descricao}}</button>
                                    </td>
                                    <td>{{$selecao->idordem_servico}}</td>
                                    <td>{{$selecao->numero_chamado}}</td>
                                    <td>{{$selecao->created_at}}</td>
                                    <td>{{$selecao->fechamento}}</td>
                                    <td>{{$selecao->colaborador->nome}}</td>
                                    <td>{{$selecao->getValoresObj()->valor_final}}</td>
                                    <td>{{$selecao->getValoresObj()->valor_total_pecas}}</td>
                                    <td>{{$selecao->cliente->getType()->nome_principal}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                           target="_blank"
                                           href="{{route('ordem_servicos.show',$selecao->idordem_servico)}}">
                                            <i class="fa fa-eye"></i> Abrir</a>
                                        {{--@role('admin')--}}
                                        {{--<a class="btn btn-danger btn-xs"--}}
                                        {{--data-nome="Ordem de Serviço #{{$selecao->idordem_servico}}"--}}
                                        {{--data-href="{{route('ordem_servicos.destroy',$selecao->idordem_servico)}}"--}}
                                        {{--data-toggle="modal"--}}
                                        {{--data-target="#modalDelecao"><i class="fa fa-trash-o"></i> Remover</a>--}}
                                        {{--@endrole--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /page content -->
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
    <script>

        <!-- script consulta NF -->
        $(document).ready(function () {
            $('div#consultaNF').on('show.bs.modal', function (e) {
                var $this = $(this);
                var $loading_modal = $($this).find('div.loading');
                var $origem = $(e.relatedTarget);
                var $listas_nf = $($this).find('ul.listas_nf');
                var $listas_nf = $($this).find('ul.listas_nf');
                $($listas_nf).hide();
                $($this).hide();


                var type = $($origem).data('type'); //if is NFe or NFSe
                var debug = $($origem).data('debug'); //if is/not debug
                var idfechamento = $($origem).data('idfechamento'); //idfechamento

                var href_ = '';
                if (type == 'nfe') {
                    href_ = '{{route('fechamentos.nfe.consulta',['XXX','debug'])}}';
                } else {
                    href_ = '{{route('fechamentos.nfse.consulta',['XXX','debug'])}}';
                }
                href_ = href_.replace('debug', debug);
                href_ = href_.replace('XXX', idfechamento);
                console.log(href_);

                $.ajax({
                    url: href_,
                    type: 'get',
                    dataType: "json",
                    beforeSend: function () {
                        $($loading_modal).show();
                    },
                    complete: function (xhr, textStatus) {
                        $($loading_modal).hide();
                    },
                    error: function (xhr, textStatus) {
                        $($loading_modal).hide();
                        console.log('xhr-error: ' + xhr);
                        console.log('textStatus-error: ' + textStatus);
                    },
                    success: function (json) {
                        console.log(json);
                        if (json.status == 200) {
                            var TIPO_NF = json.type;
                            var REF = json.ref;
                            var BODY = json.body;
                            var STATUS = BODY.status;
                            var URL = json.url;
                            var $parent = $($this).find('div.modal-body ul#' + TIPO_NF);

                            $($parent).show();

                            $($parent).find('b#ref').html(REF);

                            $($parent).find('span.autorizado').hide();
                            $($parent).find('span.erro_autorizacao').hide();

                            if (TIPO_NF == 'nfe') {
                                $($this).find('div.modal-header h4.modal-title b').html('NFe');
                            } else {
                                $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                            }

                            $.each(BODY, function (i, v) {
                                $($parent).find('b#' + i).html(v);
                            });

//                            autorizado – Neste caso a consulta irá conter os demais dados da nota fiscal
//                            processando_autorizacao – A nota ainda está em processamento. Não será devolvido mais nenhum campo além do status
//                            erro_autorizacao – A nota foi enviada ao SEFAZ mas houve um erro no momento da autorização.O campo status_sefaz e mensagem_sefaz irão detalhar o erro ocorrido. O SEFAZ valida apenas um erro de cada vez.
//                            erro_cancelamento – Foi enviada uma tentativa de cancelamento que foi rejeitada pelo SEFAZ. Os campos status_sefaz_cancelamento e mensagem_sefaz_cancelamento irão detalhar o erro ocorrido. Perceba que a nota neste estado continua autorizada.
//                            cancelado – A nota foi cancelada. Além dos campos devolvidos quanto a nota é autorizada, é disponibilizado o campo caminho_xml_cancelamento que contém o protocolo de cancelamento. O campo caminho_danfe deixa de existir quando a nota é cancelada.

                            switch (STATUS) {
                                case 'autorizado': {
                                    $($parent).find('span.autorizado').show();
                                    //autorizado
                                    if (TIPO_NF == 'nfe') {
                                        $($parent).find('b#numero_serie').html(BODY.numero + '/' + BODY.serie);
//                                    $($parent).find('b#url_danfe').html('<a href="' + URL + BODY.caminho_danfe + '" target="_blank">Abrir</a>');
//                                    $($parent).find('b#url_xml_nota_fiscal').html('<a href="' + URL + BODY.caminho_xml_nota_fiscal + '" target="_blank">Abrir</a>');
                                        $($parent).find('a#url_pdf').attr('href', URL + BODY.caminho_danfe);
                                        $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                    } else {
//                                    $($parent).find('a#url_nfse').html('<a href="' + BODY.uri + '" target="_blank">Abrir</a>');
//                                    $($parent).find('b#url_xml_nota_fiscal').html('<a href="' + URL + BODY.caminho_xml_nota_fiscal + '" target="_blank">Abrir</a>');
                                        $($parent).find('a#url_pdf').attr('href', BODY.uri);
                                        $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                    }
                                    break;
                                }
                                case 'erro_autorizacao': {
                                    $($parent).find('span.erro_autorizacao').show();
                                    if (TIPO_NF == 'nfse') {
                                        var ERROS = BODY.erros[0];
                                        $($parent).find('b#codigo').html(ERROS.codigo);
                                        $($parent).find('b#correcao').html(ERROS.correcao);
                                        $($parent).find('b#mensagem').html(ERROS.mensagem);
                                    }
                                    break;
                                }
                            }
                        } else if (json.status == 404) {
                            var TIPO_NF = json.type;
                            var REF = json.ref;
                            var BODY = json.body;
                            var ERROS = BODY.erros[0];
                            var $parent = $($this).find('div.modal-body ul#' + TIPO_NF);

                            $($parent).show();
                            $($parent).find('b#ref').html(REF);

                            $($parent).find('span.autorizado').hide();
                            $($parent).find('span.erro_autorizacao').show();

                            if (TIPO_NF == 'nfe') {
                                $($this).find('div.modal-header h4.modal-title b').html('NFe');
                            } else {
                                $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                            }
                            $($parent).find('b#codigo').html(ERROS.codigo);
                            $($parent).find('b#correcao').html('');
                            $($parent).find('b#mensagem').html(ERROS.mensagem);

                        } else {
                            alert(json.body);
                        }
                    }
                });

            });
            {{--$('div#consultaNfe').on('show.bs.modal', function (e) {--}}
            {{--var $this = $(this);--}}
            {{--var $loading_modal = $($this).find('div.loading');--}}
            {{--var $origem = $(e.relatedTarget);--}}
            {{--var href_ = '{{route('fechamentos.nfe.consulta',['XXX','debug'])}}';--}}
            {{--var id = $($origem).data('idfechamento');--}}
            {{--var debug = $($origem).data('debug');--}}
            {{--href_ = href_.replace('debug', debug);--}}
            {{--href_ = href_.replace('XXX', id);--}}

            {{--console.log(href_);--}}
            {{--$.ajax({--}}
            {{--url: href_,--}}
            {{--type: 'get',--}}
            {{--dataType: "json",--}}
            {{--beforeSend: function () {--}}
            {{--$($loading_modal).show();--}}
            {{--},--}}
            {{--complete: function (xhr, textStatus) {--}}
            {{--$($loading_modal).hide();--}}
            {{--},--}}
            {{--error: function (xhr, textStatus) {--}}
            {{--$($loading_modal).hide();--}}
            {{--console.log('xhr-error: ' + xhr);--}}
            {{--console.log('textStatus-error: ' + textStatus);--}}
            {{--},--}}
            {{--success: function (json) {--}}
            {{--console.log(json);--}}
            {{--if (json.status == 200) {--}}
            {{--var BODY = json.body;--}}
            {{--var URL = json.url;--}}
            {{--var $parent = $($this).find('div.modal-body ul.list-unstyled');--}}
            {{--$.each(BODY, function (i, v) {--}}
            {{--$($parent).find('b#' + i).html(v);--}}
            {{--});--}}
            {{--$($parent).find('b#numero_serie').html(BODY.numero + '/' + BODY.serie);--}}
            {{--$($parent).find('b#url_danfe').html('<a href="' + URL + BODY.caminho_danfe + '" target="_blank">Abrir</a>');--}}
            {{--$($parent).find('b#url_xml_nota_fiscal').html('<a href="' + URL + BODY.caminho_xml_nota_fiscal + '" target="_blank">Abrir</a>');--}}
            {{--//<a href=""></a>--}}
            {{--} else {--}}
            {{--alert(json.body);--}}
            {{--}--}}
            {{--}--}}
            {{--});--}}

            {{--});--}}
        });
    </script>
@endsection