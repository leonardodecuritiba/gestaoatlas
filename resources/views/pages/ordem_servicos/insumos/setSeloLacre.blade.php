<?php
$selos_retirados = $Instrumento->numeracao_selo_afixado();
$lacres_retirados = $Instrumento->numeracao_lacres_afixados();
//dd($lacres_retirados)
?>
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

        @if($selos_retirados['id']!=null)
            <div class="checkbox col-md-2 col-sm-2 col-xs-12">
                <label>
                    <input name="selo_outro" type="checkbox" class="flat"> Outro Selo?
                </label>
            </div>
        @else
            <input name="selo_outro" type="hidden" value="1">
        @endif

        @if($lacres_retirados['id']!=null)
            <div class="checkbox col-md-2 col-sm-2 col-xs-12">
                <label>
                    <input name="lacre_outro" type="checkbox" class="flat"> Outros Lacres?
                </label>
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
                    <div class="alert-label alert alert-danger fade in esconda"
                         role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Este campo de preenchimento obrigatório. A inclusão de numerações fictícias, fará com que o técnico ou técnico franquiado este sujeito a multas severas. Caso o selo não se faça presente entrar com 8 dígitos sequencias “12345678”, o mesmo será verificado posteriormente no portal PSI.
                    </div>
                    <input type="text" name="selo_retirado" class="form-control show-selo" placeholder="Selo retirado"
                        @if($selos_retirados['id']!=null)
                           data-selo_id="{{json_encode($selos_retirados['id'])}}"
                           data-selo_text="{{$selos_retirados['text']}}"
                           value="{{$selos_retirados['text']}}" disabled
                        @else  data-selo="" data-selo_text="" required @endif />
                    @if($selos_retirados['id']!=null)
                        <input type="hidden" name="selo_retirado_hidden"
                               value="{{json_encode($selos_retirados['id'])}}"/>
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
                        @if($lacres_retirados['list']!=null)
                            <select name="lacre_retirado[]" class="select2_multiple-ajax form-control"
                                    tabindex="-1" multiple="multiple" required></select>
                            <script>
                                $(document).ready(function () {
                                    var data = JSON.parse('<?php echo json_encode( $lacres_retirados['list'] );?>');
                                    var ids_data = $(data).map(function () {
                                        return this.id;
                                    }).get();
                                    $("select[name='lacre_retirado[]']").select2({data: data});
                                    $("select[name='lacre_retirado[]']").val(ids_data).trigger("change");
                                });
                            </script>
                            <input type="hidden" name="lacres_retirado_hidden"
                                   value="{{json_encode($lacres_retirados['id'])}}"/>
                        @endif
                    </div>
                    <div class="alert-seal alert alert-danger fade in esconda"
                         role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Este campo de preenchimento obrigatório. A inclusão de numerações fictícias, fará com que o técnico ou técnico franquiado este sujeito a multas severas. Caso o Lacre não se faça presente entrar com 8 dígitos sequencias “12345678”, o mesmo será verificado posteriormente no portal PSI. Para Lacre de outra permissionária entrar com Nº da autorização presente no lacre e não Nº do lacre. Exemplo autorização Atlas 10002180.
                    </div>
                    <input type="text" name="lacre_retirado_livre" class="form-control show-lacre"
                           placeholder="Outros Lacres separados por ';' Ex: 1001; 1002; 1003"
                           @if($lacres_retirados['id']==null) required @else style="display:none;" @endif/>
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
        @if($AparelhoManutencao->numero_chamado != null)
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