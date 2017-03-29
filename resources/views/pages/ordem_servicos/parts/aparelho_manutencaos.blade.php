<?php
$selo_afixado = NULL;
if($Instrumento->has_selo_instrumentos()){
    $selo_afixado = $Instrumento->selo_afixado();
}
$lacres_atual = NULL;
if($Instrumento->has_lacres_instrumentos()){
    $lacres_atual_aux = $Instrumento->lacres_afixados;
    foreach($lacres_atual_aux as $lacre){
        $lacres_atual[] = [
                'id' => $lacre->lacre->idlacre,
                'text' => $lacre->lacre->numeracao
        ];
    }
}
?>
<div class="x_panel">
    <div class="x_title">
        <h2>
            Instrumento
            <small>#{{$Instrumento->idinstrumento}}</small>
            <a class="btn btn-danger btn-xs"
               data-nome="Instrumento: #{{$Instrumento->idinstrumento}}"
               data-href="{{route('ordem_servicos.instrumentos.remove',$AparelhoManutencao->idaparelho_manutencao)}}"
               data-toggle="modal"
               data-target="#modalDelecao">
                <i class="fa fa-times fa-xs"></i> Cancelar</a>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <section class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="profile_img">
                    <img class="img-responsive avatar-view" src="{{$Instrumento->getFoto()}}">
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                @include('pages.ordem_servicos.parts.instrumento_descricao')
            </div>
        </section>
        <div class="ln_solid"></div>
        <section class="row animated fadeInDown">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Defeito:</h2>
                <p>{{$AparelhoManutencao->defeito}}</p>
                <h2>Selo Afixado:</h2>
                <p class="green">{{$Instrumento->selo_afixado_numeracao() }}</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Solução:</h2>
                <p>{{$AparelhoManutencao->solucao}}</p>
                <h2>Lacres Afixados:</h2>
                <p class="green">{{$Instrumento->lacres_afixados_valores()}}</p>
            </div>
        </section>
        <div class="ln_solid"></div>

        {{--DESCRIÇÃO--}}
        @if(($AparelhoManutencao->defeito == NULL) && ($AparelhoManutencao->solucao == NULL))
            <section class="row">
                {!! Form::open(['route' => ['aparelho_manutencao.update',$AparelhoManutencao->idaparelho_manutencao],
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <div class="form-group">
                        <div class="checkbox col-md-2 col-sm-2 col-xs-12">
                            <label>
                                <input name="lacre_rompido" type="checkbox" class="flat" checked="checked"> Lacre
                                rompido?
                            </label>
                        </div>
                    @if($selo_afixado!=NULL)
                        <div class="checkbox col-md-2 col-sm-2 col-xs-12">
                                <label>
                                    <input name="selo_outro" type="checkbox" class="flat"> Outro Selo?
                                </label>
                            </div>
                    @else
                        <input name="selo_outro" type="hidden" value="1">
                    @endif

                    @if($lacres_atual!=NULL)
                        <div class="form-group col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input name="lacre_outro" type="checkbox" class="flat"> Outros Lacres?
                                </label>
                            </div>
                            </div>
                    @else
                        <input name="lacre_outro" type="hidden" value="1">
                    @endif
                    </div>
                <section id="selolacre">
                    <div id="selos-container">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Selo retirado: <span
                                        class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" name="selo_retirado" class="form-control" placeholder="Selo retirado"
                                       @if($selo_afixado!=NULL) value="{{$selo_afixado->numeracao}}" disabled
                                       @else required @endif />
                                @if($selo_afixado!=NULL)
                                    <input type="hidden" name="selo_retirado_hidden"
                                           value="{{$selo_afixado->numeracao}}"/>
                                    @endif
                                </div>
                            </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Selo afixado: <span
                                        class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <select name="selo_afixado" class="select2_single-ajax form-control" tabindex="-1"
                                        placeholder="Selo afixados" required></select>
                            </div>
                            <div id="element"></div>
                        </div>
                        </div>
                    <div id="lacres-container">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Lacres retirados: <span
                                        class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <div>
                                    @if($lacres_atual!=NULL)
                                        <select name="lacre_retirado[]" class="select2_multiple-ajax form-control"
                                                tabindex="-1" multiple="multiple" required></select>
                                        <script>
                                            $(document).ready(function () {
                                                var data = JSON.parse('<?php echo json_encode($lacres_atual);?>');
                                                var ids_data = $(data).map(function () {
                                                    return this.id;
                                                }).get();
                                                $("select[name='lacre_retirado[]']").select2({data: data});
                                                $("select[name='lacre_retirado[]']").val(ids_data).trigger("change");
                                            });
                                        </script>
                                        <input type="hidden" name="lacres_retirado_hidden"
                                               value="{{json_encode($lacres_atual)}}"/>
                                    @endif
                                </div>
                                <input type="text" name="lacre_retirado_livre" class="form-control"
                                       placeholder="Outros Lacres separados por ';' Ex: 1001; 1002; 1003"
                                       @if($lacres_atual==NULL) required @else style="display:none;" @endif/>
                                </div>
                            </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Lacres afixados: <span
                                        class="required">*</span></label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <div>
                                    <select name="lacre_afixado[]" class="select2_multiple-ajax form-control"
                                            tabindex="-1" multiple="multiple" required></select>
                                </div>
                            </div>
                        </div>
                        </div>
                </section>
                <div class="form-group col-md-6">
                    <label>Defeitos encontrados: <span class="required">*</span></label>
                    <textarea name="defeito" class="form-control" rows="3"
                              required>{{$AparelhoManutencao->defeito}}</textarea>
                    </div>
                <div class="form-group col-md-6">
                    <label>Solução: <span class="required">*</span></label>
                    <textarea name="solucao" class="form-control" rows="3"
                              required>{{$AparelhoManutencao->solucao}}</textarea>
                </div>
                    <div class="clearfix"></div>
                <div class="form-group">
                    <button class="btn btn-success btn-lg pull-right"><i class="fa fa-check fa-lg"></i> Salvar</button>
                    </div>
                {!! Form::close() !!}
            </section>
        @else

            {!! Form::open(['route' => ['ordem_servicos.add_insumos',$OrdemServico->idordem_servico ],
                'method' => 'POST',
                'files' => true,
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
            <input name="idaparelho_manutencao" type="hidden" value="{{$AparelhoManutencao->idaparelho_manutencao}}">

            {{--SERVIÇOS--}}
            @if(count($Servicos)>0)
                <section class="row">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Serviços</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                <table border="0" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th width="40%">Nome</th>
                                        <th>Preço</th>
                                        <th>Preço Mínimo</th>
                                        <th>Valor Cobrado</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($AparelhoManutencao->servico_prestados as $servico_prestado)
                                        <?php
                                        $tabela_preco = $servico_prestado->servico->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        ?>
                                        <tr>
                                            <td>
                                                {{$servico_prestado->servico->nome}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco_minimo}}
                                            </td>
                                            <td>
                                                R$ {{$servico_prestado->valor}}
                                            </td>
                                            <td>
                                                <a class="btn btn-danger"
                                                   data-nome="{{$servico_prestado->servico->nome}}"
                                                   data-href="{{route('servico_prestados.destroy',$servico_prestado->idservico_prestado)}}"
                                                   data-toggle="modal"
                                                   data-target="#modalRemocao"
                                                   data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                                    <i class="fa fa-trash fa-lg"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <select class="select2_single form-control" name="idservico" id="idservico"
                                                    tabindex="-1">
                                                <option value="">Selecione</option>
                                                @foreach($Servicos as $servico)
                                                    <?php
                                                    $tabela_preco = $servico->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                                    //                                                        print_r($tabela_preco);
                                                    ?>
                                                    {{--<option>{{$tabela_preco}}</option>--}}
                                                    <option value="{{$servico->idservico}}"
                                                            data-preco="{{$tabela_preco->preco}}"
                                                            data-preco_minimo="{{$tabela_preco->preco_minimo}}">{{$servico->nome}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            <input name="valor" id="valor" type="text" class="form-control"
                                                   placeholder="Valor">
                                        </td>
                                        <td>
                                            <a class="btn btn-success add"
                                               data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                                <i class="fa fa-check fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                <div class="jumbotron">
                    <h2>Nenhum Serviço cadastrado no sistema!</h2>
                    </div>
            @endif
            {{--{{dd(1)}}--}}
            {{--PEÇAS/PRODUTOS--}}
            {{--@include('pages.ordem_servicos.insumos.pecas')--}}
            @if(count($Pecas)>0)
                <section class="row">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Peças/Produtos</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                <table border="0" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th width="40%">Nome</th>
                                        <th>Preço</th>
                                        <th>Preço Mínimo</th>
                                        <th>Valor Cobrado</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($AparelhoManutencao->pecas_utilizadas as $peca_utilizada)
                                        <?php
                                        $tabela_preco = $peca_utilizada->peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        ?>
                                        <tr>
                                            <td>
                                                {{$peca_utilizada->peca->descricao}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco_minimo}}
                                            </td>
                                            <td>
                                                {{--<input name="valor" type="hidden" class="form-control"--}}
                                                {{--placeholder="Valor"--}}
                                                {{--value="{{$tabela_preco->preco_minimo}}" required>--}}
                                                R$ {{$peca_utilizada->valor}}
                                            </td>
                                            <td>
                                                <a class="btn btn-danger"
                                                   data-nome="{{$peca_utilizada->nome}}"
                                                   data-href="{{route('pecas_utilizadas.destroy',$peca_utilizada->id)}}"
                                                   data-toggle="modal"
                                                   data-target="#modalRemocao"
                                                   data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                                    <i class="fa fa-trash fa-lg"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <select class="select2_single form-control" id="idpeca" name="idpeca"
                                                    tabindex="-1">
                                                <option value="">Selecione</option>
                                                @foreach($Pecas as $peca)
                                                    <?php
                                                    $tabela_preco = $peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                                    ?>
                                                    <option value="{{$peca->idpeca}}"
                                                            data-preco="{{$tabela_preco->preco}}"
                                                            data-preco_minimo="{{$tabela_preco->preco_minimo}}">
                                                        {{$peca->descricao}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            <input name="valor" id="valor" type="text" class="form-control"
                                                   placeholder="Valor">
                                        </td>
                                        <td>
                                            <a class="btn btn-success add"
                                               data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                                <i class="fa fa-check fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                <div class="jumbotron">
                    <h2>Nenhuma Peça cadastrada no sistema!</h2>
                </div>
            @endif

            {{--KITS--}}
            @if(count($Kits)>0)
                <section class="row">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Kits</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                                <table border="0" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th width="40%">Nome</th>
                                        <th>Preço</th>
                                        <th>Preço Mínimo</th>
                                        <th>Valor Cobrado</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($AparelhoManutencao->kits_utilizados as $kit_utilizado)
                                        <?php
                                        $tabela_preco = $kit_utilizado->kit->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                        ?>
                                        <tr>
                                            <td>
                                                {{$kit_utilizado->nome()}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco}}
                                            </td>
                                            <td>
                                                R$ {{$tabela_preco->preco_minimo}}
                                            </td>
                                            <td>
                                                R$ {{$kit_utilizado->valor_original()}}
                                            </td>
                                            <td>
                                                {{--<a class="btn btn-default"--}}
                                                {{--data-toggle-tooltip="tooltip" data-placement="top" title="Ver detalhes"--}}
                                                {{--><i class="fa fa-eye fa-lg"></i></a>--}}
                                                {{--<a class="btn btn-primary"--}}
                                                {{--data-toggle-tooltip="tooltip" data-placement="top" title="Editar">--}}
                                                {{--<i class="fa fa-edit fa-lg"></i></a>--}}
                                                <a class="btn btn-danger"
                                                   data-nome="{{$kit_utilizado->nome()}}"
                                                   data-href="{{route('kits_utilizados.destroy',$kit_utilizado->id)}}"
                                                   data-toggle="modal"
                                                   data-target="#modalRemocao"
                                                   data-toggle-tooltip="tooltip" data-placement="top" title="Excluir">
                                                    <i class="fa fa-trash fa-lg"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <select class="select2_single form-control" id="idkit" name="idkit"
                                                    tabindex="-1">
                                                <option value="">Selecione</option>
                                                @foreach($Kits as $kit)
                                                    <?php
                                                    $tabela_preco = $peca->tabela_cliente($OrdemServico->cliente->idtabela_preco_tecnica);
                                                    ?>
                                                    <option value="{{$kit->idkit}}"
                                                            data-preco="{{$tabela_preco->preco}}"
                                                            data-preco_minimo="{{$tabela_preco->preco_minimo}}">{{$kit->descricao}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            <input name="valor" id="valor" type="text" class="form-control"
                                                   placeholder="Valor">
                                        </td>
                                        <td>
                                            {{--<a class="btn btn-default"--}}
                                            {{--data-toggle-tooltip="tooltip" data-placement="top" title="Ver detalhes"--}}
                                            {{--><i class="fa fa-eye fa-lg"></i></a>--}}
                                            <a class="btn btn-success add"
                                               data-toggle-tooltip="tooltip" data-placement="top" title="Salvar">
                                                <i class="fa fa-check fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                <div class="jumbotron">
                    <h2>Nenhum Kit cadastrado no sistema!</h2>
                </div>
            @endif

            {{--FECHAR--}}
            <section class="row">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-xs-12 pull-right ">
                            <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                        </div>
                    </div>
                    </div>
            </section>
            {!! Form::close() !!}

        @endif

    </div>
</div>