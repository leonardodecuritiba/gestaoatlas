@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
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
                            <input type="hidden" name="idtecnico" value="{{Request::get('idtecnico')}}">
                            <input type="hidden" name="data_inicial" value="{{Request::get('data_inicial')}}">
                            <input type="hidden" name="data_final" value="{{Request::get('data_final')}}">
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
                                <th>Documento</th>
                                <th>Nº O.S.</th>
                                <th>Data do Reparo</th>
                                <th>Técnico</th>
                                <th>Descrição O.S.</th>
                                <th>Nº de Série</th>
                                <th>Nº do Inventario</th>
                                <th>Marca de reparo</th>
                                <th>Carga</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Buscas as $Aparelho_manutencao)
                                <?php $Ordem_servico = $Aparelho_manutencao->ordem_servico; ?>
                                <?php $Cliente = $Ordem_servico->cliente->getType(); ?>
                                <?php $Instrumento = $Aparelho_manutencao->instrumento; ?>
                                <tr>
                                    <td>{{$Cliente->razao_social}}</td>
                                    <td><b><a href="{{route('clientes.show', $Ordem_servico->idcliente)}}"
                                              target="_blank">{{$Cliente->nome_principal}}</a></b></td>
                                    <td>{{$Cliente->documento}}</td>
                                    <td><b><a href="{{route('ordem_servicos.show', $Ordem_servico->idordem_servico)}}"
                                              target="_blank">{{$Ordem_servico->idordem_servico}}</a></b></td>
                                    <td>{{$Ordem_servico->created_at}}</td>
                                    <td>
                                        <b><a href="{{route('colaboradores.show', $Ordem_servico->colaborador->idcolaborador)}}"
                                              target="_blank">{{$Ordem_servico->colaborador->nome.' - '.$Ordem_servico->colaborador->rg}}
                                        </b></td>
                                    <td>
                                        <span class="red">{{$Aparelho_manutencao->defeito}}</span> /
                                        <span class="green">{{$Aparelho_manutencao->solucao}}</span>
                                    <td>{{$Instrumento->numero_serie}}</td>
                                    <td>{{$Instrumento->inventario}}</td>
                                    <td>{{$Instrumento->selo_afixado_numeracao()}}</td>
                                    <td>{{$Instrumento->capacidade}}</td>

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
@endsection

