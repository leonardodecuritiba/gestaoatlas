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
                <h2>Negar requisição de <b>Selos</b></h2>
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
                    {!! Form::open(['route' => ['selolacres.deny'],
                'id'    => 'form-selolacre',
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
                <h2>Confirmar requisição de <b>Selos</b></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
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
                        {!! Form::open(['route' => ['selolacres.repasse'],
                    'id'    => 'form-selolacre',
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Origem <span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="select2_single form-control" name="idorigem" tabindex="-1">
                                    @foreach($Page->extras['tecnicos'] as $tecnico)
                                        <option value="{{$tecnico->idtecnico}}">{{$tecnico->colaborador->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <span class="btn btn-info btn-block pull-right btn-busca">Buscar</span>
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
                            <a class="btn btn-danger btn-lg pull-right" onclick="_cancelRequest()"><i class="fa fa-times fa-2"></i> Cancelar</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row esconda">
        <div class="x_panel" id="resultsAjax">
            <div class="x_title">
                <h2><b>Selos</b> Disponíveis</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <div class="profile_details valores">
                    </div>
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
                                    <th>Status</th>
                                    <th>Requerente</th>
                                    <th>Tipo</th>
                                    <th>Detalhes</th>
                                    <th>Valores</th>
                                    <th>Razão</th>
                                    <th>Retorno</th>
                                    <th>Gestor</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Requerente</th>
                                    <th>Tipo</th>
                                    <th>Detalhes</th>
                                    <th>Valores</th>
                                    <th>Razão</th>
                                    <th>Retorno</th>
                                    <th>Gestor</th>
                                    <th>Ação</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach ($Page->extras['requests'] as $sel)
                                    <tr>
                                        <td>{{$sel->id}}</td>
                                        <td data-order="{{$sel->created_at}}">{{$sel->getCreatedAtFormatted()}}</td>
                                        <td>
                                            <span class="label label-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span>
                                        </td>
                                        <td>{{$sel->getNameRequester()}}</td>
                                        <td>{{$sel->getTypeText()}}</td>
                                        <td>{{$sel->getParametersText()}}</td>
                                        <td>
                                            @forelse($sel->getParametersValoresText() as $val)
                                                <span class="label label-info">{{$val}}</span>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td>{{$sel->reason}}</td>
                                        <td>{{$sel->getResponseText()}}</td>
                                        <td>{{$sel->getNameManager()}}</td>
                                        <td>
                                            @if($sel->isWaiting())
                                                <button data-request="{{$sel->getFormatedRequest()}}"
                                                        class="btn btn-xs btn-danger btn-deny"
                                                        onclick="_denyRequest(this)">Negar
                                                </button>
                                                <button data-request="{{$sel->getFormatedRequest()}}"
                                                        data-type="{{$sel->getTypeText()}}"
                                                        onclick="_confirmRequest(this)"
                                                        class="btn btn-xs btn-success">Confirmar
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
                    "order": [[ 0, 'desc'] ],
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
                }
            );
        });
    </script>
    <!-- /Datatables -->

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <!-- /Select2 -->

    <!-- FORM -->
    <script type="text/javascript">
        if (!Array.prototype.removeElements) {
            Array.prototype.removeElements = function(val, all) {
                var i, removedItems = [];
                if (all) {
                    for(i = this.length; i--;){
                        if (this[i] === val) removedItems.push(this.splice(i, 1));
                    }
                }
                else {  //same as before...
                    i = this.indexOf(val);
                    if(i>-1) removedItems = this.splice(i, 1);
                }
                return removedItems;
            };
        }
        var $_FORM_REQUEST_CONFIRMAR_ = "div#formRequestConfirmar";
        var $_FORM_REQUEST_NEGAR_ = "div#formRequestNegar";
        var $_SELECT2_MULTIPLE_ = "select[name='valores[]']";
        var $_RESULTS_AJAX_ = "div#resultsAjax";
        var $_RESULTS_ = "div#results";
        var URL_AJAX = [];
        URL_AJAX['SELOS'] = "{{route('getSelosDisponiveis')}}";
        URL_AJAX['LACRES'] = "{{route('getLacresDisponiveis')}}";
        var $DATA ={};
        var remoteDataConfigSelos = {
            width: 'resolve',
            ajax: {
                url: '',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term, // search term
                        idtecnico: $($_SELECT2_MULTIPLE_).val(), // search term
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

        function _confirmRequest($this){
            var $data = $($this).data('request');
            setDataRequest($data, $_FORM_REQUEST_CONFIRMAR_);
            $DATA.url = URL_AJAX[$data.type];
            $DATA.type = $data.type;
            $DATA.max = JSON.parse($data.parameters_json).quantidade;
//            remoteDataConfigSelos.ajax.url = URL_AJAX[$data.type];
            remoteDataConfigSelos.maximumSelectionLength = JSON.parse($data.parameters_json).quantidade;

            $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').show();
            $($_FORM_REQUEST_CONFIRMAR_).find('div.x_title h2 b').html($DATA.type);
            $($_FORM_REQUEST_NEGAR_).parents('div.row').hide();
            $($_FORM_REQUEST_NEGAR_).find('div.x_title h2 b').html($DATA.type);
            $($_RESULTS_AJAX_).parents('div.row').show();
            $($_RESULTS_AJAX_).find('div.x_title h2 b').html($DATA.type);
            $($_RESULTS_).hide();
            $(".select2_multiple-ajax").select2(remoteDataConfigSelos);
        }

        function _denyRequest($this){
            var $data = $($this).data('request');
            $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').hide();
            $($_FORM_REQUEST_NEGAR_).parents('div.row').show();
            $($_RESULTS_AJAX_).parents('div.row').hide();
            $($_RESULTS_).hide();
            setDataRequest($data, $_FORM_REQUEST_NEGAR_);
            $(".select2_multiple-ajax").val(null).trigger("change");
        }

        function _cancelRequest(){
            $($_FORM_REQUEST_CONFIRMAR_).parents('div.row').hide();
            $($_FORM_REQUEST_NEGAR_).parents('div.row').hide();
            $($_RESULTS_AJAX_).parents('div.row').hide();
            $($_RESULTS_).show();
            $(".select2_multiple-ajax").val(null).trigger("change");
        }

        function _removeOption(_data){
            if ($($_SELECT2_MULTIPLE_).find("option[value='" + _data.id + "']").length) {
                $($_SELECT2_MULTIPLE_).find(" option[value='" + _data.id + "']").remove().trigger('change');
                var values = $($_SELECT2_MULTIPLE_).val();
                if(values.length > 1){
                    values = values.sort();
                }
                $($_SELECT2_MULTIPLE_).val(values).trigger('change');
            }
        }

        function _addOption(_data){
            var newOption = new Option(_data.text, _data.id, false, false);
            $($_SELECT2_MULTIPLE_).append(newOption);
            var values = $($_SELECT2_MULTIPLE_).val();
            if(values == null) {
                values = _data.id;
            } else {
                values.push(_data.id);
                values = values.sort();
            }
            $($_SELECT2_MULTIPLE_).val(values).trigger('change');

            console.log(values);
        }

        function _unselectBtn($this){
            $($this).data('value',0);
            $($this).removeClass('btn-success');
            $($this).addClass('btn-default')
        }

        function _selectBtn($this){
            $($this).data('value',1);
            $($this).removeClass('btn-default');
            $($this).addClass('btn-success');
        }

        function _acceptBtn($this){
            var val = $($this).data('value');
            var _data = {};
            _data.id = $($this).data('id');
            _data.text = $($this).html();
            if(val == "1"){
                _unselectBtn($this);
                _removeOption(_data);
            } else {
                _selectBtn($this)
                _addOption(_data);
            }
        }
        $($_SELECT2_MULTIPLE_).on('select2:unselect', function (e) {
            var _data = {};
            _data.id = e.params.data.id;
            _data.text = e.params.data.text;
            var $this = $('div.valores').find('button[data-id=' + e.params.data.id + ']');
            _unselectBtn($this);
            _removeOption(_data);
        });
        $($_SELECT2_MULTIPLE_).on('select2:select', function (e) {
            var _data = {};
            _data.id = e.params.data.id;
            _data.text = e.params.data.text;
            var $this = $('div.valores').find('button[data-id=' + e.params.data.id + ']');
            _selectBtn($this);
            _addOption(_data);
        });

        //seleção do selos
        $('select[name=idorigem]').on("select2:select", function () {
            //achar parent, pegar próximo td e escrever o valor
            var $sel = $(this).find(":selected");
        });

        $(document).ready(function () {
            $(".btn-busca").click(function () {
                var origem = $('select[name=idorigem]').find(":selected");
                //fazer busca
                $('div.valores').empty();
                $.ajax({
                    url: $DATA.url,
                    type: 'get',
                    data: {"idtecnico": origem.val()},
                    dataType: "json",

                    beforeSend: function () {
                        $($_LOADING_).show();
                    },
                    complete: function (xhr, textStatus) {
                        $($_LOADING_).hide();
                    },
                    error: function (xhr, textStatus) {
                        console.log('xhr-error: ' + xhr);
                        console.log('textStatus-error: ' + textStatus);
                    },
                    success: function (json) {
                        console.log(json);
                        if (json!=null) {
                            var values = $($_SELECT2_MULTIPLE_).val();
                            $(json).each(function(i,v){
                                if($.inArray(v.id.toString(), values) > -1){
                                    $('div.valores').append('<button data-value="1" onclick="_acceptBtn(this)" data-id="' + v.id + '" class="btn btn-success">' + v.text + '</button>');
                                } else {
                                    $('div.valores').append('<button data-value="0" onclick="_acceptBtn(this)" data-id="' + v.id + '" class="btn btn-default">' + v.text + '</button>');
                                }
                             })
                        } else {
                            $('div.valores').append('<div class="row jumbotron"><h1>Ops!</h1><h2>' + $DATA.type +  ' não encontrados. Selecione outra Origem!</h2></div>');
                        }
                    }
                });

            });
        });
    </script>
    <!-- /SELOS -->

@endsection

