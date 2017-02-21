@extends('layouts.template')
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

        {!! Form::open(['route' => $Page->link.'.store',
            'method' => 'POST',
            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Dados do {{$Page->Target}}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content div_pai">
                        <div class="form-horizontal form-label-left">
                            @include('pages.'.$Page->link.'.forms.form')
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="row" id="pecas">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Conteúdo do {{$Page->Target}}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Peça/Produto: <span class="required">*</span></label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <select name="idpeca[0]" onchange="get_data(this)" class="form-control" required>
                                        <option value="">Selecione a Peça</option>
                                        @foreach($Page->extras['pecas'] as $sel)
                                            <option data-valor="{{$sel->peca_tributacao->custo_final}}"
                                                    data-unidade="{{$sel->unidade}}"
                                                    value="{{$sel->idpeca}}">{{$sel->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição Adicional: </label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <input name="descricao_adicional[0]" type="text" class="form-control" placeholder="Descrição Adicional">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade: <span class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input id="unidade" type="text" class="form-control" disabled>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input id="qtd" onchange="calc_total(this)" name="quantidade[0]" type="number" class="form-control" placeholder="Quantidade" required>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input id="vlr" onchange="calc_total(this)" name="valor_unidade[0]" type="text" class="form-control show-valor" placeholder="Valor" required>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input id="vlr_total" name="valor_total" type="text" class="form-control show-valor" placeholder="Valor Total" disabled>
                                </div>
                            </div>
                            <div id="pecas_add"></div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-offset-8 col-md-4 col-sm-offset-8 col-sm-4 col-xs-12">
                                    <button class="btn btn-primary btn-block" id="add"><i class="fa fa-plus-circle"></i> Adicionar Peça/Produto</button>
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
    <!-- /page content -->

@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}

    <script>
        var ind_peca_kit = 1;


        function get_data($this) {
            $select = $($this);
            var unidade = $($select).find(':selected').data('unidade');
            var valor = $($select).find(':selected').data('valor');

            $parent = $($select).parents('div.form-group').next().next();
            $($parent).find('input#qtd').val(1);
            $($parent).find('input#unidade').val(unidade.codigo)
            $($parent).find('input#vlr').val(valor);
            $($parent).find('input#vlr_total').val(valor);
        }

        function remove_peca($this){
            $parent = $($this).parents('div#kit_peca');
            $($parent).remove();
            console.log(ind_peca_kit);
            return false;
        }

        function calc_total($this){
            $parent = $('section#pecas').parents('div.form-group');
            $valor_total = $($parent).find('input#vlr_total');
            qtd = $($parent).find('input#qtd').val();
            vlr = $($parent).find('input#vlr').maskMoney('unmasked');
            custo_final = qtd * vlr[0];
            $($valor_total).maskMoney('mask', custo_final);
        }
        $(document).ready(function(){
            $("section#pecas").find('button#add').click(function(){
                console.log(ind_peca_kit);
                $parent = $(this).parents('div.form-group').siblings('div#pecas_add');
                html = '<div id="kit_peca">' +
                            '<div class="ln_solid"></div>' +
                            '<div class="form-group">' +
                                '<label class="control-label col-md-2 col-sm-2 col-xs-12">Peça/Produto: <span class="required">*</span></label>' +
                                '<div class="col-md-8 col-sm-8 col-xs-12">' +
                    '<select name="idpeca[' + ind_peca_kit + ']" onchange="get_data(this)" class="form-control" required>' +
                                        '<option value="">Selecione a Peça</option>';
                @foreach($Page->extras['pecas'] as $sel)
                    html += '<option data-valor="{{$sel->peca_tributacao->custo_final}}"  data-unidade="{{$sel->unidade}}" value="{{$sel->idpeca}}">{{$sel->descricao}}</option>';
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