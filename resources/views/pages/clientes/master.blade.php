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
                                    <input value="{{old('distancia')}}" type="text" class="form-control"
                                           name="distancia" placeholder="Distância (Km)">
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Custo em Pedágios (R$)<span class="required">*</span></label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input value="{{old('pedagios')}}" type="text" class="form-control show-dinheiro"
                                           name="pedagios" placeholder="Custo Pedágios">
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Outros Custos (R$)</label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input value="{{old('outros_custos')}}" type="text"
                                           class="form-control show-dinheiro" name="outros_custos"
                                           placeholder="Outros Custos">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Número Chamado<span
                                            class="required">*</span></label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="radio" name="numero_chamado" value="0" class="flat"
                                                   checked="checked" required> Não
                                            <input type="radio" name="numero_chamado" value="1" class="flat" required>
                                            Sim
                                        </label>
                                    </div>
                                </div>
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
                            </div>
                            <div class="form-group">
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
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Forma de Pagamento<span
                                            class="required">*</span></label>
                                <div class="col-md-5 col-sm-5 col-xs-12">
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
                                        <a class="btn btn-block btn-default" onclick="addParcela(this,'tecnica')"><i
                                                    class="fa fa-plus"></i> Parcela</a>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="remParcela(this,'tecnica')"><i
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
                                        <a class="btn btn-block btn-default" onclick="addParcela(this,'comercial')"><i
                                                    class="fa fa-plus"></i> Parcela</a>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a class="btn btn-block btn-default" onclick="remParcela(this,'comercial')"><i
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

    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>

    <!-- Clientes.js -->
    @include('pages.clientes.scripts.js')
@endsection