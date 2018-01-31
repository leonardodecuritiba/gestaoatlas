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

	<section id="search" class="x_panel animated flipInX">
		<div class="x_content">
			{!! Form::open(array('route'=>'budgets.select',
                'method'=>'POST','id'=>'search',
                'class' => 'form-horizontal form-label-left')) !!}
                <div class="row">
                    {!! Html::decode(Form::label('type', 'Tipo',
                        array('class' => 'control-label col-md-1 col-sm-1 col-xs-12'))) !!}
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        {{Form::select('type', $Page->extras['types'], old('type'), ['class'=>'form-control select2_single', 'required'])}}
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <button class="btn btn-info" type="submit">Buscar</button>
                    </div>
                </div>
			{!! Form::close() !!}
		</div>
	</section>


    @if(count($Page->response) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->response->count()}} registros encontrados</h2>
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
                                           href="{{route('budgets.open', [$sel['type'],$sel['id']])}}">
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
    @elseif(Request::has('type'))
        @include('layouts.search.no-results')
    @endif

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