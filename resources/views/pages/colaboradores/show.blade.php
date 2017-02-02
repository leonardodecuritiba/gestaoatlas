@extends('layouts.template')
@section('style_content')
{{--@include('admin.master.forms.search')--}}
{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />--}}
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

<!-- Select2 -->
@include('helpers.select2.head')
    @if($Colaborador->hasRole('tecnico'))
        <!-- Datatables -->
        @include('helpers.datatables.head')
    @endif
@endsection
@section('page_content')
    @if($Colaborador->hasRole('tecnico'))
        @include('pages.colaboradores.modal.selolacre')
    @endif
    @role('admin')
        @include('pages.colaboradores.modal.pwd')
    @endrole
    <?php $existe_entidade = 1; $Entidade=$Colaborador; ?>
    <div class="x_panel">
        <div class="x_title">
            <h3>Colaborador - {{$Colaborador->is()->display_name}}</h3>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                @include('pages.'.$Page->link.'.paineis.perfil')
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <?php
                    if($Colaborador->hasRole('tecnico')){
                        $tabs = [
                                array('link'=>'sobre','descricao'=>'Sobre'),
                                array('link'=>'selos_lacres','descricao'=>'Selos/Lacres')
                        ];//,'estoque','ferramentas','lacres','selos','tarefas'];
                    } else {
                        $tabs = [array('link'=>'sobre','descricao'=>'Sobre')];
                    }
                    ?>
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        @foreach($tabs as $tab)
                            <li role="presentation" @if($Page->tab == $tab['link'])class="active" @endif><a href="#tab_{{$tab['link']}}" role="tab" data-toggle="tab" aria-expanded="true">{{$tab['descricao']}}</a></li>
                        @endforeach
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        @foreach($tabs as $tab)
                            <div role="tabpanel" class="tab-pane @if($Page->tab == $tab['link'])active in @endif fade" id="tab_{{$tab['link']}}" aria-labelledby="{{$tab['link']}}-tab">
                                @include('pages.'.$Page->link.'.paineis.'.$tab['link'])
                            </div>
                        @endforeach
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
    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
    <script>
        list_required_tecnico = [
            'carteira_imetro',
            'carteira_ipem'];
        $.each(list_required_tecnico, function(i,v){
            $("input[name=" + v + "]").attr('required', false);
        });

        $(document).ready(function() {
            $("select[name=tipo_cadastro]").change(function () {
                if($(this).val()=="2"){
                    $('section#form_tecnico').show();
                    $.each(list_required_tecnico, function(i,v){
                        $("input[name=" + v + "]").attr('required', true);
                    })
                } else {
                    $('section#form_tecnico').hide();
                    $.each(list_required_tecnico, function(i,v){
                        $("input[name=" + v + "]").attr('required', false);
                    })
                }
            });
        });
    </script>

    @if($Colaborador->hasRole('tecnico'))
        <!-- Datatables -->
        @include('helpers.datatables.foot')
        <script>
            $(document).ready(function () {
                $('.dt-responsive').DataTable(
                    {
                        "language": language_pt_br,
                        "pageLength": 5,
                        "columnDefs": [{
                            "targets": 0,
                            "orderable": false
                        }],
                        "bLengthChange": false, //used to hide the property
                        "bFilter": false
                    }
                );
            });
        </script>
        <!-- /Datatables -->
    @endif
@endsection

