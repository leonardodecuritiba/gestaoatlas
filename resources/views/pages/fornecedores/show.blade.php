@extends('layouts.template')
@section('style_content')
{{--@include('admin.master.forms.search')--}}
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />--}}
    {!! Html::style('css/icheck/flat/green.css') !!}
    <style>
        .select2 {
            width: 100%;
        }
        .preco {
            font-size: 13px;
            font-weight: 400;
            color: #26B99A;
        }
    </style>
@endsection
@section('page_content')
    <div class="x_panel">
        <div class="x_title">
            <h3>Fornecedor - {{($Fornecedor->getType()->tipo_fornecedor)?'Pessoa Jurídica':'Pessoa Física'}}</h3>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                @include('pages.'.$Page->link.'.paineis.perfil')
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" @if($Page->tab == 'sobre')class="active" @endif><a href="#tab_sobre" role="tab" data-toggle="tab" aria-expanded="true">Sobre</a></li>
                        {{--<li role="presentation" @if($Page->tab == 'pecas')class="active" @endif><a href="#tab_pecas" role="tab" data-toggle="tab" aria-expanded="false">Peças</a></li>--}}
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'sobre')active in @endif fade" id="tab_sobre" aria-labelledby="home-tab">
                            @include('pages.'.$Page->link.'.paineis.sobre')
                        </div>
                        {{--<div role="tabpanel" class="tab-pane @if($Page->tab == 'pecas') active in @endif fade" id="tab_pecas" aria-labelledby="profile-tab">--}}
                        {{--@include('pages.'.$Page->link.'.paineis.pecas')--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
    <!-- textarea resize -->
    {!! Html::script('build/js/script_pjuridica.js') !!}
    <script>
        //AJUSTA LAYOUT TIPO FORNECEDOR
        $(document).ready(function() {
            $("select[name=tipo_fornecedor]").change(function () {
                if($(this).val()=="1"){
                    $('section#form_pfisica').hide();
                    $("input[name=cpf]").attr('required', false);

                    $('section#form_pjuridica').show();
                    $.each(list_required_pjuridica, function(i,v){
                        $("input[name=" + v + "]").attr('required', true);
                    })
                } else {
                    $('section#form_pfisica').show();
                    $("input[name=cpf]").attr('required', true);

                    $('section#form_pjuridica').hide();
                    $.each(list_required_pjuridica, function(i,v){
                        $("input[name=" + v + "]").attr('required', false);
                    })
                }
            });
        });
    </script>
@endsection

