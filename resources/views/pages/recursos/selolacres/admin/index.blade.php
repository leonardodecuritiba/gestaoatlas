@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    <section class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
            <div class="x_panel">
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
                                        <th>Técnico</th>
                                        <th>Nº</th>
                                        <th>Nº Externo</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Page->extras['selos'] as $sel)
                                        <tr>
                                            <td>{{$sel->idselo}}</td>
                                            <td>{{$sel->created_at}}</td>
                                            <td>{{$sel->getNomeTecnico()}}</td>
                                            <td>{{$sel->getFormatedSeloDV()}}</td>
                                            <td>{{$sel->numeracao_externa}}</td>
                                            <td>
                                                <span class="btn btn-xs btn-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
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
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
            <div class="x_panel">
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
                                        <th>Técnico</th>
                                        <th>Nº</th>
                                        <th>Nº Externo</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Page->extras['lacres'] as $sel)
                                        <tr>
                                            <td>{{$sel->idlacre}}</td>
                                            <td>{{$sel->created_at}}</td>
                                            <td>{{$sel->getNomeTecnico()}}</td>
                                            <td>{{$sel->numeracao}}</td>
                                            <td>{{$sel->numeracao_externa}}</td>
                                            <td>
                                                <span class="btn btn-xs btn-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
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
        </div>
    </section>
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
                        "targets": 0,
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

