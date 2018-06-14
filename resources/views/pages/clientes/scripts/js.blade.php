<!-- form validation -->
{!! Html::script('js/parsley/parsley.min.js') !!}
{!! Html::script('build/js/script_pjuridica.js') !!}

<script>
    //AJUSTA LAYOUT CENTRO CUSTO
    $(document).ready(function () {
        $("input[name=centro_custo]").on('ifChecked', function (event) {
            $('select[name=idcliente_centro_custo]').parent('div').toggle();
            // $('input[name=limite_credito_tecnica]').parent('div').toggle();
            if (this.value == "1") {
                $('select[name=idcliente_centro_custo]').attr('required', true);
                // $('input[name=limite_credito_tecnica]').attr('required', false);
            } else {
                $('select[name=idcliente_centro_custo]').attr('required', false);
                // $('input[name=limite_credito_tecnica]').attr('required', true);
            }
        });
    });
</script>

<script>
    //AJUSTA LAYOUT TIPO CLIENTE
    $(document).ready(function () {
        $("select[name=tipo_cliente]").change(function () {
            if ($(this).val() == "1") {
                $('section#form_pfisica').hide();
                $("input[name=cpf]").attr('required', false);

                $('section#form_pjuridica').show();
                $.each(list_required_pjuridica, function (i, v) {
                    $("input[name=" + v + "]").attr('required', true);
                })


            } else {
                $('section#form_pfisica').show();
                $("input[name=cpf]").attr('required', true);

                $('section#form_pjuridica').hide();
                $.each(list_required_pjuridica, function (i, v) {
                    $("input[name=" + v + "]").attr('required', false);
                })

            }
        });
    });
</script>

<script>
    //CONSULTA CNPJ
    $(document).ready(function () {
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
        $("#consultarCNPJ").click(function () {
            var btn = $(this);
            var old = btn.html();

            $parent = $('div#captchaModal').find('div.modal-body');
            $($parent).find("input[name=captcha]").parent('div.form-group').removeClass('has-error');
            $($parent).find("input[name=captcha]").siblings('span').empty();

            var param = {
                cnpj: $("input[name=cnpj]").val(),
                paramBot: $($parent).find("input[name=paramBot]").val(),
                captcha: $($parent).find("input[name=captcha]").val(),
                cookie: $($parent).find("input[name=cookie]").val()
            };

            console.log(param);

            btn.html('Aguarde! Consultando..');

            //consulta.php
            $.get("{{route('consulta_sintegra_sp')}}", param, function (retorno) {

                console.log(retorno.status);
                if (retorno.status == 1) {

                    $.each(retorno, function (i, v) {
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

<script>
    //AJUSTA PARCELAS
        <?php
        if (isset($Cliente)) {
            $n_parcelas_tecnica = count($Cliente->prazo_pagamento_tecnica->extras) - 1;
            $n_parcelas_comercial = count($Cliente->prazo_pagamento_tecnica->extras) - 1;
        } else {
            $n_parcelas_tecnica = 0;
            $n_parcelas_comercial = 0;
        }
        ?>
    var N_PARCELA_TECNICA = parseInt('{{$n_parcelas_tecnica}}');
    var N_PARCELA_COMERCIAL = parseInt('{{$n_parcelas_comercial}}');
    function addParcela($this, type) {
        if (type == 'tecnica') {
            var n_parcela = ++N_PARCELA_TECNICA;
        } else {
            var n_parcela = ++N_PARCELA_COMERCIAL;
        }
        var input_name = 'parcela_' + type;
        $parent = $($this).parents('div.form-group').next();
        var html = '<div class="form-group">' +
            '<label class="control-label col-md-2 col-sm-2 col-xs-12">' + (n_parcela + 1) + 'ª Parcela</label>' +
            '<div class="col-md-4 col-sm-4 col-xs-12">' +
            '<input type="text" class="form-control show-parcelas" name=' + input_name + '[' + n_parcela + ']">' +
            '</div>' +
            '</div>';
        $($parent).append(html);
        initMaskMoneyParcelas($($parent).find('input[name="' + input_name + '[' + n_parcela + ']"]'));
    }
    function remParcela($this, type) {
        $parent = $($this).parents('div.form-group').next();
        if ($($parent).find('div.form-group').length > 1) {
            if (type == 'tecnica') {
                N_PARCELA_TECNICA--;
            } else {
                N_PARCELA_COMERCIAL--;
            }
            $($parent).children().last().remove();
        }
    }

    $(document).ready(function () {
        $("select[name=prazo_pagamento_tecnica],select[name=prazo_pagamento_comercial]").change(function () {
            $parent = $(this).parents('div.form-group');
            if ($(this).val() == "0") {
                $($parent).find('div.parcelas').hide();
                $($parent).next().hide();
            } else {
                $($parent).find('div.parcelas').show();
                $($parent).next().show();
            }
        });
    });

    //    $("div#parcelasModal").on("show.bs.modal", function () {
    //        $parent = $(this).find('div.modal-body');
    //        var html = '<div class="form-horizontal form-label-left">' +
    //            '<div class="form-group">' +
    //            '<label class="control-label col-md-2 col-sm-2 col-xs-12">1ª</label>' +
    //            '<div class="col-md-10 col-sm-10 col-xs-12">' +
    //            '<input type="text" class="form-control show-parcelas" name="parcela[1]">' +
    //            '</div>' +
    //            '</div>' +
    //            '</div>';
    //        $($parent).html(html);
    //    });
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
        $(selector).maskMoney({prefix: 'R$ ', allowNegative: false, thousands: '.', decimal: ',', affixesStay: false});
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
        $('.data-every, .data-to-now, .data-from-now').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(locale.format));
        });
    });
</script>