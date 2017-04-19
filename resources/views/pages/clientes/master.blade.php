@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <!-- Select2 -->
    @include('helpers.select2.head')
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
        {!! Form::open(['route' => $Page->link.'.store',
            'files' => true,
            'method' => 'POST',
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Dados do Cliente</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo de Cliente <span class="required">*</span></label>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select class="select2_single form-control" name="tipo_cliente" tabindex="-1">
                                        <option value="0" @if(old("tipo_cliente") == 0) selected @endif>Pessoa Física
                                        </option>
                                        <option value="1" @if(old("tipo_cliente") == 1) selected @endif>Pessoa
                                            Jurídica
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome do Responsável<span class="required">*</span></label>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <input value="{{old('nome_responsavel')}}" type="text" class="form-control" name="nome_responsavel" placeholder="Nome do Responsável">
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Segmento<span class="required">*</span></label>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <select name="idsegmento" class="select2_single form-control" required>
                                        <option value="">Escolha o Segmento</option>
                                        @foreach($Page->extras['segmentos'] as $sel)
                                            <option value="{{$sel->idsegmento}}"
                                                    @if(old("idsegmento") == $sel->idsegmento) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Orçamento<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input value="{{old('email_orcamento')}}" type="text" class="form-control" name="email_orcamento" placeholder="Email" required>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Nota<span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input value="{{old('email_nota')}}" type="text" class="form-control" name="email_nota" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Região Franquia /
                                    Filial</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <select name="idregiao" class="select2_single form-control" required>
                                        <option value="">Região</option>
                                        @foreach($Page->extras['regioes'] as $sel)
                                            <option value="{{$sel->idregiao}}"
                                                    @if(old("idregiao") == $sel->idregiao) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Foto<span class="required">*</span></label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input value="{{old('foto')}}" type="file" class="form-control" name="foto"  required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Dados Financeiros (Técnica)</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Centro de Custo <span
                                            class="required">*</span></label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="radio" name="centro_custo" value="0" class="flat"
                                                   checked="checked" required> Não
                                            <input type="radio" name="centro_custo" value="1" class="flat" required> Sim
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12" style="display:none;">
                                    <select name="idcliente_centro_custo" class="select2_single form-control">
                                        <option value="">Centro de Custo</option>
                                        @foreach($Page->extras['centro_custo'] as $sel)
                                            <option value="{{$sel->idcliente}}"
                                                    @if(old("idcliente") == $sel->idcliente) selected @endif>{{$sel->getType()->nome_principal}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <input value="{{old('limite_credito_tecnica')}}" type="text"
                                           class="form-control show-dinheiro" name="limite_credito_tecnica"
                                           placeholder="Limite de Crédito Técnica" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Distância (Km)<span class="required">*</span></label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input value="{{old('distancia')}}" type="text" class="form-control" name="distancia" placeholder="Distância (Km)" required>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Custo em Pedágios (R$)<span class="required">*</span></label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input value="{{old('pedagios')}}" type="text" class="form-control show-dinheiro" name="pedagios" placeholder="Custo Pedágios" required>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Outros Custos (R$)</label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input value="{{old('outros_custos')}}" type="text"
                                           class="form-control show-dinheiro" name="outros_custos"
                                           placeholder="Outros Custos">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tabela de Preço<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="idtabela_preco_tecnica" class="form-control" required>
                                        <option value="">Escolha a Tabela</option>
                                        @foreach($Page->extras['tabela_precos'] as $tabela_preco)
                                            <option value="{{$tabela_preco->idtabela_preco}}"
                                                    @if(old('idtabela_preco_tecnica') == $tabela_preco->idtabela_preco) selected @endif>{{$tabela_preco->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo de Emissão
                                    Faturamento<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="idemissao_tecnica" class="form-control" required>
                                        <option value="">Escolha um Tipo</option>
                                        @foreach($Page->extras['tipos_emissao_faturamento'] as $sel)
                                            <option value="{{$sel->id}}"
                                                    @if(old('idemissao_tecnica') == $sel->id) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Forma de Pagamento<span
                                            class="required">*</span></label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <select name="idforma_pagamento_tecnica" class="form-control" required>
                                        <option value="">Escolha a Forma</option>
                                        @foreach($Page->extras['formas_pagamentos'] as $sel)
                                            <option value="{{$sel->idforma_pagamento}}"
                                                    @if(old('idforma_pagamento_tecnica') == $sel->idforma_pagamento) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Prazo de Pagamento<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {{--{{$Cliente->prazo_pagamento_tecnica->descricao}}--}}
                                    <select name="prazo_pagamento_tecnica" class="form-control" required>
                                        <option value="">Escolha o Prazo</option>
                                        <option value="0" @if(old('prazo_pagamento_tecnica') == 0) selected @endif>À
                                            VISTA
                                        </option>
                                        <option value="1" @if(old('prazo_pagamento_tecnica') == 1) selected @endif>
                                            PARCELADO
                                        </option>
                                    </select>
                                </div>
                                <div class="parcelas esconda">
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="addParcela(this)"><i
                                                    class="fa fa-plus"></i> Parcela</a>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="remParcela(this)"><i
                                                    class="fa fa-minus"></i> Parcela</a>
                                    </div>
                                </div>
                            </div>
                            <div class="parcelas esconda">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">1ª Parcela</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" class="form-control show-parcelas" name="parcela_tecnica[0]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Dados Financeiros (Comercial)</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Limite Crédito<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input value="{{old('limite_credito_comercial')}}" type="text"
                                           class="form-control show-dinheiro" name="limite_credito_comercial"
                                           placeholder="Limite de Crédito Comercial" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tabela de Preço<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="idtabela_preco_comercial" class="form-control" required>
                                        <option value="">Escolha a Tabela</option>
                                        @foreach($Page->extras['tabela_precos'] as $tabela_preco)
                                            <option value="{{$tabela_preco->idtabela_preco}}"
                                                    @if(old('idtabela_preco_comercial') == $tabela_preco->idtabela_preco) selected @endif>{{$tabela_preco->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo de Emissão
                                    Faturamento<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="idemissao_comercial" class="form-control" required>
                                        <option value="">Escolha um Tipo</option>
                                        @foreach($Page->extras['tipos_emissao_faturamento'] as $sel)
                                            <option value="{{$sel->id}}"
                                                    @if(old('idemissao_comercial') == $sel->id) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Forma de Pagamento<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="idforma_pagamento_comercial" class="form-control" required>
                                        <option value="">Escolha a Forma</option>
                                        @foreach($Page->extras['formas_pagamentos'] as $sel)
                                            <option value="{{$sel->idforma_pagamento}}"
                                                    @if(old('idforma_pagamento_comercial') == $sel->idforma_pagamento) selected @endif>{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Prazo de Pagamento<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select name="prazo_pagamento_comercial" class="form-control" required>
                                        <option value="">Escolha o Prazo</option>
                                        <option value="0" @if(old('prazo_pagamento_comercial') == 0) selected @endif>À
                                            VISTA
                                        </option>
                                        <option value="1" @if(old('prazo_pagamento_comercial') == 1) selected @endif>
                                            PARCELADO
                                        </option>
                                    </select>
                                </div>
                                <div class="parcelas esconda">
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="addParcela(this)"><i
                                                    class="fa fa-plus"></i> Parcela</a>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="remParcela(this)"><i
                                                    class="fa fa-minus"></i> Parcela</a>
                                    </div>
                                </div>
                            </div>
                            <div class="parcelas esconda">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">1ª Parcela</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" class="form-control show-parcelas"
                                               name="parcela_comercial[0]">
                                    </div>
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

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>

    <script>

        //AJUSTA LAYOUT CENTRO CUSTO
        $(document).ready(function() {
            $("input[name=centro_custo]").on('ifChecked', function(event){
                $('select[name=idcliente_centro_custo]').parent('div').toggle();
                $('input[name=limite_credito_tecnica]').parent('div').toggle();
                if(this.value){
                    $('select[name=idcliente_centro_custo]').attr('required', true);
                    $('input[name=limite_credito_tecnica]').attr('required', false);
                } else {
                    $('select[name=idcliente_centro_custo]').attr('required', false);
                    $('input[name=limite_credito_tecnica]').attr('required', true);
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
            @if(old("tipo_cliente")==1)
                $("select[name=tipo_cliente]").val(1).trigger("change");
            @endif
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

        //AJUSTA PARCELAS
        var N_PARCELA = 0;
        function addParcela($this) {
            N_PARCELA++;
            $parent = $($this).parents('div.form-group').next();
            var html = '<div class="form-group">' +
                '<label class="control-label col-md-2 col-sm-2 col-xs-12">' + (N_PARCELA + 1) + 'ª Parcela</label>' +
                '<div class="col-md-4 col-sm-4 col-xs-12">' +
                '<input type="text" class="form-control show-parcelas" name="parcela_tecnica[' + N_PARCELA + ']">' +
                '</div>' +
                '</div>';
            $($parent).append(html);
            initMaskMoneyParcelas($($parent).find('input[name="parcela_tecnica[' + N_PARCELA + ']"]'));
        }
        function remParcela($this) {
            $parent = $($this).parents('div.form-group').next();
            if ($($parent).find('div.form-group').length > 1) {
                N_PARCELA--;
                $($parent).children().last().remove();
            }
        }

        $(document).ready(function () {
            $("select[name=prazo_pagamento_tecnica]").change(function () {
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

        $("div#parcelasModal").on("show.bs.modal", function () {
            $parent = $(this).find('div.modal-body');
            var html = '<div class="form-horizontal form-label-left">' +
                '<div class="form-group">' +
                '<label class="control-label col-md-2 col-sm-2 col-xs-12">1ª</label>' +
                '<div class="col-md-10 col-sm-10 col-xs-12">' +
                '<input type="text" class="form-control show-parcelas" name="parcela[1]">' +
                '</div>' +
                '</div>' +
                '</div>';
            $($parent).html(html);

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