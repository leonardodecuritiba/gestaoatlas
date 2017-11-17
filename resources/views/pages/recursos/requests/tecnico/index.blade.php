@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('modals_content')
    @include('pages.recursos.requests.modal.requerer')
@endsection
@section('page_content')
    <!-- Seach form -->
    <section class="row">
        @include('pages.recursos.requests.lists.' . $Page->extras['type'])
    </section>
    <section class="row">
        <div class="x_panel">
            @if(count($Page->extras['requisicoes']) > 0)
                <div class="x_title">
                    <h2><b>{{$Page->extras['requisicoes']->count()}}</b> Requisições de {{$Page->search_results}} </h2>
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
                        <h2>{{$Page->search_no_results}}</h2>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->

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
        MAX['{{$Page->extras['type']}}'] = parseInt('{{$Page->extras['max_can_request']}}');

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

        $(document).ready(function () {
            $('div#modalRequererParts').on('show.bs.modal', function (e) {
                var option_ = $(e.relatedTarget).data('option');
                var $content = $(this).find('div.modal-content');
                $($content).find('div.modal-header h4.modal-title b').html(option_);
                $($content).find('div.modal-body input[name=opcao]').val(option_);
            });
        });
    </script>
@endsection

