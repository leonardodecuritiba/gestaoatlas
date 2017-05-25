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
                    {!! Form::open(array('route'=>['faturamentos.index', Request::route('centro_custo')],
                        'method'=>'GET','id'=>'search',
                        'class' => 'form-horizontal form-label-left')) !!}
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Tipo:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <select name="situacao" class="form-control select2_single">
                            <option value="">Todos</option>
                            @foreach($Page->extras['status_fechamento'] as $value)
                                <option value="{{$value->id}}"
                                        @if(Request::has('situacao') && Request::get('situacao')==$value->id) selected @endif>{{$value->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Cliente:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <select name="idcliente" class="form-control select2_single">
                            <option value="">Todos</option>
                            @foreach($Page->extras['clientes'] as $cliente)
                                <option value="{{$cliente->idcliente}}"
                                        @if(Request::has('idcliente') && Request::get('idcliente')==$cliente->idcliente) selected @endif>{{$cliente->getType()->nome_principal}}</option>
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
            <div class="ln_solid"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {!! Form::open(array('route'=>['faturamentos.index',  Request::route('centro_custo')],
                        'method'=>'GET','id'=>'porid',
                        'class' => 'form-horizontal form-label-left')) !!}
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">ID:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input value="{{Request::get('idfaturamento')}}" type="text" class="form-control"
                               name="idfaturamento" placeholder="ID do Faturamento">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar por ID</button>
						</span>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            {{--<div class="ln_solid"></div>--}}
            {{--<div class="row">--}}
            {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
            {{--{!! Form::open(array('route'=>['faturamentos.index',  Request::route('centro_custo')],--}}
            {{--'method'=>'GET','id'=>'porid',--}}
            {{--'class' => 'form-horizontal form-label-left')) !!}--}}
            {{--<input type="hidden" name="pos" value="1">--}}
            {{--<div class="col-md-12 col-md-offset-1 col-sm-12 col-sm-offset-1 col-xs-12">--}}
            {{--<span class="input-group-btn">--}}
            {{--<button class="btn btn-info" type="submit">Buscar O.S. pós fechamento</button>--}}
            {{--</span>--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}
            {{--</div>--}}
            {{--</div>--}}
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
                                <th>Situação</th>
                                <th>Tipo de Pagamento</th>
                                <th>Data de criação</th>
                                <th>Quantidade O.S.</th>
                                <th>CNPJ</th>
                                <th>Cliente</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Buscas as $selecao)
                                <?php $tipo_cliente = $selecao->cliente->getType(); ?>
                                <tr>
                                    <td>{{$selecao->id}}</td>
                                    <td>
                                        <button class="btn btn-xs btn-{{$selecao->getStatusType()}}">
                                            {{$selecao->getStatusText()}}
                                        </button>
                                    </td>
                                    <td>{{$selecao->cliente->forma_pagamento_tecnica->descricao}}</td>
                                    <td>{{$selecao->getCreatedAtMonth()}}</td>
                                    <td>{{$selecao->ordem_servicos->count()}}</td>
                                    <td>{{$tipo_cliente->entidade}}</td>
                                    <th><a href="{{route('clientes.show',$selecao->idcliente)}}"
                                        >{{$tipo_cliente->nome_principal}}</a>
                                    </th>
                                    <td>
                                        <a class="btn btn-default btn-xs"
                                           href="{{route($Page->link.'.show',$selecao->id)}}"
                                        ><i class="fa fa-edit"></i> Visualizar Fechamento</a>
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
                    "pageLength": 10,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection