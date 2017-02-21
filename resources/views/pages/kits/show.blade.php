@extends('layouts.template')

@section('modals_content')
    @include('layouts.modals.padrao')
@endsection

@section('style_content')
    {!! Html::style('vendors/pnotify/dist/pnotify.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.buttons.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.nonblock.css') !!}
@endsection
@section('page_content')

    <section class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="div_pai">
            {!! Form::open(['method' => 'PATCH',
                'route'=>[$Page->link.'.update',$Kit->idkit],
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <section class="row">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Dados do {{$Page->Target}}</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="form-horizontal form-label-left">
                                @include('pages.'.$Page->link.'.forms.form')
                            </div>
                        </div>
                    </div>
                </section>
                <section class="row" id="pecas">
                    @include('pages.kits.forms.pecaskit')
                </section>
            <section class="row">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Valores</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Preço Total: <span
                                            class="required">*</span></label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input name="valor" id="valor-ref" type="text" maxlength="100"
                                           class="form-control show-valor"
                                           disabled
                                           value="{{(isset($Kit->nome))?$Kit->valor_total():''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        @include('pages.kits.forms.tabela_preco')
                    </div>
                </section>
            <section class="row">
                    <div class="form-horizontal form-label-left">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                <a href="{{route('kits.index')}}" class="btn btn-danger btn-lg btn-block">Cancelar</a>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                            </div>
                        </div>
                    </div>
                </section>
            {{ Form::close() }}
        </div>
    </section>
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
    {!! Html::script('vendors/pnotify/dist/pnotify.js') !!}
    {!! Html::script('vendors/pnotify/dist/pnotify.buttons.js') !!}
    {!! Html::script('vendors/pnotify/dist/pnotify.nonblock.js') !!}

    <script>
        var ind_peca_kit = parseInt('{{$Kit->pecas_kit->count()}}');
        var totalpecas = parseInt('{{$Kit->pecas_kit->count()}}');
        var nome_item_remover_ = '';
        var href_item_remover_ = '';
        var $div_item = '';
        $modal = $('div#modalPadrao');
        $modal_content = $($modal).find('div.modal-content');

        function get_unidade($this){
            $select = $($this);
            unidade = $($select).find(':selected').data('unidade');
            $parent = $($select).parents('div.form-group').next().next();
            $($parent).find('input#unidade').val(unidade.codigo);
        }

        function remove_peca($this){
            if(typeof $($this).data('href') == 'undefined'){
                $parent = $($this).parents('div#kit_peca');
                $($parent).remove();
                return false;
            } else {
                $div_item = $this;
                $($modal_content).addClass("panel-danger");
                //título
                $($modal_content).find('.modal-header').html("Confirmar exclusão");
                $($modal_content).find('.modal-header').addClass("modal-danger");

                nome_item_remover_ = $($div_item).data('nome');
                href_item_remover_ = $($div_item).data('href');
                $($modal_content).find('.modal-body').html('Você realmente deseja remover <strong>'+nome_item_remover_ + '</strong> e suas relações? Esta ação é irreversível!');

                //botão
                $($modal_content).find('.modal-footer .btn-ok').html("Remover");
                $($modal_content).find('.modal-footer .btn-ok').addClass("btn-danger");
                $($modal).modal('show');
            }

        }
        //modal_remoção
        $($modal_content).find('.btn-ok').click(function(){
            $.ajax({
                url: href_item_remover_,
                type: 'post',
                data: {"_token": "{{ csrf_token() }}"},
                dataType: "json",

                beforeSend: function () {
                    $(".loading-page").show();
                },
                complete: function (xhr, textStatus) {
                    $(".loading-page").hide();
                },
                error: function (xhr, textStatus) {
                    console.log('xhr-error: ' + xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    if(json.status == 1){

                        $($modal).modal('hide');
                        $parent = $($div_item).parents('div#kit_peca');
                        $($parent).remove();
                        new PNotify({
                            title: 'Sucesso!',
                            text: json.response,
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    } else {
                        new PNotify({
                            title: 'Erro!',
                            text: json.response,
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });

        });

        function calc_total($this){
            $parent = $($this).parents('div.form-group');
            $valor_total = $($parent).find('input#vlr_total');
            qtd = $($parent).find('input#qtd').val();
            vlr = $($parent).find('input#vlr').maskMoney('unmasked')[0];
            custo_final = qtd * vlr;
            $($valor_total).maskMoney('mask', custo_final);
            soma_tudo();
        }
        function soma_tudo() {
            $parent = $('div#kit_peca');
            $inputs_valor = $($parent).find('input[name^=valor_total]');
            var valor = 0;
            $($inputs_valor).each(function (i, v) {
                valor += $(v).maskMoney('unmasked')[0];
            })
            $('input#valor-ref').maskMoney('mask', custo_final);
        }

        $(document).ready(function(){
            $('input#valor-ref').maskMoney('mask');
            $("section#pecas").find('button#add').click(function(){
                console.log(ind_peca_kit);
                $parent = $(this).parents('div.form-group').siblings('div#pecas_add');
                html = '<div id="kit_peca">' +
                        '<div class="form-group">' +
                        '<label class="control-label col-md-2 col-sm-2 col-xs-12">Peça/Produto: <span class="required">*</span></label>' +
                        '<div class="col-md-8 col-sm-8 col-xs-12">' +
                        '<select name="idpeca[' + ind_peca_kit + ']" onchange="get_unidade(this)" class="form-control" required>' +
                        '<option value="">Selecione a Peça</option>';
                @foreach($Page->extras['pecas'] as $sel)
                        html += '<option data-unidade="{{$sel->unidade}}" value="{{$sel->idpeca}}">{{$sel->descricao}}</option>';
                @endforeach
                        html += '</select>' +
                        '</div>' +
                        '<div class="col-md-2 col-sm-2 col-xs-12">' +
                        '<button class="btn btn-danger btn-sm btn-block" onclick="remove_peca(this)"><i class="fa fa-times-circle"></i> Remover</button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição Adicional: </label>' +
                        '<div class="col-md-10 col-sm-10 col-xs-12">' +
                        '<input name="descricao_adicional[' + ind_peca_kit + ']" type="text" class="form-control" placeholder="Descrição Adicional">' +
                        '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade: <span class="required">*</span></label>' +
                        '<div class="col-md-4 col-sm-4 col-xs-12">' +
                        '<input id="unidade" type="text" class="form-control" disabled>' +
                        '</div>' +
                        '<div class="col-md-2 col-sm-2 col-xs-12">' +
                        '<input id="qtd" onchange="calc_total(this)" name="quantidade[' + ind_peca_kit + ']" type="number" class="form-control" placeholder="Quantidade" required>' +
                        '</div>' +
                        '<div class="col-md-2 col-sm-2 col-xs-12">' +
                        '<input id="vlr" onchange="calc_total(this)" name="valor_unidade[' + ind_peca_kit + ']" type="text" class="form-control show-valor" placeholder="Valor" required>' +
                        '</div>' +
                        '<div class="col-md-2 col-sm-2 col-xs-12">' +
                        '<input id="vlr_total" name="valor_total[' + ind_peca_kit + ']" type="text" class="form-control show-valor" placeholder="Valor Total" disabled>' +
                        '</div>' +
                        '</div>' +
                        '<div class="ln_solid"></div>' +
                        '</div>';
                $($parent).append(html);
                initMaskMoney($("input[name='valor_unidade["+ind_peca_kit+"]']"));
                initMaskMoney($("input[name='valor_total["+ind_peca_kit+"]']"));
                var y = $(window).scrollTop();  //your current y position on the page
                $(window).scrollTop(y+150);
                ind_peca_kit++;
                return false;
            });
        })
    </script>
@endsection