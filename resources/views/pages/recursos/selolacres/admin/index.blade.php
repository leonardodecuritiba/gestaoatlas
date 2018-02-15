@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    <section id="search" class="x_panel animated flipInX">
        <div class="x_content">
            {!! Form::open(array('route'=>'selolacres.listagem',
                'method'=>'GET','id'=>'search',
                'class' => 'form-horizontal form-label-left')) !!}
            <div class="row">

                {!! Html::decode(Form::label('tipo', 'TIPO',
                    array('class' => 'control-label col-md-1 col-sm-1 col-xs-12'))) !!}
                <div class="col-md-2 col-sm-2 col-xs-12">
                    {{Form::select('tipo', ['Selo', 'Lacre'], old('tipo'), ['class'=>'form-control select2_single', 'required'])}}
                </div>

                <label class="control-label col-md-1 col-sm-1 col-xs-12">NUMERAÇÃO:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{Request::get('numeracao')}}" type="text" class="form-control"
                           name="numeracao" placeholder="NUMERAÇÃO">
                </div>

                {!! Html::decode(Form::label('origem', 'ORIGEM',
                    array('class' => 'control-label col-md-1 col-sm-1 col-xs-12'))) !!}
                <div class="col-md-2 col-sm-2 col-xs-12">
                    {{Form::select('origem', $Page->extras['tecnicos'], old('origem'), ['class'=>'form-control select2_single', 'required'])}}
                </div>


                {!! Html::decode(Form::label('status', 'STATUS',
                    array('class' => 'control-label col-md-1 col-sm-1 col-xs-12'))) !!}
                <div class="col-md-2 col-sm-2 col-xs-12">
                    {{Form::select('status', $Page->extras['status'], old('status'), ['class'=>'form-control select2_single', 'required'])}}
                </div>
            </div>
            <div class="ln_solid"></div>
            <div class="row">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">CNPJ:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input value="{{Request::get('cnpj')}}" type="text" class="form-control"
                           name="cnpj" placeholder="CNPJ">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12">ID:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input value="{{Request::get('idordem_servico')}}" type="text" class="form-control"
                           name="idordem_servico" placeholder="ID da Ordem de Serviço">
                </div>
            </div>
            <div class="ln_solid"></div>
            <div class="row">
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Nº SÉRIE:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input value="{{Request::get('numero_serie')}}" type="text" class="form-control"
                           name="numero_serie" placeholder="Nº SÉRIE">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nº INVENTÁRIO:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input value="{{Request::get('inventario')}}" type="text" class="form-control"
                           name="inventario" placeholder="Nº INVENTÁRIO">
                </div>
            </div>
            <div class="ln_solid"></div>
            <div class="row">
                    <span class=" pull-right">
                        <button class="btn btn-info" type="submit">Filtrar</button>
                    </span>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
    @if(Request::has('tipo'))
        <section class="row">
            @if(Request::get('tipo') == 0)
                <div class="x_panel animated fadeInDown">
                    @if(count($Page->extras['selos']) > 0)
                        <div class="x_title">
                            <h2><b>{{$Page->extras['selos']->count()}}</b> Selos encontrados</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Origem</th>
                                            <th>Nº</th>
                                            <th>Nº Externo</th>
                                            <th>Cliente</th>
                                            <th>Fixado</th>
                                            <th>Retirado</th>
                                            <th>Nº Série</th>
                                            <th>Nº Inventário</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($Page->extras['selos'] as $sel)
                                            <tr>
                                                <td>{{$sel->idselo}}</td>
                                                <td data-order="{{$sel->created_at}}">{{$sel->created_at_formatted}}</td>
                                                <td>{{$sel->nome_tecnico}}</td>
                                                <td>{{$sel->numero_formatado}}</td>
                                                <td>{{$sel->numeracao_externa}}</td>
                                                <td>{{$sel->cliente_documento}}</td>
                                                <td>
                                                    @if($sel->idos_set!=NULL)
                                                        <a class="btn btn-default btn-xs" target="_blank"
                                                           href="{{route('ordem_servicos.show',$sel->idos_set)}}">
                                                            <i class="fa fa-eye"></i> {{$sel->idos_set}}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sel->idos_unset!=NULL)
                                                        <a class="btn btn-default btn-xs" target="_blank"
                                                           href="{{route('ordem_servicos.show',$sel->idos_unset)}}">
                                                            <i class="fa fa-eye"></i> {{$sel->idos_unset}}</a>
                                                    @endif
                                                </td>
                                                <td>{{$sel->n_serie}}</td>
                                                <td>{{$sel->n_inventario}}</td>
                                                <td>
                                                    <span class="label label-{{$sel->status_color}}">{{$sel->status_text}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Origem</th>
                                            <th>Nº</th>
                                            <th>Nº Externo</th>
                                            <th>Cliente</th>
                                            <th>Fixado</th>
                                            <th>Retirado</th>
                                            <th>Nº Série</th>
                                            <th>Nº Inventário</th>
                                            <th>Status</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="x_content">
                            <div class="row jumbotron">
                                <h1>Ops!</h1>
                                <h2>Nenhum Selo Encontrado</h2>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if(Request::get('tipo') == 1)
                <div class="x_panel animated fadeInDown">
                    @if(count($Page->extras['lacres']) > 0)
                        <div class="x_title">
                            <h2><b>{{$Page->extras['lacres']->count()}}</b> Lacres encontrados</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Origem</th>
                                            <th>Nº</th>
                                            <th>Nº Externo</th>
                                            <th>Cliente</th>
                                            <th>Fixado</th>
                                            <th>Retirado</th>
                                            <th>Nº Série</th>
                                            <th>Nº Inventário</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($Page->extras['lacres'] as $sel)
                                            <tr>
                                                <td>{{$sel->idlacre}}</td>
                                                <td>{{$sel->created_at}}</td>
                                                <td>{{$sel->nome_tecnico}}</td>
                                                <td>{{$sel->numero_formatado}}</td>
                                                <td>{{$sel->numeracao_externa}}</td>
                                                <td>{{$sel->cliente_documento}}</td>
                                                <td>
                                                    @if($sel->idos_set!=NULL)
                                                        <a class="btn btn-default btn-xs" target="_blank"
                                                           href="{{route('ordem_servicos.show',$sel->idos_set)}}">
                                                            <i class="fa fa-eye"></i> {{$sel->idos_set}}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sel->idos_unset!=NULL)
                                                        <a class="btn btn-default btn-xs" target="_blank"
                                                           href="{{route('ordem_servicos.show',$sel->idos_unset)}}">
                                                            <i class="fa fa-eye"></i> {{$sel->idos_unset}}</a>
                                                    @endif
                                                </td>
                                                <td>{{$sel->n_serie}}</td>
                                                <td>{{$sel->n_inventario}}</td>
                                                <td>
                                                    <span class="label label-{{$sel->status_color}}">{{$sel->status_text}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Origem</th>
                                            <th>Nº</th>
                                            <th>Nº Externo</th>
                                            <th>Cliente</th>
                                            <th>Fixado</th>
                                            <th>Retirado</th>
                                            <th>Nº Série</th>
                                            <th>Nº Inventário</th>
                                            <th>Status</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="x_content">
                            <div class="row jumbotron">
                                <h1>Ops!</h1>
                                <h2>Nenhum Lacre Encontrado</h2>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </section>
    @endif
    <!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 10,
                    "columnDefs": [{
                        "targets": 2,
                        "orderable": false
                    }],
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
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

