@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')

    <div id="search" class="x_panel animated flipInX">
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {!! Form::open(array('route'=>['notas_fiscais.index', Request::route('tipo')],
                        'method'=>'GET','id'=>'search',
                        'class' => 'form-horizontal form-label-left')) !!}
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Inicial:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input value="{{Request::get('data_inicial')}}"
                               type="text" class="form-control data-to-now" name="data_inicial" placeholder="Data"
                               required>
                    </div>
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Final:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input value="{{Request::get('data_final')}}"
                               type="text" class="form-control data-to-now" name="data_final" placeholder="Data"
                               required>
                    </div>
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">CNPJ:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input value="{{Request::get('cnpj')}}"
                               type="text" class="form-control show-cnpj" name="cnpj" placeholder="CNPJ">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar</button>
						</span>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="ln_solid"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {!! Form::open(array('route'=>['notas_fiscais.index', Request::route('tipo')],
                        'method'=>'GET','id'=>'porid',
                        'class' => 'form-horizontal form-label-left')) !!}
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">ID:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input value="{{Request::get('ref')}}" type="text" class="form-control"
                               name="ref" placeholder="REF">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar por REF</button>
						</span>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @if($Buscas->count() > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2><b>{{$Buscas->count()}}</b> {{$Page->Targets}} encontrados</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>REF</th>
                                <th>Data</th>
                                <th>CNPJ</th>
                                <th>Cliente</th>
                                <th>Faturamento</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Buscas as $selecao)
                                <?php $tipo_cliente = $selecao->cliente->getType(); ?>
                                <tr>
                                    <td>{{$selecao->{$Page->extras['ref']} }}</td>
                                    <td>{{$selecao->getDataNF($Page->extras['data_nf'])}}</td>
                                    <td>{{$tipo_cliente->entidade}}</td>
                                    <td><a href="{{route('clientes.show',$selecao->idcliente)}}"
                                        >{{$tipo_cliente->nome_principal}}</a>
                                    </td>
                                    <td>
                                        <a class="btn btn-default btn-xs" target="_blank"
                                           href="{{route('faturamentos.show',$selecao->id)}}"
                                        ><i class="fa fa-eye"></i> Visualizar Faturamento</a>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                        ><i class="fa fa-edit"></i> Abrir {{$Page->extras['nome_nf']}}</a>
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
    <!-- /page content -->
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
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 5,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [1, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection