@extends('layouts.template')
@section('style_content')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    @include('layouts.search.form')
    <!-- Upmenu form -->
    <?php $route_importacao = "#";  ?>
    <?php $route_exportacao = "#"; ?>
    @include('layouts.menus.upmenu-reduzido')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        {!! Form::open(['method' => 'PATCH',
            'files' => 'true',
            'route'=>[$Page->link.'.update',$Data->id],
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{$Page->titulo_secundario}}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            @include('pages.'.$Page->path.$Page->link.'.forms.form')
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
    </div>
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>

@endsection