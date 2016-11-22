<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Conteúdo do {{$Page->Target}}</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="form-horizontal form-label-left">
                @foreach($Kit->pecas_kit as $ind => $peca_kit)
                    <div id="kit_peca">
                        <input name="idpecas_kit[{{$ind}}]" type="hidden" class="form-control"
                               value="{{$peca_kit->idpeca_kit}}">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Peça/Produto: <span
                                        class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select name="idpeca[{{$ind}}]" onchange="get_unidade(this)" class="form-control"
                                        required>
                                    <option value="">Selecione a Peça</option>
                                    @foreach($Page->extras['pecas'] as $sel)
                                        <option data-unidade="{{$sel->unidade}}" value="{{$sel->idpeca}}"
                                                @if($peca_kit->idpeca == $sel->idpeca) selected @endif
                                        >{{$sel->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a onclick="remove_peca(this)"
                                   data-nome="{{$peca_kit->peca->descricao}}"
                                   data-href="{{route('kits.pecakit.destroy',$peca_kit->idpeca_kit)}}"
                                   class="btn btn-danger btn-sm btn-block"><i class="fa fa-times-circle"></i>
                                    Remover</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição Adicional: </label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input name="descricao_adicional[{{$ind}}]" type="text" class="form-control"
                                       placeholder="Descrição Adicional"
                                       value="{{$peca_kit->descricao_adicional}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade: <span
                                        class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input id="unidade" type="text" class="form-control" disabled
                                       value="{{$peca_kit->peca->unidade->codigo}}">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input id="qtd" onchange="calc_total(this)" name="quantidade[{{$ind}}]" type="number"
                                       class="form-control" placeholder="Quantidade" required
                                       value="{{$peca_kit->quantidade}}">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input id="vlr" onchange="calc_total(this)" name="valor_unidade[{{$ind}}]" type="text"
                                       class="form-control show-valor" placeholder="Valor" required
                                       value="{{$peca_kit->valor_unidade}}">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input id="vlr_total" name="valor_total" type="text" class="form-control show-valor"
                                       placeholder="Valor Total" disabled
                                       value="{{$peca_kit->valor_total}}">
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                    </div>
                @endforeach
                <div id="pecas_add"></div>
                <div class="form-group">
                    <div class="col-md-offset-8 col-md-4 col-sm-offset-8 col-sm-4 col-xs-12">
                        <button class="btn btn-primary btn-block" id="add"><i class="fa fa-plus-circle"></i> Adicionar
                            Peça/Produto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>