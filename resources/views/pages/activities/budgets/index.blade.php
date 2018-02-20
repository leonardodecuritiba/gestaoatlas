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
                <h2>Filtrar {{$Page->titulo_primario}}</h2><ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-default" onclick="window.location.replace('{{route('budgets.create')}}')">
                            <i class="fa fa-eye fa-2"></i> Novo Orçamento
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {!! Form::open(array('route'=>'budgets.index',
                            'method'=>'GET','id'=>'search',
                            'class' => 'form-horizontal form-label-left')) !!}


                            {{-- DATA DE ABERTURA --}}
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">Abertura:</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input value="{{Request::has('date')?Request::get('date'):\Carbon\Carbon::now()->format('d/m/Y')}}"
                                       type="text" class="form-control data-to-now" name="date" placeholder="Data" required>
                            </div>


                            {{-- TIPO --}}
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">Tipo:</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select name="situation" class="form-control select2_single">
                                    @foreach($Page->extras['situation'] as $key => $value)
                                        <option value="{{$key}}"
                                                @if(Request::has('situation') && Request::get('situation')==$key) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>



                            {{-- CLIENTE --}}
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">Por Cliente:</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select name="client_id" class="form-control select2_single">
                                    <option value="">Todos</option>
                                    @foreach($Page->extras['clients'] as $client)
                                        <option value="{{$client['id']}}"
                                                @if(Request::has('client_id') && Request::get('client_id')==$client['id']) selected @endif>
                                            {{$client['name']}}</option>
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
                        {!! Form::open(array('route'=>['budgets.index'],
                            'method'=>'GET','id'=>'byid',
                            'class' => 'form-horizontal form-label-left')) !!}
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">ID:</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input value="{{Request::get('id')}}" type="text" class="form-control"
                                       name="id" placeholder="ID do Orçamento">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                            <span class="input-group-btn">
                                <button class="btn btn-info" type="submit">Filtrar por ID</button>
                            </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="row">
        @if(count($Page->response) > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{$Page->response->count()}} orçamentos encontrados</h2>
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
                                    <th>Situação</th>
                                    <th>Data de Abertura</th>
                                    <th>Colaborador</th>
                                    <th>Cliente</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Situação</th>
                                    <th>Data de Abertura</th>
                                    <th>Colaborador</th>
                                    <th>Cliente</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($Page->response as $sel)
                                    <tr>
                                        <td>{{$sel['id']}}</td>
                                        <td><span class="label label-{{$sel['situation_color']}}">{{$sel['situation_text']}}</span></td>
                                        <td data-order="{{$sel['created_at_time']}}">{{$sel['created_at']}}</td>
                                        <td>{{$sel['collaborator']}}</td>
                                        <td>{{$sel['client']}}</td>
                                        <td>
                                            <a class="btn btn-default btn-xs"
                                               href="{{$sel['show_url']}}">
                                                <i class="fa fa-eye"></i> Visualizar</a>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
    <!-- /Select2 -->

@endsection