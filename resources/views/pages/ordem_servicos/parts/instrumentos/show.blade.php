<?php
$selo_afixado = NULL;
if ( $Instrumento->has_selo_instrumentos_fixado() ) {
    $selo_afixado = $Instrumento->selo_afixado();
}
$lacres_atual = NULL;
//if ($Instrumento->has_lacres_instrumentos()) {
//    $lacres_atual_aux = $Instrumento->lacres_afixados;
//    foreach ($lacres_atual_aux as $lacre) {
//        $lacres_atual[] = [
//            'id' => $lacre->lacre->idlacre,
//            'text' => $lacre->lacre->numeracao
//        ];
//    }
//}
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
                <i class="fa fa-times fa-xs"></i> Remover</a>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @include('pages.ordem_servicos.parts.instrumentos.selo_lacres')
        <div class="ln_solid"></div>
        @if(($AparelhoManutencao->defeito != NULL) && ($AparelhoManutencao->solucao != NULL))
            @include('pages.ordem_servicos.insumos.adicionar')
        @else
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
                <div class="form-group">
                    @if($AparelhoManutencao->numero_chamado != NULL)
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Número do chamado: </label>
                            <div class="col-md-10 col-sm-10 col-xs-10">
                                <input type="text" class="form-control"
                                       value="{{$AparelhoManutencao->numero_chamado}}" disabled/>
                            </div>
                        </div>
                    @endif
                </div>
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
        @endif
    </div>
</div>