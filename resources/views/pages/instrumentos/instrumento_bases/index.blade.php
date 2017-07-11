@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
@endsection
@section('page_content')
    <!-- Seach form -->
    @include('layouts.search.form')
    <!-- Upmenu form -->
    <?php $route_importacao = "#";  ?>
    <?php $route_exportacao = "#"; ?>
    @include('layouts.menus.upmenu-reduzido')

    @if(count($Buscas) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->Results}}</h2>
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
                                <th>Imagem</th>
                                <th>Data de Cadastro</th>
                                <th>Marca/Modelo</th>
                                <th>Descrição</th>
                                <th>Divisão</th>
                                <th>Portaria</th>
                                <th>Capacidade</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Buscas as $selecao)
                                <tr>
                                    <td>{{$selecao->id}}</td>
                                    <td><img src="{{$selecao->getThumbFoto()}}" class="avatar" alt="Avatar"></td>
                                    <td>{{$selecao->created_at}}</td>
                                    <td>{{$selecao->modelo->getMarcaModelo()}}</td>
                                    <td>{{$selecao->descricao}}</td>
                                    <td>{{$selecao->divisao}}</td>
                                    <td>{{$selecao->portaria}}</td>
                                    <td>{{$selecao->capacidade}}</td>
                                    <td>
                                        <a class="btn btn-default btn-xs"
                                           href="{{route($Page->link.'.show',$selecao->id)}}">
                                            <i class="fa fa-edit"></i> Visualizar / Editar</a>
                                        <a class="btn btn-danger btn-xs"
                                           data-nome="{{$Page->Target}}: {{$selecao->descricao}}"
                                           data-href="{{route($Page->link.'.destroy',$selecao->id)}}"
                                           data-toggle="modal"
                                           data-target="#modalRemocao">
                                            <i class="fa fa-trash-o"></i> Excluir </a>
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
                    "pageLength": 20,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection