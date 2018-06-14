@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
<!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <style>
        .preco {
            font-size: 13px;
            font-weight: 400;
            color: #26B99A;
        }
    </style>
<!-- Select2 -->
@include('helpers.select2.head')
@endsection
@section('page_content')
    @include('layouts.modals.sintegra')
    <div class="x_panel">
        @if(!$Cliente->validado())
            <div class="x_title">
                <h2>Cliente - {{($Cliente->getType()->tipo_cliente)?'Pessoa Jurídica':'Pessoa Física'}} </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button onclick="window.location.href='{{route('cliente.validar',$Cliente->idcliente)}}'"
                                class="btn btn-success"><i class="fa fa-check fa-2"></i> Validar
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="alert alert-danger fade in" role="alert">
                Este cliente ainda não foi validado!
            </div>
        @else
            <div class="x_title">
                <h3>Cliente - {{($Cliente->getType()->tipo_cliente)?'Pessoa Jurídica':'Pessoa Física'}} </h3>
                <div class="clearfix"></div>
            </div>
        @endif
        <div class="x_content">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 profile_left">
                @include('pages.'.$Page->link.'.paineis.perfil')
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" @if($Page->tab == 'sobre')class="active" @endif><a href="#tab_sobre" role="tab" data-toggle="tab" aria-expanded="true">Sobre</a></li>
                        <li role="presentation" @if($Page->tab == 'instrumentos')class="active" @endif><a href="#tab_instrumentos" role="tab" data-toggle="tab" aria-expanded="false">Instrumentos</a></li>
                        <li role="presentation" @if($Page->tab == 'equipamentos')class="active" @endif><a href="#tab_equipamentos" role="tab" data-toggle="tab" aria-expanded="false">Equipamentos</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'sobre')active in @endif fade" id="tab_sobre" aria-labelledby="home-tab">
                            @include('pages.'.$Page->link.'.paineis.sobre')
                        </div>
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'instrumentos') active in @endif fade" id="tab_instrumentos" aria-labelledby="profile-tab">
                            @include('pages.'.$Page->link.'.paineis.instrumentos')
                        </div>
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'equipamentos') active in @endif fade" id="tab_equipamentos" aria-labelledby="profile-tab">
                            @include('pages.'.$Page->link.'.paineis.equipamentos')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_content')

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>

    <!-- Clientes.js -->
    @include('pages.clientes.scripts.js')

    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 10,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
    <script type="text/javascript">
        $(document).ready(function () {
            $("form#form-client").on('submit',function(){
                $(this).find('input.show-dinheiro').each(function(){
                    var value = $(this).maskMoney('unmasked')[0];
                    $(this).maskMoney('destroy');
                    $(this).val(value);

                });
            });
        });
    </script>
@endsection

