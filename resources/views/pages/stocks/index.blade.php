@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    <!-- Select2 -->
    @include('helpers.select2.head')
    <!-- /Select2 -->
@endsection
@section('page_content')

    <div class="x_panel animated flipInX">
        <div class="x_title">
            <h2>Cadastrar {{$Page->Target}}</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                {!! Form::open(['route' => $Page->extras['type_stock'].'.stocksStore',
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                @include('pages.stocks.forms.' . $Page->extras['type_stock'])
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                        <button type="reset" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                    </div>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    @if(count($Buscas) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->Targets}} encontrados</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        @include('pages.stocks.lists.'. $Page->extras['type_stock'])
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
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
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
    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
    <!-- /Select2 -->
@endsection