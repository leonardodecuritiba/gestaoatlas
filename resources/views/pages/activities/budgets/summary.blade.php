@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->

    <!-- Select2 -->
    @include('helpers.select2.head')
    <!-- /Select2 -->

    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <!-- /icheck -->

@endsection
@section('modals_content')
    @include('pages.activities.budgets.inc.orcamento')
@endsection
@section('page_content')
        <section class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Resumo</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    {{--STATUS--}}

                    <div class="alert fade in alert-{{$Data->getSituationColor()}}" role="alert">
                        Status do Orçamento de Venda: <b>{{$Data->getSituationText()}}</b>
                    </div>

                    @include('pages.activities.budgets.inc.details')

                    <div class="ln_solid"></div>

                </div>
                <div class="x_content">

                    {{--RESPONSIBLE--}}

                    {!! Form::open(['route' => ['budgets.close',$Data->id],
                        'method' => 'POST',
                        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}

                    @if(!$Data->isClosed())

                        <section class="row">

                            {{--ALERTA DE ISENÇÃO DE CUSTOS--}}
                            <div class="alert-cost_exemption form-group @if($Data->cost_exemption == 0) esconda @endif">
                                <div class="alert alert-danger fade in"
                                     role="alert">
                                    <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Este Orçamento está
                                    sendo isentado de custos com Deslocamentos, Pedágios e Outros Custos.
                                </div>
                            </div>


                            {{-- NOME RESPONSÁVEL / ISENCAO DE CUSTOS--}}
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Responsável: <span
                                            class="required">*</span></label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <input name="responsible" type="text" maxlength="100" class="form-control"
                                           value="{{old('responsible',$Data->responsible)}}"
                                           required>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="checkbox">
                                        <label>
                                            <input name="cost_exemption" type="checkbox" class="flat"
                                                   @if($Data->cost_exemption == 1) checked="checked" @endif
                                            > Isenção de Custos
                                        </label>
                                    </div>
                                </div>
                            </div>


                            {{-- CPF RESPONSÁVEL / CARGO RESPONSÁVEL --}}
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">CPF: <span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input name="responsible_cpf" type="text" maxlength="16" class="form-control show-cpf"
                                           value="{{old('responsible_cpf', $Data->responsible_cpf)}}"
                                           required>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Cargo: <span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input name="responsible_office" type="text" maxlength="50" class="form-control"
                                           value="{{old('responsible_office', $Data->responsible_office)}}"
                                           required>
                                </div>
                            </div>

                        </section>

                        <div class="ln_solid"></div>

                    @endif


                    {{--CLOSE--}}

                    <section class="row">

                        <div class="form-group">

                            {{--PRINT--}}
                            <div class="{{$Data->isClosed() ? 'col-md-6 col-sm-6' : 'col-md-3 col-sm-3'}} col-xs-12">
                                <a target="_blank"
                                   href="{{route('budgets.print',$Data->id)}}"
                                   class="btn btn-default btn-lg btn-block"><i class="fa fa-print fa-2"></i>
                                    Imprimir</a>
                            </div>


                            {{--SEND--}}
                            <div class="{{$Data->isClosed() ? 'col-md-6 col-sm-6' : 'col-md-3 col-sm-3'}} col-xs-12">
                                <a href="{{route('budgets.send',$Data->id)}}"
                                   class="btn btn-primary btn-lg btn-block"><i class="fa fa-envelope fa-2"></i>
                                    Encaminhar</a>
                            </div>


                            @if(!$Data->isClosed())

                                {{--EDITAR--}}
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <a href="{{route('budgets.show',$Data->id)}}"
                                       class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left fa-2"></i> Editar</a>
                                </div>


                                {{--FINALIZAR--}}
                                <div class="col-md-3 col-sm-3 col-xs-12 ">
                                    <button class="btn btn-success btn-lg btn-block"><i class="fa fa-sign-out fa-2"></i>
                                        Arquivar
                                    </button>
                                </div>

                            @endif
                        </div>

                    </section>


                </div>
            </div>
        </section>


        {{--Peças/Produtos--}}

        <section class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Peças/Produtos</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table border="0" class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th width="40%">Nome</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Desconto</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th width="40%">Nome</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Desconto</th>
                                <th>Total</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($Data->getPartsFormatted() as $sel)
                                <tr>
                                    <td>{{$sel['id']}}</td>
                                    <td>{{$sel['name']}}</td>
                                    <td>{{$sel['price']}}</td>
                                    <td>{{$sel['quantity']}}</td>
                                    <td>{{$sel['discount']}}</td>
                                    <td>{{$sel['total']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>



        {{--Serviços--}}

        <section class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Serviços</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table border="0" class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th width="40%">Nome</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Desconto</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th width="40%">Nome</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Desconto</th>
                                <th>Total</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($Data->getServicesFormatted() as $sel)
                                <tr>
                                    <td>{{$sel['id']}}</td>
                                    <td>{{$sel['name']}}</td>
                                    <td>{{$sel['price']}}</td>
                                    <td>{{$sel['quantity']}}</td>
                                    <td>{{$sel['discount']}}</td>
                                    <td>{{$sel['total']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>



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
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>

    <!-- /Datatables -->


    <!-- Parsley -->

    {!! Html::script('js/parsley/parsley.min.js') !!}

    <!-- /Parsley -->

    <script>
        $(document).ready(function () {
            //ISENÇÃO DE DESLOCAMENTO
            $('input[name="cost_exemption"]').on('ifToggled', function (event) {
                var $alert = $(this).parents().find('div.alert-cost_exemption');
                if (this.checked) {
                    $($alert).show();
                } else {
                    $($alert).hide();
                }
            });
        });
    </script>


@endsection