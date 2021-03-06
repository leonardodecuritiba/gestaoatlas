<section id="novo-instrumento" style="display: none" >
    <div class="x_panel hide">
        <div class="x_content text-center" id="campo-fotos">
        </div>
    </div>
    {!! Form::open(['route' => 'instrumentos.store', 'method' => 'POST', 'files' => true,
        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
    <div class="x_panel instrumento">
            <input type="hidden" name="idcliente" value="{{$Cliente->idcliente}}">
            <div class="x_title">
                <h2>Dados do Instrumento
                    <small></small>
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="instrumento-container">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Base:</label>
                    <div class="col-md-10 col-sm-10 col-xs-12 form-group has-feedback">
                        <select name="idbase" class="form-control select2_single" required>
                            <option value="">Escolha um Instrumento Base</option>
                            @foreach($Page->extras['instrumentos_base'] as $sel)
                                <option value="{{$sel->id}}">{{$sel->getDetalhesBase()}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Setor:</label>
                    <div class="col-md-10 col-sm-10 col-xs-12 form-group has-feedback">
                        <select name="idsetor" class="form-control select2_single" required>
                            <option value="">Escolha um Setor</option>
                            @foreach($Page->extras['setors'] as $sel)
                                <option value="{{$sel->id}}">{{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Nº Série:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="numero_serie" type="text" class="form-control" placeholder="Nº Série" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Ano:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="ano" maxlength="4" type="text" class="form-control" placeholder="Ano" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Inventário:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="inventario" type="text" class="form-control" placeholder="Inventário">
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Patrimônio:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="patrimonio" type="text" class="form-control" placeholder="Patrimônio">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">IP:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="ip" type="text" class="form-control" placeholder="IP">
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Endereço:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="endereco" type="text" class="form-control" placeholder="Endereço">
                    </div>
                </div>
                <div class="form-group etiquetas esconda">
                    <div id="etiqueta_identificacao" class="col-md-55 col-md-offset-2 esconda">
                        <div class="thumbnail">
                            <div class="image view view-first">
                                <img style="width: 100%; display: block;"
                                     alt="image"/>
                            </div>
                            <div class="caption">
                                <p>Etiqueta de Identificação</p>
                            </div>
                        </div>
                    </div>
                    <div id="etiqueta_inventario" class="col-md-55 col-md-offset-4 esconda">
                        <div class="thumbnail">
                            <div class="image view view-first">
                                <img style="width: 100%; display: block;"
                                     alt="image"/>
                            </div>
                            <div class="caption">
                                <p>Etiqueta de Inventário</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Etiqueta de
                        Identificação:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="etiqueta_identificacao" type="file" class="form-control">
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Etiqueta de
                        Inventário:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="etiqueta_inventario" type="file" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    <div class="x_panel lacres-selos">
            <div class="x_title">
                <h2>Selo e lacres do Instrumento</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="selos-container">
                <div class="form-group">
                    <div class="animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Númeração (DV)</th>
                                <th>Afixado em (O.S)</th>
                                <th>Retirado em (O.S)</th>
                                <th>Técnico</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Númeração (DV)</th>
                                <th>Afixado em (O.S)</th>
                                <th>Retirado em (O.S)</th>
                                <th>Técnico</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="x_content" id="lacres-container">
                <div class="form-group">
                    <div class="animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Númeração</th>
                                <th>Afixado em (O.S)</th>
                                <th>Retirado em (O.S)</th>
                                <th>Técnico</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Númeração</th>
                                <th>Afixado em (O.S)</th>
                                <th>Retirado em (O.S)</th>
                                <th>Técnico</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <section class="row">
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 ">
                <button type="reset" id="cancel-instrumento" class="btn btn-danger btn-lg btn-block">Cancelar</button>
            </div>
            @if(Auth::user()->hasRole(['admin', 'tecnico']) || (!$Cliente->validado()))
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                </div>
            @endif
        </div>
    </section>
    {{ Form::close() }}
</section>
<section class="x_panel" id="resultados-instrumento">
    <div class="x_title">
        <h2>Instrumentos encontrados</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <button class="btn btn-primary" id="add-instrumento"><i class="fa fa-plus-circle fa-2"></i> Novo instrumento</button>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            @if($Cliente->has_instrumento())
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Descrição</th>
                                <th>Nº de Série</th>
                                <th>Inventário</th>
                                <th>Selo</th>
                                <th>Lacres</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($Cliente->instrumentos as $instrumento)
                            <?php
                                $selos = $instrumento->numeracao_selo_afixado();
                                $lacres = $instrumento->numeracao_lacres_afixados();
//                                dd();
                            ?>
                            <tr>
                                <td>{{$instrumento->idinstrumento}}</td>
                                <td><img src="{{$instrumento->getThumbFoto()}}" class="avatar" alt="Avatar"></td>
                                <td>{{$instrumento->getDetalhesBase()}}</td>
                                <td>{{$instrumento->numero_serie}}</td>
                                <td>{{$instrumento->inventario}}</td>
                                <td>{{$selos['text']}}</td>
                                <td>{{$lacres['text']}}</td>
                                <td>
                                    <button class="btn btn-default btn-xs edit-instrumento"
                                            data-dados="{{$instrumento}}"
                                            data-foto="{{$instrumento->getThumbFoto()}}"
                                            data-etiquetas="{{$instrumento->getEtiquetas()}}"
                                            data-selo-afixado="{{$instrumento->selo_instrumento_cliente()}}"
                                            data-lacres-afixados="{{$instrumento->lacres_instrumento_cliente()}}"
                                            data-lacres="{{$instrumento->lacres_instrumentos}}"
                                    ><i class="fa fa-edit"></i> Visualizar / Editar
                                    </button>
                                    <button class="btn btn-danger btn-xs"
                                            data-nome="Patrimônio: {{$instrumento->descricao}}"
                                            data-href="{{route('instrumentos.destroy',$instrumento->idinstrumento)}}"
                                            data-toggle="modal"
                                            data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
            @else
                <div class="jumbotron">
                    <p>Este cliente não possui nenhum instrumento cadastrado.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<script>
    var $novo_instrumento_container = $('section#novo-instrumento');
    var $form_instrumento = $($novo_instrumento_container).find('form');
    $ACTION_NEW_INSTRUMENTO = "{{route('instrumentos.store')}}";
    $FOLDER_INSTRUMENTOS = "{{route('instrumentos.store')}}";
    $($novo_instrumento_container).find('div.lacres-selos').hide();

    function instrumento_toggle(){
        $($novo_instrumento_container).find('div#campo-fotos').parent('div.x_panel').addClass('hide');
        $($novo_instrumento_container).find('div#campo-fotos').empty();
        $($novo_instrumento_container).toggle('fast');
        $('section#resultados-instrumento').toggle('fast');
        $($novo_instrumento_container).find('div.lacres-selos').hide();
    }

    $('button#add-instrumento, button#cancel-instrumento').click(function() {
        $($form_instrumento).get(0).setAttribute('action', $ACTION_NEW_INSTRUMENTO);
        $($novo_instrumento_container).find('div.instrumento div.x_title small').empty();
        $($novo_instrumento_container).find('div.etiquetas').hide();
        instrumento_toggle();
        $($form_instrumento).find('input[name="_method"]').remove();
        // $($form_instrumento).find('input[name="etiqueta_identificacao"]').attr('required', true);
        $($form_instrumento).find('input[name="etiqueta_identificacao"]').attr('required', false);
    });

    $('button.edit-instrumento').click(function(){
        instrumento_toggle();

        $dados = $(this).data('dados');
        var $foto = $(this).data('foto');
        var $etiquetas = $(this).data('etiquetas');
        console.log($dados);
        var ver_selo_lacre = 0;
//        var table = $($novo_instrumento_container).find('div#selos-container table').DataTable();

        $selos = $(this).data('selo-afixado');
        if (($selos != null) && ($selos != "")) {
            ver_selo_lacre = 1;
            console.log($selos);
            $($novo_instrumento_container).find('div#selos-container').find('tbody').empty();
            $($selos).each(function (i, selo) {
//                table.row.add([
//                    selo.idselo,
//                    selo.afixado_em,
//                    selo.retirado_em,
//                    selo.nome_tecnico,
//                    selo.numeracao_dv
//                ]).draw();
//
                $($novo_instrumento_container).find('div#selos-container').find('tbody').append(
                    '<tr>' +
                    '<td>' + selo.idselo + '</td>' +
                    '<td>' + selo.numeracao_dv + '</td>' +
                    '<td>' + selo.afixado_em + '</td>' +
                    '<td>' + selo.retirado_em + '</td>' +
                    '<td>' + selo.nome_tecnico + '</td>' +
                    '</tr>'
                );
            });
        }

        $lacres = $(this).data('lacres-afixados');
        if (($lacres != null) && ($lacres != "")) {
            ver_selo_lacre = 1;
            console.log($lacres);
            $($novo_instrumento_container).find('div#lacres-container').find('tbody').empty();
            $.each($lacres, function (i, lacre) {
                $($novo_instrumento_container).find('div#lacres-container').find('tbody').append(
                    '<tr>' +
                    '<td>' + lacre.idlacre + '</td>' +
                    '<td>' + lacre.numeracao + '</td>' +
                    '<td>' + lacre.afixado_em + '</td>' +
                    '<td>' + lacre.retirado_em + '</td>' +
                    '<td>' + lacre.nome_tecnico + '</td>' +
                    '</tr>'
                );
            });
        }
        if (ver_selo_lacre) $($novo_instrumento_container).find('div.lacres-selos').show();

        $ACTION_EDIT = "{{route('instrumentos.update',0)}}";
        console.log($ACTION_EDIT.replace('/0', '/' + $dados.idinstrumento));
        $($form_instrumento).get(0).setAttribute('action', $ACTION_EDIT.replace('/0', '/' + $dados.idinstrumento));
        $($form_instrumento).append('<input name="_method" type="hidden" value="PATCH">');
        $($novo_instrumento_container).find('div.instrumento div.x_title small').html('#' + $dados.idinstrumento);
        html_foto = '';

        $.each($dados, function(i,v){
//            console.log(i + " = " + v);
//            console.log("input[name="+ i +"] = " + v);
            console.log('i-' + i + 'v-' + v);
            switch (i) {
                case 'etiqueta_identificacao': {
                    if (v == null) {
                        // $($form_instrumento).find('input[name="' + i + '"]').attr('required', true);
                        $($form_instrumento).find('input[name="' + i + '"]').attr('required', false);
                        $($novo_instrumento_container).find('div.etiquetas').find('div#' + i).hide();
                    } else {
                        $($form_instrumento).find('input[name="' + i + '"]').attr('required', false);
                        $($novo_instrumento_container).find('div.etiquetas').show();
                        var $div = $($novo_instrumento_container).find('div.etiquetas').find('div#' + i).show();
                        ;
                        $($div).find('img').attr('src', $etiquetas[i]);
                    }
                    break;
                }
                case 'etiqueta_inventario': {
                    if (v == null) {
                        $($novo_instrumento_container).find('div.etiquetas').find('div#' + i).hide();
                    } else {
                        $($novo_instrumento_container).find('div.etiquetas').show();
                        var $div = $($novo_instrumento_container).find('div.etiquetas').find('div#' + i).show();
                        $($div).find('img').attr('src', $etiquetas[i]);
                    }
                    break;
                }
                case 'idbase': {
                    $($novo_instrumento_container).find('div#instrumento-container').find("select[name=" + i + "]").val(v).trigger("change");
                    break;
                }
                case 'idsetor': {
                    $($novo_instrumento_container).find('div#instrumento-container').find("select[name=" + i + "]").val(v).trigger("change");
                    break;
                }
                case 'base': {
                    if (v != '' && v != null) {
                        $($novo_instrumento_container).find('div#campo-fotos').parent('div.x_panel').removeClass('hide');
                        html_foto = '<div class="form-group">' +
                            '<div class="peca_image">' +
                            '<img width="70%" src="' + $foto + '" />' +
                            '</div>' +
                            '</div>';
                        $($novo_instrumento_container).find('div#campo-fotos').append(html_foto);
                    }
                    break;
                }
                default: {
                    $($novo_instrumento_container).find('div#instrumento-container').find("input[name=" + i + "]").val(v);
                }
            }
        });

    });
</script>