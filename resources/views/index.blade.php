@extends('layouts.template')
@section('page_content')
    @role('admin')
    <div id="search" class="x_panel animated flipInX">
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
                {!! Form::open(array('route'=>'busca.index','method'=>'GET','id'=>'search','class'=>'form-horizontal form-label-left')) !!}
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Inicial:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{Request::get('data_inicial')}}"
                           type="text" class="form-control data-to-now" name="data_inicial" placeholder="Data" required>
                </div>
                <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Final:</label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <input value="{{Request::get('data_final')}}"
                           type="text" class="form-control data-to-now" name="data_final" placeholder="Data" required>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="submit">Buscar</button>
                    </span>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @if(count($Resultados) >0)
        <div class="x_panel">
            <div class="x_title">
                <h2>Ordens de Serviços Correntes de: <i>{{Request::get('data_inicial')}}</i> à
                    <i>{{Request::get('data_final')}}</i></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table border="0" class="table table-hover">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Pendentes (Passadas)</th>
                                <th>Finalizadas</th>
                                <th>Faturadas</th>
                                <th>Abertas</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Resultados as $selecao)
                                <tr>
                                    <td>{{$selecao->colaborador->nome}}</td>
                                    <td>{{$selecao->pendentes}}
                                        <a class="btn btn-default btn-xs"
                                           target="_blank"
                                           href="{{route('ordem_servicos.por_colaborador',[$selecao->colaborador->idcolaborador,'pendentes'])}}">
                                            <i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>{{$selecao->finalizadas}}
                                        <a class="btn btn-default btn-xs"
                                           target="_blank"
                                           href="{{route('ordem_servicos.por_colaborador',[$selecao->colaborador->idcolaborador,'finalizadas'])}}">
                                            <i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>{{$selecao->faturadas}}
                                        <a class="btn btn-default btn-xs"
                                           target="_blank"
                                           href="{{route('ordem_servicos.por_colaborador',[$selecao->colaborador->idcolaborador,'faturadas'])}}">
                                            <i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>{{$selecao->abertas}}
                                        <a class="btn btn-default btn-xs"
                                           target="_blank"
                                           href="{{route('ordem_servicos.por_colaborador',[$selecao->colaborador->idcolaborador,'abertas'])}}">
                                            <i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>{{$selecao->total}}</td>
                                </tr>
                            @endforeach
                            <tr class="green">
                                <th>SCORE</th>
                                <th>{{$Score->pendentes}}</th>
                                <th>{{$Score->finalizadas}}</th>
                                <th>{{$Score->faturadas}}</th>
                                <th>{{$Score->abertas}}</th>
                                <th>{{$Score->total}}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @endrole
@endsection