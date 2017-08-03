@extends('layouts.template')
@section('style_content')
    <!-- Select2 -->
    @include('helpers.select2.head')
    @include('helpers.datatables.head')
@endsection

@section('page_content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <section class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Lançar Selos</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        {!! Form::open(['route' => $Page->link.'.lancar_selos',
                            'method' => 'POST',
                            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                        <section class="row">
                            <div class="form-horizontal form-label-left">
                                @role(['admin'])
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-6 col-xs-12">Técnico: <span
                                                class="required">*</span></label>
                                    <div class="col-md-8 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" name="idtecnico" tabindex="-1"
                                                required>
                                            <option value="">Selecione</option>
                                            @foreach($Page->extras['tecnicos'] as $sel)
                                                <option value="{{$sel->idtecnico}}">{{$sel->getNome()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <input type="hidden" name="idtecnico"
                                           value="{{Auth::user()->colaborador->tecnico->idtecnico}}">
                                    @endrole
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-6 col-xs-12">Numeração Inicial:
                                            <span class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control show-inteiro" min="1"
                                                   name="numeracao_inicial" placeholder="Numeração Inicial" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-6 col-xs-12">Numeração Final: <span
                                                    class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control show-inteiro" min="2"
                                                   name="numeracao_final" placeholder="Numeração Inicial" required>
                                        </div>
                                    </div>
                            </div>
                        </section>
                        <section class="row">
                            <div class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <a href="{{route($Page->link.'.index')}}"
                                           class="btn btn-danger btn-lg btn-block">Cancelar</a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Lançar Lacres</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        {!! Form::open(['route' => $Page->link.'.lancar_lacres',
                            'method' => 'POST',
                            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                        <section class="row">
                            <div class="form-horizontal form-label-left">
                                @role(['admin'])
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-6 col-xs-12">Técnico: <span
                                                class="required">*</span></label>
                                    <div class="col-md-8 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" name="idtecnico" tabindex="-1"
                                                required>
                                            <option value="">Selecione</option>
                                            @foreach($Page->extras['tecnicos'] as $sel)
                                                <option value="{{$sel->idtecnico}}">{{$sel->getNome()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <input type="hidden" name="idtecnico"
                                           value="{{Auth::user()->colaborador->tecnico->idtecnico}}">
                                    @endrole
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-6 col-xs-12">Numeração Inicial:
                                            <span class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control show-inteiro" min="1"
                                                   name="numeracao_inicial" placeholder="Numeração Inicial" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-6 col-xs-12">Numeração Final: <span
                                                    class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control show-inteiro" min="2"
                                                   name="numeracao_final" placeholder="Numeração Inicial" required>
                                        </div>
                                    </div>
                            </div>
                        </section>
                        <section class="row">
                            <div class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <a href="{{route($Page->link.'.index')}}"
                                           class="btn btn-danger btn-lg btn-block">Cancelar</a>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                        <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </section>
        @if (session()->has('Selos'))
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Técnico</th>
                            <th>Numeração</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(session()->get('Selos') as $sel)
                            <tr>
                                <td>{{$sel->idselo}}</td>
                                <td>{{$sel->getNomeTecnico()}}</td>
                                <td>{{$sel->numeracao}}</td>
                                <td>@if($sel->used)
                                        <span class="btn btn-danger btn-xs">Usado</span>
                                    @else
                                        <span class="btn btn-success btn-xs">Disponível</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @elseif (session()->has('Lacres'))
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Técnico</th>
                            <th>Numeração</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(session()->get('Lacres') as $sel)
                            <tr>
                                <td>{{$sel->idlacre}}</td>
                                <td>{{$sel->getNomeTecnico()}}</td>
                                <td>{{$sel->numeracao}}</td>
                                <td>@if($sel->used)
                                        <span class="btn btn-danger btn-xs">Usado</span>
                                    @else
                                        <span class="btn btn-success btn-xs">Disponível</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
    <!-- /page content -->

@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
    <!-- Datatables -->
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

@endsection