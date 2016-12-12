{!! Form::model($Cliente, ['method' => 'PATCH','route'=>[$Page->link.'.update',$Cliente->idcliente],
    'files' => true, 'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Centro de Custo <span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="radio" name="centro_custo" value="0" class="flat" @if(!$Cliente->centro_custo) checked="checked" @endif required> Não
                                        <input type="radio" name="centro_custo" value="1" class="flat" @if($Cliente->centro_custo) checked="checked" @endif required> Sim
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" @if(!$Cliente->centro_custo) style="display:none;" @endif>
                                <select name="idcliente_centro_custo"  class="form-control">
                                    <option value="">Centro de Custo</option>
                                    @foreach($Page->extras['centro_custo'] as $centro_custo)
                                        <option value="{{$centro_custo->idcliente}}"
                                                @if($Cliente->idcliente_centro_custo == $centro_custo->idcliente) selected @endif>{{$centro_custo->getType()->nome_principal}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" @if($Cliente->centro_custo) style="display:none;" @endif>
                                <input value="{{$Cliente->limite_credito}}" type="text" class="form-control show-dinheiro" name="limite_credito" placeholder="Limite de Crédito" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome do Responsável<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{$Cliente->nome_responsavel}}" type="text" class="form-control" name="nome_responsavel" placeholder="Nome" >
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Segmento<span class="required">*</span></label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select name="idsegmento" class="form-control" required>
                                    <option value="">Escolha o Segmento</option>
                                    @foreach($Page->extras['segmentos'] as $segmento)
                                        <option value="{{$segmento->idsegmento}}"
                                            @if($Cliente->idsegmento == $segmento->idsegmento) selected @endif>{{$segmento->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Orçamento<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="{{$Cliente->email_orcamento}}" type="text" class="form-control" name="email_orcamento" placeholder="Email" required>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Nota<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="{{$Cliente->email_nota}}" type="text" class="form-control" name="email_nota" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Tabela de Preço<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select name="idtabela_preco" class="form-control" required>
                                    <option value="">Escolha a Tabela</option>
                                    @foreach($Page->extras['tabela_precos'] as $tabela_preco)
                                        <option value="{{$tabela_preco->idtabela_preco}}"
                                            @if($Cliente->idtabela_preco == $tabela_preco->idtabela_preco) selected @endif>{{$tabela_preco->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Forma de Pagamento<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select name="idforma_pagamento" class="form-control" required>
                                    <option value="">Escolha a Forma</option>
                                    @foreach($Page->extras['formas_pagamentos'] as $sel)
                                        <option value="{{$sel->idforma_pagamento}}"
                                                @if($Cliente->idforma_pagamento == $sel->idforma_pagamento) selected @endif>{{$sel->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Região </label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <select name="idregiao" class="form-control" required>
                                    <option value="">Região</option>
                                    @foreach($Page->extras['regioes'] as $sel)
                                        <option value="{{$sel->idregiao}}"
                                                @if($Cliente->idregiao == $sel->idregiao) selected @endif>{{$sel->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Foto<span class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input value="{{old('foto')}}" type="file" class="form-control" name="foto">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Distância (Km)<span class="required">*</span></label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input value="{{$Cliente->distancia}}" type="text" class="form-control" name="distancia" placeholder="Distância (Km)" required>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Custo em Pedágios (R$)<span class="required">*</span></label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input value="{{$Cliente->pedagios}}" type="text" class="form-control show-dinheiro"
                                       name="pedagios" placeholder="Custo Pedágios">
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Outros Custos (R$)<span class="required">*</span></label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input value="{{$Cliente->outros_custos}}" type="text"
                                       class="form-control show-dinheiro" name="outros_custos"
                                       placeholder="Outros Custos">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php $existe_entidade = 1; $Entidade=$Cliente; ?>
    @if($Cliente->getType()->tipo_cliente)
        <section class="row" id="form_pjuridica">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_pjuridica')
                </div>
            </div>
        </section>
    @else
        <section class="row" id="form_pfisica">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_pfisica')
                </div>
            </div>
        </section>
    @endif

    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                @include('pages.forms.form_contato')
            </div>
        </div>
    </section>
    @if(Auth::user()->hasRole('admin'))
        <section class="row">
            <div class="form-group ">
                <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                </div>
            </div>
        </section>
    @endif
{{ Form::close() }}
