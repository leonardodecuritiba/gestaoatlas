@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('page_content')
    @include('layouts.modals.sintegra')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        {!! Form::open(['route' => $Page->link.'.store', 'files' => true, 'method' => 'POST',
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Tipo de cadastro </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo de Fornecedor<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <select class="select2_single form-control" name="tipo_fornecedor" tabindex="-1">
                                            <option value="0">Pessoa Física</option>
                                            <option value="1">Pessoa Jurídica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Segmento<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <select name="idsegmento_fornecedor" class="form-control" required>
                                            <option value="">Escolha o Segmento</option>
                                            @foreach($Page->extras['segmentos_fornecedores'] as $sel)
                                                <option value="{{$sel->idsegmento_fornecedor}}">{{$sel->descricao}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Grupo</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input value="{{old('grupo')}}" type="text" class="form-control" name="grupo" placeholder="Grupo">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Orçamento<span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input value="{{old('email_orcamento')}}" type="text" class="form-control" name="email_orcamento" placeholder="Email" required>
                                    </div>
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Responsável</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input value="{{old('nome_responsavel')}}" type="text" class="form-control" name="nome_responsavel" placeholder="Nome do Responsável">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row" id="form_pfisica">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <?php $existe_entidade = 0; ?>
                        @include('pages.forms.form_pfisica')
                    </div>
                </div>
            </section>
            <section class="row" id="form_pjuridica" style="display:none">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <?php $existe_entidade = 0; ?>
                        @include('pages.forms.form_pjuridica')
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <?php $existe_entidade = 0; ?>
                        @include('pages.forms.form_contato')
                    </div>
                </div>
            </section>
            <section class="row">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <button type="reset" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                        </div>
                    </div>
                </div>
            </section>
        {{ Form::close() }}

    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
    {!! Html::script('build/js/script_pjuridica.js') !!}
    <script>

        //AJUSTA LAYOUT TIPO FORNECEDOR
        $(document).ready(function() {
            $("select[name=tipo_fornecedor]").change(function () {
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