@extends('layouts.template')
@section('style_content')
<!-- icheck -->
{!! Html::style('css/icheck/flat/green.css') !!}
    <style>
        .select2 {
            width: 100%;
        }
        .preco {
            font-size: 13px;
            font-weight: 400;
            color: #26B99A;
        }
    </style>
<!-- Select2 -->
@include('helpers.select2.head')
@endsection
@section('page_content')
    @include('layouts.modals.sintegra')
    <div class="x_panel">
        @if(!$Cliente->validado())
            <div class="x_title">
                <h2>Cliente - {{($Cliente->getType()->tipo_cliente)?'Pessoa Jurídica':'Pessoa Física'}} </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button onclick="window.location.href='{{route('cliente.validar',$Cliente->idcliente)}}'"
                                class="btn btn-success"><i class="fa fa-check fa-2"></i> Validar
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="alert alert-danger fade in" role="alert">
                Este cliente ainda não foi validado!
            </div>
        @else
            <div class="x_title">
                <h3>Cliente - {{($Cliente->getType()->tipo_cliente)?'Pessoa Jurídica':'Pessoa Física'}} </h3>
                <div class="clearfix"></div>
            </div>
        @endif
        <div class="x_content">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 profile_left">
                @include('pages.'.$Page->link.'.paineis.perfil')
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" @if($Page->tab == 'sobre')class="active" @endif><a href="#tab_sobre" role="tab" data-toggle="tab" aria-expanded="true">Sobre</a></li>
                        <li role="presentation" @if($Page->tab == 'instrumentos')class="active" @endif><a href="#tab_instrumentos" role="tab" data-toggle="tab" aria-expanded="false">Instrumentos</a></li>
                        <li role="presentation" @if($Page->tab == 'equipamentos')class="active" @endif><a href="#tab_equipamentos" role="tab" data-toggle="tab" aria-expanded="false">Equipamentos</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'sobre')active in @endif fade" id="tab_sobre" aria-labelledby="home-tab">
                            @include('pages.'.$Page->link.'.paineis.sobre')
                        </div>
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'instrumentos') active in @endif fade" id="tab_instrumentos" aria-labelledby="profile-tab">
                            @include('pages.'.$Page->link.'.paineis.instrumentos')
                        </div>
                        <div role="tabpanel" class="tab-pane @if($Page->tab == 'equipamentos') active in @endif fade" id="tab_equipamentos" aria-labelledby="profile-tab">
                            @include('pages.'.$Page->link.'.paineis.equipamentos')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>

    {!! Html::script('build/js/script_pjuridica.js') !!}
    <script>
        //AJUSTA LAYOUT CENTRO CUSTO
        $(document).ready(function() {
            $("input[name=centro_custo]").on('ifChecked', function(event){
                $('select[name=idcliente_centro_custo]').parent('div').toggle();
                $('input[name=limite_credito]').parent('div').toggle();
                if(this.value){
                    $('select[name=idcliente_centro_custo]').attr('required', true);
                    $('input[name=limite_credito]').attr('required', false);
                } else {
                    $('select[name=idcliente_centro_custo]').attr('required', false);
                    $('input[name=limite_credito]').attr('required', true);
                }
            });
        });

        //AJUSTA LAYOUT TIPO CLIENTE
        $(document).ready(function() {
            $("select[name=tipo_cliente]").change(function () {
                if($(this).val()=="1"){
                    $('section#form_pfisica').hide();
                    $("input[name=cpf]").attr('required', false);

                    $('section#form_pjuridica').show();
                    $.each(list_required_pjuridica, function(i,v){
                        $("input[name=" + v + "]").attr('required', true);
                    })


                } else {
                    $('section#form_pfisica').show();
                    $("input[name=cpf]").attr('required', true);

                    $('section#form_pjuridica').hide();
                    $.each(list_required_pjuridica, function(i,v){
                        $("input[name=" + v + "]").attr('required', false);
                    })

                }
            });
        });

        //CONSULTA CNPJ
        $(document).ready(function() {
            $("div#captchaModal").on("hide.bs.modal", function () {
                $parent = $(this).find('div.modal-body');
                $($parent).find("img").attr("src", '');
                $($parent).find("div.form-group").removeClass('has-error');
                $($parent).find("div.form-group span").empty();
                $($parent).find("div.form-group input[name=captcha]").val('');
            });
            $("div#captchaModal").on("show.bs.modal", function () {
                $parent = $(this).find('div.modal-body');
                $loading_modal = $(this).find('div.loading');
                $($loading_modal).show();
                $.ajax({
                    url: "{{route('get_sintegra_params')}}",
                    type: 'GET',
                    dataType: "json",
                    error: function (xhr, textStatus) {
                        console.log('xhr-error: ' + xhr.responseText);
                        console.log('textStatus-error: ' + textStatus);
                    },
                    success: function (result) {
                        console.log(result);
                        $($loading_modal).hide();
                        $($parent).find("img").attr("src", result.captchaBase64);
                        $($parent).find("input[name=paramBot]").val(result.paramBot);
                        $($parent).find("input[name=cookie]").val(result.cookie);
                    }
                });
            });
            $("#consultarCNPJ").click(function() {
                var btn = $(this);
                var old = btn.html();

                $parent = $('div#captchaModal').find('div.modal-body');
                $($parent).find("input[name=captcha]").parent('div.form-group').removeClass('has-error');
                $($parent).find("input[name=captcha]").siblings('span').empty();

                var param = {
                    cnpj:       $("input[name=cnpj]").val(),
                    paramBot:   $($parent).find("input[name=paramBot]").val(),
                    captcha:    $($parent).find("input[name=captcha]").val(),
                    cookie:     $($parent).find("input[name=cookie]").val()
                };

                console.log(param);

                btn.html('Aguarde! Consultando..');

                //consulta.php
                $.get("{{route('consulta_sintegra_sp')}}", param, function(retorno) {

                    console.log(retorno.status);
                    if (retorno.status == 1) {

                        $.each(retorno,function(i,v){
//                            console.log(i);console.log(v);
                            $("input[name=" + i + "").val(v.toUpperCase());
                        });
                        $('#captchaModal').modal('hide');
                    } else {
                        $($parent).find("input[name=captcha]").parent('div.form-group').addClass('has-error');
                        $($parent).find("input[name=captcha]").siblings('span').html(retorno.response);
                    }

                    btn.html(old);

                }, "json");

            });
        });
    </script>

    {{--padrões do cliente--}}
    {!! Html::script('vendors/jquery.inputmask/dist/min/inputmask/inputmask.min.js') !!}
    {{--INPUT MASKS--}}
    <script type="text/javascript">
        $(document).ready(function () {
            $('.show-cep').inputmask({'mask': '99999-999', 'removeMaskOnSubmit': true});
            $('.show-cpf').inputmask({'mask': '999.999.999-99', 'removeMaskOnSubmit': true});
            $('.show-cnpj').inputmask({'mask': '99.999.999/9999-99', 'removeMaskOnSubmit': true});
            $('.show-ie').inputmask({'mask': '999.999.999.999', 'removeMaskOnSubmit': true});
            $('.show-rg').inputmask({'mask': '99.999.999-9', 'removeMaskOnSubmit': true});
            $('.show-celular').inputmask({'mask': '(99) 99999-9999', 'removeMaskOnSubmit': true});
            $('.show-telefone').inputmask({'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true});
        });
    </script>

    <!-- maskmoney -->
    {!! Html::script('js/maskmoney/jquery.maskMoney.min.js') !!}
    <script type="text/javascript">
        function initMaskMoney(selector) {
            $(selector).maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false});
        }
        $(document).ready(function () {
            initMaskMoney($(".show-dinheiro"));
        });
    </script>

    <!-- daterangepicker -->
    {!! Html::script('js/datepicker/moment.min.js') !!}
    {!! Html::script('js/datepicker/daterangepicker.js') !!}
    <script type="text/javascript">
        //    calender_style: "picker_4"
        var locale = {
            format: "DD/MM/YYYY",
            separator: " - ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "De",
            toLabel: "A",
            customRangeLabel: "Customizado",
            daysOfWeek: [
                "Dom",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "Sáb"
            ],
            monthNames: [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        };
        var dateOptionsToNow = {
            locale: locale,
            maxDate: new Date(),
            singleDatePicker: true,
            autoUpdateInput: false
        };
        var dateOptionsFromNow = {
            locale: locale,
            minDate: new Date(),
            singleDatePicker: true,
            autoUpdateInput: false
        };
        var dateOptionsEvery = {
            locale: locale,
            singleDatePicker: true,
            autoUpdateInput: false
        };
        $(document).ready(function () {

            $('.data-every').daterangepicker(dateOptionsEvery);
            $('.data-to-now').daterangepicker(dateOptionsToNow);
            $('.data-from-now').daterangepicker(dateOptionsFromNow);
            $('.data-every, .data-to-now, .data-from-now').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format(locale.format));
            });
        });
    </script>
@endsection

