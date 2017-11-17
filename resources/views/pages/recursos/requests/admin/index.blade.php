@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    <div class="row esconda">
        <div class="x_panel" id="formRequestNegar">
            <div class="x_title">
                <h2>Negar requisição</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
                    <div class="profile_details">
                        <div class="well">
                            <div class="perfil">
                                <h4>Requerente: <b>XX</b>
                                </h4>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-info"></i> ID: <b id="id">XX</b></li>
                                    <li><i class="fa fa-info"></i> Data da Requisição: <b id="date">XX</b></li>
                                    <li><i class="fa fa-info"></i> Tipo de Requisição: <b id="type">XX</b></li>
                                    <li><i class="fa fa-info"></i> Detalhamento: <b id="parameters">XX</b></li>
                                    <li><i class="fa fa-info"></i> Razão: <b id="reason">XX</b></li>
                                    <li><i class="fa fa-info"></i> Status: <b id="status">XX</b></li>
                                    <li><i class="fa fa-info"></i> Gestor: <b id="manager">XX</b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
                    {!! Form::open(['route' => [$Page->extras['type'] . '.deny'],
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Motivo: <span
                                    class="required">*</span></label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea name="response" rows="4" class="form-control"
                                      data-parsley-errors-container="#response-errors" required></textarea>
                            <div id="response-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-lg pull-right"><i class="fa fa-check fa-2"></i> Confirmar
                        </button>
                        <a class="btn btn-danger btn-lg pull-right btn-cancel"><i class="fa fa-times fa-2"></i> Cancelar</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row esconda">
        <div class="x_panel" id="formRequestConfirmar">
            <div class="x_title">
                <h2>Confirmar Requisição</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
                    <div class="profile_details">
                        <div class="well">
                            <div class="perfil">
                                <h4>Requerente: <b>XX</b>
                                </h4>
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-info"></i> ID: <b id="id">XX</b></li>
                                    <li><i class="fa fa-info"></i> Data da Requisição: <b id="date">XX</b></li>
                                    <li><i class="fa fa-info"></i> Tipo de Requisição: <b id="type">XX</b></li>
                                    <li><i class="fa fa-info"></i> Detalhamento: <b id="parameters">XX</b></li>
                                    <li><i class="fa fa-info"></i> Razão: <b id="reason">XX</b></li>
                                    <li><i class="fa fa-info"></i> Status: <b id="status">XX</b></li>
                                    <li><i class="fa fa-info"></i> Gestor: <b id="manager">XX</b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
                    @include('pages.recursos.requests.admin.forms.'.$Page->extras['type'])
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="results">
        @if(count($Page->extras['requests']) > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h2><b>{{$Page->extras['requests']->count()}}</b> Requisições encontradas</h2>
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
                                    <th>Requerente</th>
                                    <th>Tipo</th>
                                    <th>Detalhes</th>
                                    <th>Razão</th>
                                    <th>Retorno</th>
                                    <th>Gestor</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($Page->extras['requests'] as $sel)
                                    <tr>
                                        <td>{{$sel->id}}</td>
                                        <td>{{$sel->created_at}}</td>
                                        <td>{{$sel->getNameRequester()}}</td>
                                        <td>{{$sel->getTypeText()}}</td>
                                        <td>{{$sel->getParametersText()}}</td>
                                        <td>{{$sel->reason}}</td>
                                        <td>{{$sel->getResponseText()}}</td>
                                        <td>{{$sel->getNameManager()}}</td>
                                        <td>
                                            <span class="label label-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
                                        </td>
                                        <td>
                                            @if($sel->isWaiting())
                                                <button data-request="{{$sel->getFormatedRequest()}}"
                                                        class="btn btn-xs btn-danger btn-deny">Negar
                                                </button>
                                                <button data-request="{{$sel->getFormatedRequest()}}"
                                                        data-type="{{$sel->getTypeText()}}"
                                                        class="btn btn-xs btn-success btn-confirm">Confirmar
                                                </button>
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
    </div>
    <!-- /page content -->
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
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

    <!-- FORM -->
    <script type="text/javascript">
        var $_FORM_REQUEST_CONFIRMAR_ = "div#formRequestConfirmar";
        var $_FORM_REQUEST_NEGAR_ = "div#formRequestNegar";
        var $_RESULTS_ = "div#results";
        var URL_AJAX = [];
        URL_AJAX['SELOS'] = "{{route('getSelosDisponiveis')}}";
        URL_AJAX['LACRES'] = "{{route('getLacresDisponiveis')}}";
        var remoteDataConfigSelos = {
            width: 'resolve',
            ajax: {
                url: '',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term, // search term
                        idtecnico: $('select[name=idorigem]').val(), // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            maximumSelectionLength: 3,
            minimumInputLength: 1,
//            allowClear: true,
            language: "pt-BR"
//                templateResult: formatState
        };

        function setDataRequest($data, $DIV) {
            var $list = $($DIV).find('div.x_content div.profile_details ul.list-unstyled');
            $($DIV).find('div.x_content div.profile_details div.perfil h4 b').html($data.requester);
            $($DIV).find('div.x_content form input[name=id]').val($data.id);
            $.each($data, function (i, v) {
                $($list).find('b#' + i).html(v);
            });
        }

        $(document).ready(function () {
            $(".btn-confirm").click(function () {
                var $data = $(this).data('request');
                $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').show();
                $($_FORM_REQUEST_NEGAR_).parents('div.row').hide();
                $($_RESULTS_).hide();
                setDataRequest($data, $_FORM_REQUEST_CONFIRMAR_);
                remoteDataConfigSelos.ajax.url = URL_AJAX[$data.type];
                remoteDataConfigSelos.maximumSelectionLength = JSON.parse($data.parameters_json).quantidade;
                $(".select2_multiple-ajax").select2(remoteDataConfigSelos);
            });
        });
        $(".btn-deny").click(function () {
            var $data = $(this).data('request');
            $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').hide();
            $($_FORM_REQUEST_NEGAR_).parents('div.row').show();
            $($_RESULTS_).hide();
            setDataRequest($data, $_FORM_REQUEST_NEGAR_);
            $(".select2_multiple-ajax").val(null).trigger("change");
        });
        $(".btn-cancel").click(function () {
            $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').hide();
            $($_FORM_REQUEST_NEGAR_).parents('div.row').hide();
            $($_RESULTS_).show();
            $(".select2_multiple-ajax").val(null).trigger("change");
        });
        //seleção do selos
        $('select[name=idorigem]').on("select2:select", function () {
            //achar parent, pegar próximo td e escrever o valor
            var $sel = $(this).find(":selected");
            $(".select2_multiple-ajax").val(null).trigger("change");
        });
    </script>
    <!-- /SELOS -->

@endsection

