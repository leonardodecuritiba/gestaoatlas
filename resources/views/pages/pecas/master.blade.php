@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    {{--@include('admin.master.forms.search')--}}
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        {!! Form::open(['route' => $Page->link.'.store',
            'method' => 'POST',
            'files' => true,
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content div_pai">
                        <div class="form-horizontal form-label-left">
                            @include('pages.'.$Page->link.'.forms.form')
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
        {{ Form::close() }}

    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
        $(document).ready(function(){
            var remoteDataConfigNCM = {
                width: 'resolve',
                ajax: {
                    url: "{{url('getNcm')}}",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            value: params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        console.log(data);
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                language: "pt-BR"
//                templateResult: formatState
            };
            $("select[name='idncm']").select2(remoteDataConfigNCM);
        });
    </script>
@endsection