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
                    {!! Form::open(array('route'=>['fechamentos.index_pos'],
                        'method'=>'GET','id'=>'porid',
                        'class' => 'form-horizontal form-label-left')) !!}
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Listar por:</label>
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
                <h3><b>{{count($Buscas)}}</b> {{$Page->search_results}}</h3>
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
                            @foreach($Buscas as $ordem_servico)
                                <?php $clientType = (Request::get('centro_custo') == 0) ? $ordem_servico->cliente->getType() : $ordem_servico->centro_custo->getType(); ?>
                                <tr>
                                    <td><a target="_blank"
                                           href="{{route('clientes.show', $clientType->idcliente)}}"><b>{{$clientType->nome_principal}}</b></a>
                                    </td>
                                    <td>{{$clientType->razao_social}}</td>
                                    <td>{{$clientType->entidade}}</td>
                                    <td>{{$ordem_servico->qtd_os}}</td>
                                    <td>
                                        <a href="{{route('faturamentos.show_pos', [(Request::get('centro_custo')) ? 1:0,$clientType->idcliente])}}"
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