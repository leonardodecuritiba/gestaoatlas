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
                <div class="alert fade in alert-{{$Faturamento->getStatusType()}}" role="alert">
                    Situação do Fechamento: <b>{{$Faturamento->getStatusText()}}</b>
                </div>
                <div class="profile_details">
                    <div class="well perfil">

                        <h4>{{$Faturamento->getTipoFechamento()}}:
                            <a target="_blank"
                               href="{{route('clientes.show', $Faturamento->idcliente)}}"><i>{{$Faturamento->cliente->getType()->nome_principal}}</i></a>
                            @role('admin')
                            <a class="btn btn-danger pull-right"
                               href="{{route('faturamentos.remover',$Faturamento->id)}}">
                                <i class="fa fa-trash fa-2"></i> Excluir Fechamento</a>
                            @endrole
                        </h4>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-calendar"></i> Data do Fechamento:
                                <b>{{$Faturamento->created_at}}</b></li>
                            <li><i class="fa fa-credit-card"></i> Tipo de Emissão (Técnica):
                                <b>{{$Faturamento->cliente->tipo_emissao_tecnica->descricao}}</b></li>
                            <li><i class="fa fa-credit-card"></i> Forma de Pagamento (Técnica):
                                <b>{{$Faturamento->cliente->forma_pagamento_tecnica->descricao}}</b></li>
                            <li><i class="fa fa-info"></i> Pagamento: <b
                                        class="text-{{$Faturamento->getPagoStatusColor()}}">{{$Faturamento->getPagoText()}}</b>
                            </li>
                            <li><i class="fa fa-money"></i> Total Pendente: <b
                                        class="text-danger">{{$Faturamento->getTotalPendenteReal()}}</b>
                            </li>
                            <li><i class="fa fa-money"></i> Total Recebido: <b
                                        class="text-success">{{$Faturamento->getTotalPagoReal()}}</b>
                            </li>
                        </ul>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <ul class="list-unstyled">
                                    @if($Faturamento->getStatusNfeHomologacao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfechamento="{{$Faturamento->id}}"
                                               data-type="nfe"
                                               data-target="#consultaNF"
                                               data-debug="1"
                                               class="btn btn-warning"><i class="fa fa-search"></i> Consultar NFe
                                                (Homologação)</a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{route('faturamentos.nf.send',[$Faturamento->id, $debug = true, 'nfe'])}}"
                                               class="btn btn-primary"><i class="fa fa-info fa-2"></i> Gerar NFe
                                                (Homologação)</a>
                                        </li>
                                    @endif
                                    @if($Faturamento->getStatusNFSeHomologacao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfechamento="{{$Faturamento->id}}"
                                               data-type="nfse"
                                               data-target="#consultaNF"
                                               data-debug="1"
                                               class="btn btn-warning"><i class="fa fa-search"></i> Consultar NFSe
                                                (Homologação)</a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{route('faturamentos.nf.send',[$Faturamento->id, $debug = true, 'nfse'])}}"
                                               class="btn btn-primary"><i class="fa fa-info fa-2"></i> Gerar NFSe
                                                (Homologação)</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <ul class="list-unstyled pull-right">
                                    @if($Faturamento->getStatusNfeProducao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfechamento="{{$Faturamento->id}}"
                                               data-type="nfe"
                                               data-target="#consultaNF"
                                               data-debug="0"
                                               class="btn btn-warning"><i class="fa fa-search"></i> Consultar
                                                NFe</a>
                                        </li>
                                    @elseif($Faturamento->getStatusNfeHomologacao())
                                        <li>
                                            <a href="{{route('faturamentos.nf.send',[$Faturamento->id, $debug = 0, 'nfe'])}}"
                                               class="btn btn-primary"><i class="fa fa-info fa-2"></i>
                                                Gerar NFe</a>
                                        </li>
                                    @endif
                                    @if($Faturamento->getStatusNFSeProducao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfechamento="{{$Faturamento->id}}"
                                               data-type="nfse"
                                               data-target="#consultaNF"
                                               data-debug="0"
                                               class="btn btn-warning"><i class="fa fa-search"></i> Consultar
                                                NFSe</a>
                                        </li>
                                    @elseif($Faturamento->getStatusNFSeHomologacao())
                                        <li>
                                            <a href="{{route('faturamentos.nf.send',[$Faturamento->id, $debug = 0, 'nfse'])}}"
                                               class="btn btn-primary"><i class="fa fa-info fa-2"></i> Gerar
                                                NFSe</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <?php $Valores = $Faturamento->getValores();?>
                        @include('pages.ordem_servicos.parts.resumo_valores')
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
                                <th>Parcela</th>
                                <th>Forma de Pagamento</th>
                                <th>Data de Vencimento</th>
                                <th>Data de Pagamento</th>
                                <th>Data de Baixa</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Faturamento->pagamento->parcelas as $selecao)
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
                                        @if($selecao->recebida() == 0)
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
        <?php $OrdemServicos = $Faturamento->ordem_servicos; ?>
        @include('pages.faturamentos.panels.lists_ordem_servico')
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
                var $erros_nf = $($this).find('ul.erros_nf');
                var $btn_refresh = $($this).find('div.modal-footer a#btn-refresh');
                var type = $($origem).data('type'); //if is NFe or NFSe
                var debug = $($origem).data('debug'); //if is/not debug
                var idfechamento = $($origem).data('idfechamento'); //idfechamento

                $($listas_nf).hide();
                $($erros_nf).hide();
                $($this).hide();

                $($btn_refresh).hide();
                $($btn_refresh).attr('href', '');

                var href_ = '';
                href_ = '{{route('faturamentos.nf.get',['XXX','debug','type'])}}';
                href_ = href_.replace('XXX', idfechamento);
                href_ = href_.replace('debug', debug);
                href_ = href_.replace('type', type);
                console.log(href_);

                var url_refresh = '';
                url_refresh = '{{route('faturamentos.nf.resend',['XXX','debug','type'])}}';
                url_refresh = url_refresh.replace('XXX', idfechamento);
                url_refresh = url_refresh.replace('debug', debug);
                url_refresh = url_refresh.replace('type', type);


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
                            var $parent = $($this).find('div.modal-body ul.listas_nf');

                            $($parent).show();

                            $($parent).find('b#ref').html(REF);

                            $($parent).find('span.esconda').hide();

                            if (TIPO_NF == 'nfe') {
                                $($this).find('div.modal-header h4.modal-title b').html('NFe');
                            } else {
                                $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                            }
                            $($this).find('div.modal-header h4.modal-title i').html(json.profile);

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
                                    $($parent).find('span#' + TIPO_NF).show();
                                    //autorizado
                                    if (TIPO_NF == 'nfe') {
                                        $($parent).find('b#numero_serie').html(BODY.numero + '/' + BODY.serie);
                                        $($parent).find('a#url_pdf').attr('href', URL + BODY.caminho_danfe);
                                        $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                    } else {
                                        $($parent).find('a#url_pdf').attr('href', BODY.uri);
                                        $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                    }
                                    break;
                                }
                                case 'erro_autorizacao': {
                                    $($btn_refresh).show();
                                    $($btn_refresh).attr('href', url_refresh);

//                                    if (TIPO_NF == 'nfse') {
//                                        var ERROS = BODY.erros;
//                                        $($parent).find('b#codigo').html(ERROS.codigo);
//                                        $($parent).find('b#correcao').html(ERROS.correcao);
//                                        $($parent).find('b#mensagem').html(ERROS.mensagem);
//                                    }
                                    break;
                                }
                            }
                        } else if (json.status == 404) {
                            var TIPO_NF = json.type;
                            var REF = json.ref;
                            var BODY = json.body;
                            var ERROS = BODY.erros[0];

                            $($erros_nf).show();
                            $($erros_nf).find('b#ref').html(REF);

                            if (TIPO_NF == 'nfe') {
                                $($this).find('div.modal-header h4.modal-title b').html('NFe');
                            } else {
                                $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                            }
                            $($this).find('div.modal-header h4.modal-title i').html(json.profile);

                            $($erros_nf).find('b#codigo').html(ERROS.codigo);
                            $($erros_nf).find('b#mensagem').html(ERROS.mensagem);

                        } else {
                            alert(json.body);
                        }
                    }
                });

            });

        });
    </script>
@endsection