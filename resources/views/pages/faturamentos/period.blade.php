@extends('layouts.template')
@section('style_content')
@endsection
@section('page_content')

    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Fechamento por Período</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {!! Form::open(array('route'=>'faturamentos.faturar_periodo','method'=>'POST','id'=>'search','class'=>'form-horizontal form-label-left')) !!}
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Inicial:</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input value="{{Request::get('data_inicial')}}"
                                   type="text" class="form-control data-to-now" name="data_inicial" placeholder="Data"
                                   required>
                        </div>
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Data Final:</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input value="{{Request::get('data_final')}}"
                                   type="text" class="form-control data-to-now" name="data_final" placeholder="Data"
                                   required>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Faturar</button>
                    </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /page content -->
@endsection
@section('scripts_content')
@endsection