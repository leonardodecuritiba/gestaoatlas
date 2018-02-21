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

    <section class="row" id="search">
        <div class="x_panel animated flipInX">
            <div class="x_title">
                <h2>{{$Page->titulo_primario}}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {!! Form::open(array('route'=>'budgets.create',
                                        'method'=>'GET','id'=>'search',
                                        'class' => 'form-horizontal form-label-left')) !!}
                        <div class="col-md-12 col-sm-12 col-xs-12 input-group input-group-lg">
                            <input id="buscar" name="busca" type="text" class="form-control"
                                   value="{{Request::get('busca')}}" placeholder="Buscar Cliente...">
                            <span class="input-group-btn">
                                        <button class="btn btn-info" type="submit">Buscar</button>
                                    </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row">
        @if($Page->response->count() > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{$Page->response->count()}} clientes encontrados</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                            <table id="datatable-responsive"
                                   class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>CNPJ/CPF</th>
                                    <th>Responsável</th>
                                    <th>Fone</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Page->response as $sel)
                                    <tr>
                                        <td>{{$sel['id']}}</td>
                                        <td>{{$sel['name']}}</td>
                                        <td>{{$sel['document']}}</td>
                                        <td>{{$sel['responsible']}}</td>
                                        <td>{{$sel['phone']}}</td>
                                        <td>
                                            <a class="btn btn-success btn-xs"
                                               href="{{route('budgets.open', $sel['id'])}}">
                                                <i class="fa fa-edit"></i> Abrir</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('layouts.search.no-results')
        @endif
    </section>

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


    <!-- Select2 -->
    @include('helpers.select2.foot')
    <!-- /Select2 -->
@endsection