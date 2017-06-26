@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('modals_content')
    @include('layouts.modals.notifications')
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
                    Situação do Faturamento: <b>{{$Faturamento->getStatusText()}}</b>
                </div>
                <div class="profile_details">
                    <div class="well perfil">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <h4>{{$Faturamento->getTipoFaturamento()}}:
                                    <a target="_blank"
                                       href="{{route('clientes.show', $Faturamento->idcliente)}}"><i>{{$Faturamento->cliente->getType()->nome_principal}}</i></a>
                                </h4>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-calendar"></i> Data da Fechamento:
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
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                @role('admin')
                                <a class="btn btn-danger pull-right"
                                   href="{{route('faturamentos.remover',$Faturamento->id)}}">
                                    <i class="fa fa-trash fa-2"></i> Excluir Faturamento</a>
                                @endrole
                                @if($Faturamento->isAberto())
                                    <a data-toggle="modal"
                                       data-idfaturamento="{{$Faturamento->id}}"
                                       data-target="#modalAviso"
                                       class="btn btn-success pull-right"><i class="fa fa-check fa-2"></i> Finalizar
                                        Faturamento</a>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <ul class="list-unstyled">
                                    @if($Faturamento->getStatusNfeHomologacao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfaturamento="{{$Faturamento->id}}"
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
                                               data-idfaturamento="{{$Faturamento->id}}"
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
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <ul class="list-unstyled pull-right">
                                    @if($Faturamento->getStatusNfeProducao())
                                        <li>
                                            <a data-toggle="modal"
                                               data-idfaturamento="{{$Faturamento->id}}"
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
                                               data-idfaturamento="{{$Faturamento->id}}"
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

                        <?php $Valores = $Faturamento->getValoresReal();?>
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
                                               class="btn btn-info btn-xs"><i class="fa fa-money"></i> Receber</a>
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
    @include('helpers.notas_fiscais.nf')
    <script>
        <!-- script Aviso -->
        $(document).ready(function () {
            $('div#modalAviso').on('show.bs.modal', function (e) {

                var $this = $(this);
                var $origem = $(e.relatedTarget);
                var idfaturamento = $($origem).data('idfaturamento');

                var $parent = $($this).find('div.modal-content');
                $($parent).find('div.modal-header h4.modal-title').html('<i class="fa fa-exclamation-triangle"></i> Atenção! Deseja finalizar o Faturamento?');
                $($parent).find('div.modal-body p')
                    .html('Certifique que as notas e boletos foram geradas! Verifique também se as mesmas foram enviadas para o cliente!');

                var _URL_ = '{{route('faturamentos.fechar','_ID_')}}';
                _URL_ = _URL_.replace('_ID_', idfaturamento);
                $($parent).find('div.modal-footer a').attr('href', _URL_);
            });
        });
    </script>
@endsection