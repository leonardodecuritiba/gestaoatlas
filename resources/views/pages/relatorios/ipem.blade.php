@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('page_content')
    <!-- Seach form -->
    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Filtro Selos IPEM</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {!! Form::open(array('route'=>$Page->link.'.ipem','method'=>'GET','class'=>'form-horizontal form-label-left')) !!}
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Data: <span
                                    class="required">*</span></label>
                        <div class="col-md-3 col-sm-5 col-xs-12">
                            <input name="data_inicial" type="text" maxlength="50" class="form-control data-to-now"
                                   placeholder="Data Inicial"
                                   value="{{Request::get('data_inicial')}}">
                        </div>
                        <div class="col-md-3 col-sm-5 col-xs-12">
                            <input name="data_final" type="text" maxlength="50" class="form-control data-to-now"
                                   placeholder="Data Final"
                                   value="{{Request::get('data_final')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Numeração: <span
                                    class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <input name="numeracao_inicial" type="text" maxlength="50" class="form-control"
                                   placeholder="Inicial"
                                   value="{{Request::get('numeracao_inicial')}}">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <input name="numeracao_final" type="text" maxlength="50" class="form-control"
                                   placeholder="Final"
                                   value="{{Request::get('numeracao_final')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Técnico: <span
                                    class="required">*</span></label>
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <select class="select2_single form-control" name="idtecnico" tabindex="-1">
                                <option value="0">Todos</option>
                                @foreach($Page->extras['tecnicos'] as $tecnico)
                                    <option value="{{$tecnico->idtecnico}}"
                                            @if(Request::get('idtecnico') == $tecnico->idtecnico) selected @endif
                                    >{{$tecnico->colaborador->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <button class="btn btn-info btn-block" type="submit">Buscar</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
    @if(count($Buscas) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2><b>{{$Buscas->count()}}</b> {{$Page->Targets}} encontrados</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <form action="{{route('relatorios.ipem.print')}}" target="_blank">
                            <input type="hidden" name="data_inicial" value="{{Request::get('data_inicial')}}">
                            <input type="hidden" name="data_final" value="{{Request::get('data_final')}}">
                            <input type="hidden" name="numeracao_inicial" value="{{Request::get('numeracao_inicial')}}">
                            <input type="hidden" name="numeracao_final" value="{{Request::get('numeracao_final')}}">
                            <input type="hidden" name="idtecnico" value="{{Request::get('idtecnico')}}">
                            <button class="btn btn-success"><i class="fa fa-print fa-2"></i> Exportar</button>
                        </form>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Razão Social</th>
                                <th>Nome Fantasia</th>
                                <th>CNPJ / CPF</th>
                                <th>Nº O.S.</th>
                                <th>Nº do Inventario</th>
                                <th>Nº de Série</th>
                                <th>Marca de reparo</th>
                                <th>Data do Reparo</th>
                                <th>Técnico</th>
                                <th>Descrição O.S.</th>
                                <th>Carga</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Razão Social</th>
                                <th>Nome Fantasia</th>
                                <th>CNPJ / CPF</th>
                                <th>Nº O.S.</th>
                                <th>Nº do Inventario</th>
                                <th>Nº de Série</th>
                                <th>Marca de reparo</th>
                                <th>Data do Reparo</th>
                                <th>Técnico</th>
                                <th>Descrição O.S.</th>
                                <th>Carga</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach ($Buscas as $sel)
                                <tr>
                                    <td>{{$sel->cliente->razao_social}}</td>
                                    <td><b><a href="{{route('clientes.show', $sel->ordem_servico->idcliente)}}"
                                              target="_blank">{{$sel->cliente->nome_principal}}</a></b></td>
                                    <td>{{$sel->cliente->documento}}</td>
                                    <td><b><a href="{{route('ordem_servicos.show', $sel->ordem_servico->idordem_servico)}}"
                                              target="_blank">{{$sel->ordem_servico->idordem_servico}}</a></b></td>
                                    <td>{{$sel->instrumento->inventario}}</td>
                                    <td>{{$sel->instrumento->numero_serie}}</td>
                                    <td>{!! (($sel->selo_numeracao!=NULL) ? $sel->selo_numeracao : '<i class="red">sem reparo</i>') !!}</td>
                                    <td>{{$sel->ordem_servico->created_at_formatted}}</td>
                                    <td>
                                        <b><a href="{{route('colaboradores.show', $sel->colaborador->idcolaborador)}}"
                                              target="_blank">{{$sel->colaborador->nome.' - '.$sel->colaborador->rg}}</a>
                                        </b></td>
                                    <td>
                                        <span class="red">{{$sel->defeito}}</span> /
                                        <span class="green">{{$sel->solucao}}</span>
                                    </td>
                                    <td>{{$sel->instrumento->capacidade}}</td>
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
    <script type="text/javascript">
        function declare(btn){
            var id_ = $(btn).data('id');
            var $el = $(btn);
            console.log(id_);
            $.ajax({
                url: '{{route('relatorios.ipem.declarar')}}',
                type: 'get',
                data: {"id": id_, "declared":0},
//                    dataType: "json",

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
                /**/
                success: function (json) {
                    console.log(json);
                    if (json.status) {
                        console.log(json.response);
                        $($el).parent().html('<button class="btn btn-success btn-xs"><i class="fa fa-check" aria-hidden="true"></i></button>');
                    } else {
                        alert(json);
                    }
                }
            });
        }
    </script>
@endsection

