@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
@endsection
@section('page_content')
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
                                <th>Quantidade</th>
                                <th>Tipo de Faturamento</th>
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
                                    <td>{{$selecao->getTipoFechamento()}}</td>
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
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "columnDefs": [
                        {targets: [0, 1, 2, 3, 4, 5], visible: true}
                    ],
                    "pageLength": 10,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection