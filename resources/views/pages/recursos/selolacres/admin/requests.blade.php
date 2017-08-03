@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    <div class="row esconda">
        <div class="x_panel" id="formRequest">
            <div class="x_title">
                <h2>Confirmar requisição de Selos</h2>
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
                                    <li><i class="fa fa-info"></i> Status: <b id="status">XX</b></li>
                                    <li><i class="fa fa-info"></i> Gestor: <b id="manager">XX</b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
                    {!! Form::open(['route' => ['selolacres.repasse'],
                'id'    => 'form-selolacre',
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Origem <span class="required">*</span></label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <select class="select2_single form-control" name="idorigem" tabindex="-1">
                                @foreach($Page->extras['tecnicos'] as $tecnico)
                                    <option value="{{$tecnico->idtecnico}}">{{$tecnico->colaborador->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Valores: <span
                                    class="required">*</span></label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <select name="valores[]" class="select2_multiple-ajax form-control" multiple tabindex="-1"
                                    placeholder="Selo afixados" required
                                    data-parsley-errors-container="#select-errors"></select>
                            <div id="select-errors"></div>
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
    <div class="row">
        @if(count($Buscas) > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h2><b>{{$Buscas->count()}}</b> Requisições encontradas</h2>
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
                                    <th>Tipo Requisição</th>
                                    <th>Detalhamento</th>
                                    <th>Status</th>
                                    <th>Gestor</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($Buscas as $sel)
                                    <tr>
                                        <td>{{$sel->id}}</td>
                                        <td>{{$sel->created_at}}</td>
                                        <td>{{$sel->getNameRequester()}}</td>
                                        <td>{{$sel->getParametersText()}}</td>
                                        <td>{{$sel->getTypeText()}}</td>
                                        <td>
                                            <span class="btn btn-xs btn-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
                                        </td>
                                        <td>{{$sel->getNameManager()}}</td>
                                        <td>
                                            @if($sel->isWaiting())
                                                <a href="{{route('selolacres.negar',$sel->id)}}"
                                                   class="btn btn-xs btn-danger">Negar</a>
                                                <button data-request="{{$sel->getFormatedRequest()}}"
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
        var $_FORM_REQUEST_ = "div#formRequest";
        var remoteDataConfigSelos = {
            width: 'resolve',
            ajax: {
                url: "{{url('getSelosDisponiveis')}}",
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
            minimumInputLength: 3,
//            allowClear: true,
            language: "pt-BR"
//                templateResult: formatState
        };
        $(document).ready(function () {
            $(".btn-confirm").click(function () {
                var $data = $(this).data('request');
                $($_FORM_REQUEST_).parents('div.row').show();
                var $list = $($_FORM_REQUEST_).find('div.x_content div.profile_details ul.list-unstyled');
                $($_FORM_REQUEST_).find('div.x_content div.profile_details div.perfil h4 b').html($data.requester);
                $($_FORM_REQUEST_).find('div.x_content form input[name=id]').val($data.id);
                $.each($data, function (i, v) {
                    $($list).find('b#' + i).html(v);
                });
                remoteDataConfigSelos.maximumSelectionLength = JSON.parse($data.parameters_json).quantidade;
                $(".select2_multiple-ajax").select2(remoteDataConfigSelos);
            });
            $(".btn-cancel").click(function () {
                $($_FORM_REQUEST_).parents('div.row').hide();
                $(".select2_multiple-ajax").val(null).trigger("change");
            });
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

