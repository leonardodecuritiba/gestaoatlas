@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
@endsection
@section('modals_content')
    @include('pages.recursos.selolacres.modal.requerer')
@endsection
@section('page_content')
    <!-- Seach form -->
    <section class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
            <div class="x_panel">
                <div class="row">
                    @if($Page->extras['selos']->count() > 0)
                        <div class="x_title">
                            <h2><b>{{$Page->extras['selos']->count()}}</b> Selos encontrados
                                ({{$Page->extras['selos']->where('used',0)->count()}} Disponíveis)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                    <table class="table table-striped table-bordered dt-responsive nowrap"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Numeração</th>
                                            <th>Numeração Externa</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Page->extras['selos'] as $sel)
                                            <tr>
                                                <td>{{$sel->idselo }}</td>
                                                <td>{{$sel->numeracao}}</td>
                                                <td>{{$sel->numeracao_externa}}</td>
                                                <td>
                                                    <button class="label label-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</button>
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
                <div class="row">
                    <div class="form-group">
                        <button class="btn btn-primary pull-right"
                                data-option="selos"
                                data-toggle="modal"
                                data-target="#modalRequerer"
                                @if(!$Page->extras['can_request_selos'])
                                disabled
                                @endif
                        ><i class="fa fa-plus fa-2"></i> Requerer
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
            <div class="x_panel">
                <div class="row">
                    @if($Page->extras['lacres']->count() > 0)
                        <div class="x_title">
                            <h2><b>{{$Page->extras['lacres']->count()}}</b>
                                Lacres encontrados ({{$Page->extras['lacres']->where('used',0)->count()}} Disponíveis)
                            </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                    <table class="table table-striped table-bordered dt-responsive nowrap"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Numeração</th>
                                            <th>Numeração Externa</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Page->extras['lacres'] as $sel)
                                            <tr>
                                                <td>{{$sel->idlacre }}</td>
                                                <td>{{$sel->numeracao}}</td>
                                                <td>{{$sel->numeracao_externa}}</td>
                                                <td>
                                                    <button class="label label-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</button>
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
                <div class="row">
                    <div class="form-group">
                        <button class="btn btn-primary pull-right"
                                data-option="lacres"
                                data-toggle="modal"
                                data-target="#modalRequerer"
                                @if(!$Page->extras['can_request_lacres'])
                                disabled
                                @endif
                        ><i class="fa fa-plus fa-2"></i> Requerer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row">
        <div class="x_panel">
            @if($Page->extras['requisicoes']->count() > 0)
                <div class="x_title">
                    <h2><b>{{$Page->extras['requisicoes']->count()}}</b> Requisições de Selos/Lacres encontrados </h2>
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
                                    <th>Data</th>
                                    <th>Tipo Requisição</th>
                                    <th>Requisição</th>
                                    <th>Razão</th>
                                    <th>Gestor</th>
                                    <th>Retorno</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Page->extras['requisicoes'] as $sel)
                                    <tr>
                                        <td>{{$sel->id}}</td>
                                        <td>{{$sel->created_at}}</td>
                                        <td>{{$sel->getTypeText()}}</td>
                                        <td>{{$sel->getParametersText()}}</td>
                                        <td>{{$sel->reason}}</td>
                                        <td>{{$sel->getNameManager()}}</td>
                                        <td>{{$sel->getResponseText()}}</td>
                                        <td>
                                            <span class="label label-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
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
                        <h2>Nenhuma Requisição de Selos/Lacres Encontrada</h2>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->

    {!! Html::script('js/parsley/parsley.min.js') !!}
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 5,
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
    <script>
        var MAX = [];
        MAX['selos'] = parseInt('{{$Page->extras['max_selos_can_request']}}');
        MAX['lacres'] = parseInt('{{$Page->extras['max_lacres_can_request']}}');

        $(document).ready(function () {
            $('div#modalRequerer').on('show.bs.modal', function (e) {
                var option_ = $(e.relatedTarget).data('option');
                var $content = $(this).find('div.modal-content');
                $($content).find('div.modal-header h4.modal-title b').html(option_);
                $($content).find('div.modal-body input[name=opcao]').val(option_);
                var $field = $($content).find('div.modal-body input[name=quantidade]');
                $($field).parents('form').parsley().destroy();
                $($field).attr({
                    "min": 1, // values (or variables) here,
                    "max": MAX[option_],              // substitute your own
                });
                $($field).parents('form').parsley();
            });
        });
    </script>
@endsection

