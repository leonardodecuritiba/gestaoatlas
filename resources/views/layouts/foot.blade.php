{!! Html::script('vendors/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Html::script('js/chartjs/chart.min.js') !!}
{!! Html::script('js/nicescroll/jquery.nicescroll.min.js') !!}
{!! Html::script('js/progressbar/bootstrap-progressbar.min.js') !!}
{!! Html::script('vendors/iCheck/icheck.min.js') !!}
{!! Html::script('build/js/custom.min.js') !!}

{!! Html::script('js/inputmask/inputmask.min.js') !!}
{!! Html::script('js/inputmask/jquery.inputmask.min.js') !!}

<!-- PNotify -->
{!! Html::script('vendors/pnotify/dist/pnotify.js') !!}
{!! Html::script('vendors/pnotify/dist/pnotify.buttons.js') !!}
{!! Html::script('vendors/pnotify/dist/pnotify.nonblock.js') !!}
<!-- /PNotify -->

{{--PACIENTES--}}
<script type="text/javascript">
    $(document).ready(function () {
        $('.show-cep').inputmask({'mask': '99999-999', 'removeMaskOnSubmit': true});
        $('.show-cpf').inputmask({'mask': '999.999.999-99', 'removeMaskOnSubmit': true});
        $('.show-cnpj').inputmask({'mask': '99.999.999/9999-99', 'removeMaskOnSubmit': true});
        $('.show-ie').inputmask({'mask': '999.999.999.999', 'removeMaskOnSubmit': true});
        $('.show-rg').inputmask({'mask': '99.999.999-9', 'removeMaskOnSubmit': true});
        $('.show-celular').inputmask({'mask': '(99) 99999-9999', 'removeMaskOnSubmit': true});
        $('.show-telefone').inputmask({'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true});
        $('.show-placa').inputmask({'mask': "aaa-9999", 'removeMaskOnSubmit': true});
    });
</script>

{!! Html::script('js/maskmoney/jquery.maskMoney.min.js') !!}
<script type="text/javascript">
    function initMaskMoneyFix(selector) {
        $(selector).maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: true
        });
    }
    function initMaskMoney(selector) {
        $(selector).maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: false
        });
    }

    function initMaskMoneyValorReal(selector) {
        $(selector).maskMoney({allowNegative: false, thousands: '.', decimal: ',', precision: 2, affixesStay: false});
    }

    function initMaskMoneyDolar(selector) {
        $(selector).maskMoney({
            prefix: '$ ',
            allowNegative: false,
            thousands: ',',
            decimal: '.',
            precision: 2,
            affixesStay: false
        });
    }

    function initMaskMoneyPorcento(selector) {
        $(selector).maskMoney({
            suffix: ' %',
            allowNegative: false,
            allowZero: true,
            thousands: '.',
            decimal: ',',
            affixesStay: false
        });
    }

    function initMaskMoneyPeso(selector) {
        $(selector).maskMoney({
            suffix: ' Kg',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            precision: 2,
            affixesStay: false
        });
    }

    function initMaskGarantia(selector) {
        $(selector).maskMoney({suffix: ' meses', precision: 0, affixesStay: false});
    }

    function initMaskMoneyNumero(selector) {
        $(selector).maskMoney({allowNegative: false, thousands: '', decimal: '', precision: 0, affixesStay: false});
    }

    function initMaskMoneyPositivos(selector) {
        $(selector).maskMoney({
            allowNegative: false,
            allowZero: false,
            precision: 0,
            thousands: '',
            decimal: '',
            affixesStay: false
        });
    }

    function initMaskMoneyParcelas(selector) {
        $(selector).maskMoney({
            suffix: ' dias',
            allowNegative: false,
            allowZero: false,
            precision: 0,
            thousands: '',
            decimal: '',
            affixesStay: false
        });
    }

    function initMaskKm(selector) {
        $(selector).maskMoney({suffix: ' KM', precision: 0, thousands: '', decimal: '', affixesStay: false});
    }


    $(document).ready(function () {
        initMaskMoneyFix($(".show-valor-fixo"));
    });
    $(document).ready(function () {
        initMaskMoney($(".show-valor"));
    });
    $(document).ready(function () {
        initMaskMoneyValorReal($(".show-valor-real"));
    });
    $(document).ready(function () {
        initMaskMoney($(".show-valor-noreal"));
    });
    $(document).ready(function () {
        initMaskMoneyDolar($(".show-valor-dolar"));
    });
    $(document).ready(function () {
        initMaskMoneyPorcento($(".show-porcento"));
    });
    $(document).ready(function () {
        initMaskGarantia($(".show-meses"));
    });
    $(document).ready(function () {
        initMaskMoneyPeso($(".show-peso"));
    });
    $(document).ready(function () {
        initMaskMoneyNumero($(".show-inteiro"));
    });
    $(document).ready(function () {
        initMaskMoneyPositivos($(".show-inteiro-positivo"));
    });
    $(document).ready(function () {
        initMaskMoneyParcelas($(".show-parcelas"));
    });
    $(document).ready(function () {
        initMaskKm($(".show-km"));
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


<script>
    $(document).ready(function () {
        $_LOADING_ = $("div.loading");
    });
    $(document).ready(function () {
        $('#buscar').keypress(function (e) {
            if (e.which == 13) {
                $('form#search').submit();
                return false;    //<---- Add this line
            }
        });
    });

    <!-- script remoção -->
    $(document).ready(function () {

        $('div#modalRemocao').on('show.bs.modal', function(e) {
            $origem = $(e.relatedTarget);
            nome_ = $($origem).data('nome');
            href_ = $($origem).data('href');
            $el = $($origem).data('elemento');
            $(this).find('.modal-body').html('Você realmente deseja remover <strong>'+nome_ + '</strong> e suas relações? Esta ação é irreversível!');
            $(this).find('.btn-ok').click(function(){

//                console.log($el);return;
                $('div#modalRemocao').modal('hide');
                $.ajax({
                    url: href_,
                    type: 'post',
                    data: {"_method": 'delete', "_token": "{{ csrf_token() }}"},
                    dataType: "json",
                    /*
                     beforeSend: function () {
                     $(".onLoading").show();
                     },
                     complete: function (xhr, textStatus) {
                     $(".onLoading").hide();
                     },
                     error: function (xhr, textStatus) {
                     console.log('xhr-error: ' + xhr);
                     console.log('textStatus-error: ' + textStatus);
                     },
                     */
                    success: function (json) {
                        if(json.status){
                            console.log(json.response);

                            $el = $($origem).closest('tr');
                            if($el.length == 0){
                                $el = $($origem).closest('.tr');
                            }
                            $($el).remove();
                        } else {
                            alert(json.response);
                        }
                    }
                });

            });
        });
    });
</script>

<!-- script ativar/desativar -->
<script>
    function ajaxActive($target_,action_){
        var href_ = '{{url('ajax')}}';
        if(typeof $($target_).data('href') != 'undefined'){
            href_ = $($target_).data('href');
        }
        $.ajax({
            url: href_,
            type: 'GET',
            data: {id: $($target_).data('id'),
                table: $($target_).data('table'),
                pk: $($target_).data('pk'),
                sk: $($target_).data('sk'),
                action: action_},
            dataType: "json",
            error: function (xhr, textStatus) {
                console.log('xhr-error: ' + xhr.responseText);
                console.log('textStatus-error: ' + textStatus);
            },
            success: function (json) {
                console.log(json);
                if(json.status==1) {
                    if (json.valor == 1) {
                        $($target_).data('value', 1);
                        $($target_).html('<i class="fa fa-eye-slash"></i>Desativar');
                        $($target_).closest('tr').find('td.td-active:first').html('<span class="btn btn-success btn-xs">Ativo</span>');
                    } else {
                        $($target_).data('value', 0);
                        $($target_).html('<i class="fa fa-eye"></i>Ativar');
                        $($target_).closest('tr').find('td.td-active:first').html('<span class="btn btn-danger btn-xs">Inativo</span>');
                    }
                }
            }
        });
    }
    $(document).ready(function () {
        $('a.btn-active').click(function(){
            if($(this).data('value')){
                ajaxActive($(this), 'desativar');
            } else {
                ajaxActive($(this), 'ativar');
            }
        });
    });
</script>
<!-- /script ativar/desativar -->

<!-- script aprovar/Não Aprovar -->
<script>
    /*
     function ajaxAprovar($target_,action_){
     var href_ = '{{url('ajax')}}';
     if(typeof $($target_).data('href') != 'undefined'){
     href_ = $($target_).data('href');
     }
     $.ajax({
     url: href_,
     type: 'GET',
     data: {id: $($target_).data('id'),
     table: $($target_).data('table'),
     pk: $($target_).data('pk'),
     sk: $($target_).data('sk'),
     action: action_},
     dataType: "json",
     error: function (xhr, textStatus) {
     console.log('xhr-error: ' + xhr.responseText);
     console.log('textStatus-error: ' + textStatus);
     },
     success: function (json) {
     console.log(json);
     if(json.status==1) {
     if (json.valor == 1) {
     //                        $($target_).data('value', 1);
     //                        $($target_).html('<i class="fa fa-thumbs-o-down"></i> Não Aprovar');
     $($target_).closest('tr').find('td.td-aprovar:first').html('<span class="btn btn-success btn-xs">Aprovado</span>');
     $($target_).remove();
     }
     else {
     $($target_).data('value', 0);
     $($target_).html('<i class="fa fa-thumbs-o-up"></i> Aprovar');
     $($target_).closest('tr').find('td.td-aprovar:first').html('<span class="btn btn-danger btn-xs">Não Aprovado</span>');
     }

     }
     }
     });
     }
     $(document).ready(function () {
     $('a.btn-aprovar').click(function(){
     if($(this).data('value')){
     ajaxAprovar($(this), 'desativar');
     } else {
     ajaxAprovar($(this), 'ativar');
     }
     });
     });
     */
</script>
<!-- /script aprovar/Não Aprovar -->


<!-- bootstrap progress js -->
<script>
    NProgress.done();
</script>