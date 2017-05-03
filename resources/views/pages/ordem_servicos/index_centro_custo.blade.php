@extends('layouts.template')
@section('modals_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <div id="search" class="x_panel animated flipInX">
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
                {!! Form::open(array('route'=>'ordem_servicos.index_centro_custo',
                    'method'=>'GET','id'=>'search',
                    'class' => 'form-horizontal form-label-left')) !!}
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Data de Abertura:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{Request::get('data')}}"
                           type="text" class="form-control data-to-now" name="data" placeholder="Data" required>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Tipo:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <select name="situacao" class="form-control select2_single" required>
                        @foreach($Page->extras['situacao_ordem_servico'] as $key => $value)
                            <option value="{{$key}}"
                                    @if(Request::has('situacao') && Request::get('situacao')==$key) selected @endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Cliente:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <select name="idcentro_custo" class="form-control select2_single">
                        <option value="">Todos</option>
                        @foreach($Page->extras['centro_custos'] as $centro_custo)
                            <option value="{{$centro_custo->idcliente}}"
                                    @if(Request::has('idcentro_custo') && Request::get('idcentro_custo')==$centro_custo->idcliente) selected @endif>{{$centro_custo->getType()->nome_principal}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar</button>
						</span>
                </div>
                {!! Form::close() !!}
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
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fantasia</th>
                                <th>Razão Social</th>
                                <th>Documento</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Buscas as $cliente)
                                <?php $tipo_cliente = $cliente->getType(); ?>
                                <tr>
                                    <td>{{$cliente->idcliente}}</td>
                                    <td>{{$tipo_cliente->nome_principal}}</td>
                                    <td>{{$tipo_cliente->razao_social}}</td>
                                    <td>{{$tipo_cliente->entidade}}</td>
                                    <td>
                                        {{Form::open(['method' => 'GET', 'route' => $Page->link.'.show_centro_custo'])}}
                                        <input type="hidden" name="data" value="{{Request::get('data')}}">
                                        <input type="hidden" name="situacao" value="{{Request::get('situacao')}}">
                                        <input type="hidden" name="idcentro_custo" value="{{$cliente->idcliente}}">
                                        <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-edit"></i>
                                            Visualizar O.S.
                                        </button>
                                        {{Form::close()}}
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
                    "pageLength": 20,
                    "columnDefs": [{
                        "targets": 0,
                    }],
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection