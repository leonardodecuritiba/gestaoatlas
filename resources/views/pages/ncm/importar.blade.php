@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('page_content')
    {{--@include('admin.master.forms.search')--}}
    <!-- mascaras -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        {!! Form::open(['route' => $Page->link.'.importar',
            'method'=> 'POST',
            'files' =>  true,
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Dados do {{$Page->Target}}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Arquivo: <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input name="upload" type="file" class="form-control" required >
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="checkbox">
                                            <label>
                                                <input name="aliquotas" type="checkbox" value="1" class="flat"> Lista de NCM com ALÍQUOTAS
                                            </label>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <a href="{{route('ncm.index')}}" class="btn btn-danger btn-lg btn-block">Cancelar</a>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <button id="importar" class="btn btn-success btn-lg btn-block">Importar</button>
                        </div>
                    </div>
                </div>
            </section>
        {{ Form::close() }}
        @if($Importacao != NULL)
            {!! Form::open(['method' => 'POST',
            'route'=>$Page->link.'.storeImportar',
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <section class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Importar {{$Page->Target}}</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table border="0" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>NCM</th>
                                            <th>DESCRIÇÃO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($Importacao as $sel)
                                        <tr>
                                            <td class="col-md-4 col-sm-4 col-xs-12">
                                                <input name="codigo[]" maxlength="50" class="form-control col-md-1 col-xs-1" required="required" type="text" value="{{$sel->codigo}}">
                                            </td>
                                            <td class="col-md-8 col-sm-8 col-xs-12">
                                                <input name="descricao[]" maxlength="100" class="form-control col-md-1 col-xs-1" required="required" type="text" value="{{$sel->descricao}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="row">
                    <div class="form-horizontal form-label-left">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                <a href="{{route('ncm.index')}}" class="btn btn-danger btn-lg btn-block">Cancelar</a>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                <button type="submit" class="btn btn-success btn-lg btn-block">Salvar todos</button>
                            </div>
                        </div>
                    </div>
                </section>
            {{ Form::close() }}
        @endif
    </div>
    <!-- /page content -->

@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
@endsection