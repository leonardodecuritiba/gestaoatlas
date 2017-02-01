@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <!-- Select2 -->
    {!! Html::style('vendors/select2/dist/css/select2.min.css') !!}
@endsection
@section('page_content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
            {!! Form::open(['route'=>[$Page->link.'.update',$Peca->idpeca],
                'method' => 'PATCH',
                'files' => true,
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <section class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>{{$Page->Target}} @if(isset($Peca)) (#{{$Peca->idpeca}}) @endif</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content div_pai">
                                <div class="form-horizontal form-label-left">
                                    @include('pages.'.$Page->link.'.forms.form')
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @role('admin')
                    <section class="row">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <a href="{{route($Page->link.'.index')}}" class="btn btn-danger btn-lg btn-block">Cancelar</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </section>
                @endrole
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
    <!-- Select2 -->
    {!! Html::script('vendors/select2/dist/js/select2.min.js') !!}
    {!! Html::script('vendors/select2/dist/js/i18n/pt-BR.js') !!}
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
        var $_SELECT2_AJAX = [];
        $_SELECT2_AJAX['url'] = "{{url('get_ajaxSelect2')}}";
        $_SELECT2_AJAX['field'] = 'codigo';
        $_SELECT2_AJAX['table'] = 'ncm';
        $_SELECT2_AJAX['pk'] = 'idncm';
        $_SELECT2_AJAX['action'] = 'busca_por_campo';
    </script>
    @include('helpers.select2.get_ajax');

    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single-ajax").empty()
                .append('<option value="{{$Peca->peca_tributacao->idncm}}">{{$Peca->peca_tributacao->ncm->codigo}}</option>')
                .val('{{$Peca->peca_tributacao->idncm}}').trigger('change');
        });
    </script>
    <script>
        var $container_form = $('section#peca');

        var required_fields = ['codigo',
            'descricao',
            'descricao_tecnico',
            'tipo',
            'idmarca',
            'idunidade',
            'idgrupo',
            'comissao_tecnico',
            'comissao_vendedor',
            'custo_final'
        ];

        $(document).ready(function(){
            $.each(required_fields, function (i, v) {
                $($container_form).find('input[name="' + v + '"]').prop('required', true);
            });
        });
    </script>
@endsection