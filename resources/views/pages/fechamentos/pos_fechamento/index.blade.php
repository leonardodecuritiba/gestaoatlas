@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')

    <div class="row">
        <div id="search" class="x_panel animated flipInX">
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {!! Form::open(array('route'=>['fechamentos.index_pos'],
                            'method'=>'GET','id'=>'porid',
                            'class' => 'form-horizontal form-label-left')) !!}
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Listar:</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <select name="centro_custo" class="form-control select2_single">
                                <option value="0"
                                        @if(Request::has('centro_custo') || Request::get('centro_custo')==0) selected @endif>
                                    Clientes
                                </option>
                                <option value="1"
                                        @if(Request::has('centro_custo') && Request::get('centro_custo')==1) selected @endif>
                                    Centro de Custo
                                </option>
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Buscar</button>
						</span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @if(count($Fechamentos) > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h3><b>{{count($Fechamentos)}}</b> {{$Page->search_results}}</h3>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Fantasia</th>
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>Quantidade O.S</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Fechamentos as $ordem_servico)
                                    <?php $clientType = (Request::get('centro_custo') == 0) ? $ordem_servico->cliente->getType() : $ordem_servico->centro_custo->getType(); ?>
                                    <tr>
                                        <td><a target="_blank"
                                               href="{{route('clientes.show', $clientType->idcliente)}}"><b>{{$clientType->nome_principal}}</b></a>
                                        </td>
                                        <td>{{$clientType->razao_social}}</td>
                                        <td>{{$clientType->entidade}}</td>
                                        <td>{{$ordem_servico->qtd_os}}</td>
                                        <td>
                                            <a href="{{route('fechamentos.show_pos', [(Request::get('centro_custo')) ? 1:0,$clientType->idcliente])}}"
                                               class="btn btn-default btn-xs"><i class="fa fa-eye"></i>
                                                Visualizar
                                            </a>
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
    </div>
    @if(count($Faturamentos) > 0)
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h3><b>{{count($Faturamentos)}}</b> Faturamentos
                        de {{(Request::get('centro_custo') == 0)?'Clientes':'Centro de Custos'}} Abertos</h3>
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
                                @foreach($Faturamentos as $selecao)
                                    <?php $tipo_cliente = $selecao->cliente->getType(); ?>
                                    <tr>
                                        <td>{{$selecao->id}}</td>
                                        <td>
                                            <span class="label label-{{$selecao->getStatusType()}}">
                                                {{$selecao->getStatusText()}}
                                            </span>
                                        </td>
                                        <td>{{$selecao->cliente->forma_pagamento_tecnica->descricao}}</td>
                                        <td>{{$selecao->getCreatedAtMonth()}}</td>
                                        <td>{{$selecao->ordem_servicos->count()}}</td>
                                        <td>{{$tipo_cliente->entidade}}</td>
                                        <th><a href="{{route('clientes.show',$selecao->idcliente)}}"
                                            >{{$tipo_cliente->nome_principal}}</a>
                                        </th>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{route('faturamentos.show',$selecao->id)}}"
                                            ><i class="fa fa-edit"></i> Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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