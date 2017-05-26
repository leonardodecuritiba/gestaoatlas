@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Filtrar por Período</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {!! Form::open(array('route'=>'recebimentos.index','method'=>'GET','id'=>'search','class'=>'form-horizontal form-label-left')) !!}
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
                        <div class="col-md-3 col-sm-3 col-xs-12">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Filtrar</button>
                    </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <section class="row">
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
                                        <th>#</th>
                                        <th>Situação</th>
                                        <th>Cliente</th>
                                        <th>Número da Parcala</th>
                                        <th>Forma de Pagamento</th>
                                        <th>Data de Vencimento</th>
                                        <th>Data de Pagamento</th>
                                        <th>Data de Baixa</th>
                                        <th>Valor</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($Buscas as $selecao)
                                        <?php $clientType = $selecao->cliente->getType();/* : $ordem_servico->centro_custo->getType(); */?>
                                        <tr>
                                            <td>{{$selecao->id}}</td>
                                            <td>
                                                <button class="btn btn-xs btn-{{$selecao->getStatusColor()}}">
                                                    {{$selecao->getStatusText()}}
                                                </button>
                                            </td>
                                            <td><a target="_blank"
                                                   href="{{route('clientes.show', $clientType->idcliente)}}"><b>{{$clientType->nome_principal}}</b></a>
                                            </td>
                                            <td>{{$selecao->getNumeroParcela()}}</td>
                                            <td>{{$selecao->forma_pagamento->descricao}}</td>
                                            <td>{{$selecao->data_vencimento}}</td>
                                            <td>{{$selecao->data_pagamento}}</td>
                                            <td>{{$selecao->data_baixa}}</td>
                                            <td>{{$selecao->valor_parcela_real()}}</td>
                                            <td>
                                                @if($selecao->status == 0)
                                                    <a data-toggle="modal"
                                                       data-parcela="{{$selecao}}"
                                                       data-valor_real="{{$selecao->valor_parcela_real()}}"
                                                       data-target="#modalPagarParcela"
                                                       class="btn btn-info btn-xs"><i class="fa fa-money"></i>
                                                        Receber</a>
                                                @else
                                                    <a class="btn btn-danger btn-xs"><i class="fa fa-refresh"></i>
                                                        Estornar</a>
                                                @endif
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
                            "order": [4, "asc"]
                        }
                    );
                });
            </script>
            <!-- /Datatables -->
@endsection