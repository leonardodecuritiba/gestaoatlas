@extends('layouts.template')
@section('modals_content')
    @include('layouts.modals.delete')
@endsection
@section('page_content')
    @include('pages.ordem_servicos.popup.cliente')
    <!-- Seach form -->
    {{--@include('layouts.search.form')--}}
    <!-- Upmenu form -->
    @if(count($Buscas) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->Targets}} encontrados</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <?php $tipo_cliente = $CentroCusto->getType(); ?>
                        <h2>Centro de Custo: </h2>
                        <a href="{{route('clientes.show',$CentroCusto->idcliente)}}">{{$tipo_cliente->razao_social}}</a>
                        <div class="ln_solid"></div>
                    </div>
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
                                <th>Técnico</th>
                                <th>Cliente</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Buscas as $selecao)
                                <tr>
                                    <td>
                                        <button class="btn btn-xs
										<?php
                                        switch ($selecao->idsituacao_ordem_servico) {
                                            case '1':
                                                echo 'btn-success';
                                                break;
                                            case '2':
                                                echo 'btn-warning';
                                                break;
                                            case '3':
                                                echo 'btn-danger';
                                                break;
                                        }
                                        ?>"
                                        >{{$selecao->situacao->descricao}}</button>
                                    </td>
                                    <td>{{$selecao->idordem_servico}}</td>
                                    <td>{{$selecao->numero_chamado}}</td>
                                    <td>{{$selecao->created_at}}</td>
                                    <td>{{$selecao->colaborador->nome}}</td>
                                    <td>{{$selecao->cliente->getType()->nome_principal}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                           href="{{route('ordem_servicos.show',$selecao->idordem_servico)}}">
                                            <i class="fa fa-eye"></i> Abrir</a>
                                        @role('admin')
                                        <a class="btn btn-danger btn-xs"
                                           data-nome="Ordem de Serviço #{{$selecao->idordem_servico}}"
                                           data-href="{{route('ordem_servicos.destroy',$selecao->idordem_servico)}}"
                                           data-toggle="modal"
                                           data-target="#modalDelecao"><i class="fa fa-trash-o"></i> Remover</a>
                                        @endrole
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {!! $Buscas->appends(Request::only('busca'))->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('layouts.search.no-results')
    @endif
    <!-- /page content -->
@endsection
@section('scripts_content')
    <script>
        <!-- script deleção -->
        $(document).ready(function () {

            $('div#modalDelecao').on('show.bs.modal', function (e) {
                $origem = $(e.relatedTarget);
                nome_ = $($origem).data('nome');
                href_ = $($origem).data('href');
                $el = $($origem).data('elemento');
                $(this).find('.modal-body').html('Você realmente deseja remover <strong>' + nome_ + '</strong> e suas relações? Esta ação é irreversível!');
                $(this).find('.btn-ok').click(function () {
                    window.location.replace(href_);
                });
            });
        });
    </script>
@endsection