@extends('layouts.template')
@section('page_content')
    {{--@include('admin.master.forms.search')--}}
    <!-- mascaras -->
    <?php $existe_entidade = 0; ?>
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        {!! Form::open(['route' => $Page->link.'.store', 'files' => true, 'method' => 'POST',
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Dados do Colaborador</h2>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="select2_single form-control" name="tipo_cadastro" tabindex="-1" required>
                                    <option value="">Tipo de Cadastro</option>
                                    @foreach($Page->extras['Role'] as $role)
                                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            @include('pages.forms.form_colaborador')
                        </div>
                    </div>
                </div>
            </section>
            <section class="row" id="form_tecnico" style="display:none;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        @include('pages.forms.form_tecnico')
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        @include('pages.forms.form_contato')
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <button type="reset" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                        </div>
                    </div>
                </div>
            </section>
        {{ Form::close() }}

    </div>


@endsection
@section('scripts_content')
    <!-- tags -->
    {!! Html::script('js/tags/jquery.tagsinput.min.js') !!}
    <!-- switchery -->
    {!! Html::script('js/switchery/switchery.min.js') !!}
    <!-- richtext editor -->
    {!! Html::script('js/editor/bootstrap-wysiwyg.js') !!}
    {!! Html::script('js/editor/external/jquery.hotkeys.js') !!}
    {!! Html::script('js/editor/external/google-code-prettify/prettify.js') !!}
    <!-- select2 -->
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
    <!-- textarea resize -->

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
@endsection