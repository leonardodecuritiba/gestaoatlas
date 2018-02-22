@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->


    <!-- Select2 -->
    @include('helpers.select2.head')
    <!-- /Select2 -->
@endsection
@section('modals_content')
    @include('pages.activities.budgets.inc.orcamento')
@endsection
@section('page_content')

    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->titulo_primario}}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                {{--STATUS--}}

                <div class="alert fade in alert-{{$Data->getSituationColor()}}" role="alert">
                    Status do Orçamento de Venda: <b>{{$Data->getSituationText()}}</b>
                </div>

                @include('pages.activities.budgets.inc.details')

                <section class="row">

                    <div class="form-group">

                        {{--REMOVE--}}
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <a class="btn btn-danger btn-lg btn-block"
                               data-nome="Orçamento #{{$Data->id}}"
                               data-href="{{route('budgets.destroy',$Data->id)}}"
                               data-toggle="modal"
                               data-target="#modalDelecao">
                                <i class="fa fa-trash-o"></i> Remover</a>
                        </div>

                        {{--CLIENT--}}
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <a class="btn btn-warning btn-lg btn-block" target="_blank"
                               href="{{route('clientes.show',$Data->client_id)}}">
                                <i class="fa fa-eye"></i> Cliente</a>
                        </div>

                        {{--FINALIZAR--}}
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <a class="btn btn-success btn-lg btn-block"
                               href="{{route('budgets.summary',$Data->id)}}">
                                <i class="fa fa-check fa-2"></i> Finalizar
                            </a>
                        </div>


                    </div>


                </section>
            </div>
        </div>
    </section>
    {!! Form::open(['route' => ['budgets.save',$Data->id ],
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}

        {{--Peças/Produtos--}}

        <section class="row" id="parts">
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
                                <th>Ações</th>
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
                                        <td>
                                            <a class="btn btn-danger"
                                               data-nome="{{$sel['name']}}"
                                               data-href="{{route('budget_parts.destroy',$sel['id'])}}"
                                               data-toggle="modal"
                                               data-target="#modalRemocao"
                                               data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                                <i class="fa fa-trash fa-lg"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <select class="select2_single form-control" id="part" name="select_part_id"
                                                tabindex="-1">
                                            <option value="">Selecione</option>
                                            @foreach($Page->extras['parts'] as $sel)
                                                <option value="{{$sel['id']}}"
                                                        data-price_formatted="{{$sel['price_formatted']}}"
                                                        data-price="{{$sel['price']}}">
                                                    {{$sel['name']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled id="value" class="form-control show-valor-fixo">
                                    </td>
                                    <td>
                                        <input id="quantity" value="1" type="text"
                                               class="form-control show-inteiro-positivo calc-total"
                                               placeholder="Quantidade">
                                    </td>
                                    <td>
                                        <input id="discount" value="R$ 0,00" type="text"
                                               class="form-control show-valor calc-total"
                                               placeholder="Desconto"
                                               @role('tecnico') disabled @endrole>
                                    </td>
                                    <td>
                                        <input disabled id="total" class="form-control show-valor-fixo">
                                    </td>
                                    <td>
                                        <a class="btn btn-success add"
                                           data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                            <i class="fa fa-check fa-lg"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        {{--Serviços--}}

        <section class="row" id="services">
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
                                <th>Ações</th>
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
                                    <td>
                                        <a class="btn btn-danger"
                                           data-nome="{{$sel['name']}}"
                                           data-href="{{route('budget_services.destroy',$sel['id'])}}"
                                           data-toggle="modal"
                                           data-target="#modalRemocao"
                                           data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                            <i class="fa fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>#</td>
                                <td>
                                    <select class="select2_single form-control" id="service" name="select_service_id"
                                            tabindex="-1">
                                        <option value="">Selecione</option>
                                        @foreach($Page->extras['services'] as $sel)
                                            <option value="{{$sel['id']}}"
                                                    data-price_formatted="{{$sel['price_formatted']}}"
                                                    data-price="{{$sel['price']}}">
                                                {{$sel['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input disabled id="value" class="form-control show-valor-fixo">
                                </td>
                                <td>
                                    <input id="quantity" value="1" type="text"
                                           class="form-control show-inteiro-positivo calc-total"
                                           placeholder="Quantidade">
                                </td>
                                <td>
                                    <input id="discount" value="R$ 0,00" type="text"
                                           class="form-control show-valor calc-total"
                                           placeholder="Desconto"
                                           @role('tecnico') disabled @endrole>
                                </td>
                                <td>
                                    <input disabled id="total" class="form-control show-valor-fixo">
                                </td>
                                <td>
                                    <a class="btn btn-success add"
                                       data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                        <i class="fa fa-check fa-lg"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        {{--FECHAR--}}

        <section class="row">
            <div class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12 pull-right ">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                    </div>
                </div>
            </div>
        </section>

    {!! Form::close() !!}




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


    <!-- Select2 -->
    @include('helpers.select2.foot')

    <script>

        var $_INPUT_VALUE_ = "input#value";
        var $_INPUT_QUANTITY_ = "input#quantity";
        var $_INPUT_DISCOUNT_ = "input#discount";
        var $_INPUT_TOTAL_ = "input#total";
        var $_DATA_PRICE_ = 'price';
        var $_DATA_PRICE_FORMATTED_ = 'price_formatted';
        var x = 0;

        function removeEl($this) {
            var $parent = $($this).parents('tr');
            $($parent).remove();
        }


        $(document).ready(function () {

            //Select - PART

            $("select[name=select_part_id]").on("select2:select", function() {
                //achar parent, pegar próximo td e escrever o valor
                var $sel = $(this).find(":selected");
                var $tr = $(this).parents('tr');
                var $field_preco = $($tr).find($_INPUT_VALUE_);
                var $field_quantidade = $($tr).find($_INPUT_QUANTITY_);
                var $field_desconto = $($tr).find($_INPUT_DISCOUNT_);
                var $field_total = $($tr).find($_INPUT_TOTAL_);

                $($field_quantidade).val(1);
                $($field_desconto).val('0,00');
                var preco = '';
                if($($sel).val()!=''){
                    preco = $($sel).data($_DATA_PRICE_);
                    preco = 'R$ ' + preco;
                }
                $($field_preco).val(preco);
                $($field_total).val(preco);
            });


            //Select - SERVICE

            $("select[name=select_service_id]").on("select2:select", function() {
                //achar parent, pegar próximo td e escrever o valor
                var $sel = $(this).find(":selected");
                var $tr = $(this).parents('tr');
                var $field_preco = $($tr).find($_INPUT_VALUE_);
                var $field_quantidade = $($tr).find($_INPUT_QUANTITY_);
                var $field_desconto = $($tr).find($_INPUT_DISCOUNT_);
                var $field_total = $($tr).find($_INPUT_TOTAL_);

                $($field_quantidade).val(1);
                $($field_desconto).val('0,00');
                var preco = '';
                if($($sel).val()!=''){
                    preco = $($sel).data($_DATA_PRICE_);
                    preco = 'R$ ' + preco;
                }
                $($field_preco).val(preco);
                $($field_total).val(preco);
            });




            $(".calc-total").on("change", function () {
                //achar parent, pegar próximo td e escrever o valor

                var $sel = $(this).parents('td').prevAll().find(":selected");
                var $tr = $(this).parents('tr');

                var $field_preco = $($tr).find($_INPUT_VALUE_);
                var $field_quantidade = $($tr).find($_INPUT_QUANTITY_);
                var $field_desconto = $($tr).find($_INPUT_DISCOUNT_);
                var $field_total = $($tr).find($_INPUT_TOTAL_);

                var quantidade = $($field_quantidade).val();
                var desconto = $($field_desconto).maskMoney('unmasked')[0];
                var preco = '';
                if ($($sel).val() != '') {
                    preco = $($sel).data('price');
                    preco = preco * quantidade;
                    preco = preco - desconto;
                }
                $($field_total).maskMoney('mask', preco);
//                $($field_total ).maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});
            });

            //SELECTS ADICIONADOS AO INSTRUMENTO
            $('a.add').click(function () {
                var data = {};
                var $parent = $(this).parents('tr');
                var id_select = $($parent).find('select').attr('id');
                var $selected = $($parent).find('select#' + id_select).find(":selected");
                if ($($selected).val() != '') {
                    data.id = $($selected).val();
                    data.text = $($selected).html();
                    data.price = $($selected).data($_DATA_PRICE_FORMATTED_);
                    data.quantity = $($parent).find($_INPUT_QUANTITY_).val();
                    data.discount = $($parent).find($_INPUT_DISCOUNT_).val();
                    data.discount_float = $($parent).find($_INPUT_DISCOUNT_).maskMoney('unmasked')[0];
                    data.value = $($selected).data($_DATA_PRICE_);
                    data.total = $($parent).find('input#total').val();
                    x++;
                    var campo = '<tr>' +
                        '<input name="' + id_select + '_discount[' + (x) + ']" type="hidden" value="' + data.discount_float + '" required>' +
                        '<input name="' + id_select + '_quantity[' + (x) + ']" type="hidden" value="' + data.quantity + '" required>' +
                        '<input name="' + id_select + '_value[' + (x) + ']" type="hidden" value="' + data.value + '" required>' +
                        '<input name="' + id_select + '_id[' + (x) + ']" type="hidden" value="' + data.id + '" required>' +
                        '<td>' + data.id + '</td>' +
                        '<td>' + data.text + '</td>' +
                        '<td>' + data.price + '</td>' +
                        '<td>' + data.quantity + '</td>' +
                        '<td>R$ ' + data.discount + '</td>' +
                        '<td>' + data.total + ' </td>' +
                        '<td>' +
                        '<a class="btn btn-danger" onclick="removeEl(this)" title="Excluir">' +
                        '<i class="fa fa-trash fa-lg"></i></a>' +
                        '</td>' +
                        '</tr>';
                    $(campo).insertBefore($parent);
                }
            });
        });
    </script>
    <!-- /Select2 -->
@endsection