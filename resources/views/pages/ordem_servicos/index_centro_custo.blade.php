@extends('layouts.template')
@section('modals_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
@endsection
@section('page_content')
    {{--@include('admin.layouts.alerts.remove')--}}
    <!-- Seach form -->
    {{--@include('layouts.search.form')--}}
    <!-- /Seach form -->
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
                                        <?php
                                        $path = explode('/', Request::path());
                                        $link = $path[2];
                                        ?>
                                        <a class="btn btn-default btn-xs"
                                           href="{{route($Page->link.'.show_centro_custo',[ $link, $cliente->idcliente])}}">
                                            <i class="fa fa-edit"></i> Visualizar O.S.</a>
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