@extends('layouts.template')
@section('modals_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    @include('layouts.modals.delete')
@endsection
@section('page_content')
    @include('pages.ordem_servicos.popup.cliente')
    <!-- Seach form -->
    {{--@include('layouts.search.form')--}}
    <!-- Upmenu form -->

    <?php
    if (Request::get('centro_custo') == 0) {
        $ClientePrincipal = $Buscas[0]->cliente->getType();
    } else {
        $ClientePrincipal = $Buscas[0]->centro_custo->getType();
    }
    ?>
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Simulador de Fechamento</h2>
                <a class="btn btn-success pull-right"
                   href="{{route('faturamentos.faturar',[(Request::get('centro_custo')) ? 1:0,$ClientePrincipal->idcliente])}}">
                    <i class="fa fa-money fa-2"></i> Faturar</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <h2>{{(Request::get('centro_custo')==0) ? 'Cliente' : 'Centro de Custo'}}</h2>
                        <a href="{{route('clientes.show',$ClientePrincipal->idcliente)}}">{{$ClientePrincipal->razao_social}}</a>
                        <div class="ln_solid"></div>
                    </div>
                </div>
                @include('pages.ordem_servicos.parts.resumo_valores')
            </div>
        </div>
    </section>

    <section class="row">
        @if(Request::get('centro_custo')==0)
            <?php $OrdemServicos = $Buscas; ?>
            @include('pages.faturamentos.panels.lists_ordem_servico_valores')
        @else
            <?php $Clientes = $Buscas; ?>
            @include('pages.faturamentos.panels.lists_clientes_ordem_servico_valores')
        @endif
    </section>
    <!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 20,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
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