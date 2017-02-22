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
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Descrição:</label>
                    <div class="col-md-10 col-sm-10 col-xs-12 form-group has-feedback">
                        <input name="descricao" type="text" class="form-control" placeholder="Descrição" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Marca:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
                        <select name="idmarca" class="form-control" required>
                            <option value="">Escolha uma Marca</option>
                            @foreach($Page->extras['marcas'] as $sel)
                                <option value="{{$sel->idmarca}}">{{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Modelo:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="modelo" type="text" class="form-control" placeholder="Modelo" required>
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
                        <input name="inventario" type="text" class="form-control" placeholder="Inventário" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Portaria:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="portaria" type="text" class="form-control" placeholder="Portaria" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Divisão:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="divisao" type="text" class="form-control" placeholder="Divisão" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Capacidade:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="capacidade" type="text" class="form-control" placeholder="Capacidade" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Setor:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="setor" type="text" class="form-control" placeholder="Setor" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Patrimônio:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="patrimonio" type="text" class="form-control" placeholder="Patrimônio" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">IP:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="ip" type="text" class="form-control" placeholder="IP" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Endereço:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="endereco" type="text" class="form-control" placeholder="Endereço" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Foto:</label>
                    <div class="col-md-10 col-sm-10 col-xs-12">
                        <input name="foto" type="file" class="form-control">
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
                        <table id="datatable-responsive"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th colspan="4">Selo</th>
                            </tr>
                            <tr>
                                <th>Afixado em</th>
                                <th>Técnico</th>
                                <th>Númeração</th>
                                <th>Númeração (DV)</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="x_content" id="lacres-container">
                <div class="form-group">
                    <div class="animated fadeInDown">
                        <table id="datatable-responsive"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr><th colspan="3">Lacres</th></tr>
                            <tr>
                                <th>Afixado em</th>
                                <th>Técnico</th>
                                <th>Númeração</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
                    <table border="0" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Modelo</th>
                                <th>Série</th>
                                <th>Inventário</th>
                                <th>Selo Afixado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Cliente->instrumentos as $instrumento)
                                <tr>
                                    <td>{{$instrumento->idinstrumento}}</td>
                                    <td><img src="{{$instrumento->getFotoThumb()}}" class="avatar" alt="Avatar"></td>
                                    <td>{{$instrumento->modelo}}</td>
                                    <td>{{$instrumento->numero_serie}}</td>
                                    <td>{{$instrumento->inventario}}</td>
                                    <td>{{$instrumento->selo_afixado_numeracao()}}</td>
                                    <td>
                                        <button class="btn btn-default btn-xs edit-instrumento"
                                           data-dados="{{$instrumento}}"
                                           data-selo-afixado="{{$instrumento->selo_instrumento_cliente()}}"
                                           data-lacres-afixados="{{$instrumento->lacres_instrumento_cliente()}}"
                                           data-lacres="{{$instrumento->lacres_instrumentos}}">
                                            <i class="fa fa-edit"></i> Visualizar / Editar</button>

                                        <button class="btn btn-danger btn-xs"
                                                data-nome="Patrimônio: {{$instrumento->descricao}}"
                                                data-href="{{route('instrumentos.destroy',$instrumento->idinstrumento)}}"
                                                data-toggle="modal"
                                                data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i> Excluir</button>
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
    var $novo_instrumento_container      = $('section#novo-instrumento');
    $ACTION_NEW_INSTRUMENTO = "{{route('instrumentos.store')}}";
    $CAMINHO_FOTO_INSTRUMENTO = "{{asset('/uploads/instrumentos/X')}}";
    $($novo_instrumento_container).find('div.lacres-selos').hide();
    function instrumento_toggle(){
        $($novo_instrumento_container).find('div#campo-fotos').parent('div.x_panel').addClass('hide');
        $($novo_instrumento_container).find('div#campo-fotos').empty();
        $($novo_instrumento_container).toggle('fast');
        $('section#resultados-instrumento').toggle('fast');
        $($novo_instrumento_container).find('div.lacres-selos').hide();
    }

    $('button#add-instrumento, button#cancel-instrumento').click(function() {
        $($novo_instrumento_container).find('form').get(0).setAttribute('action', $ACTION_NEW_INSTRUMENTO);
        $($novo_instrumento_container).find('div.instrumento div.x_title small').empty();
        instrumento_toggle();
        $($novo_instrumento_container).find('form').find('input[name="_method"]').remove();
    });

    $('button.edit-instrumento').click(function(){
        instrumento_toggle();
        $dados = $(this).data('dados');
        console.log($dados);
        var ver_selo_lacre = 0;

        $selo = $(this).data('selo-afixado');
        if (($selo != null) && ($selo != "")) {
            ver_selo_lacre = 1;
            console.log($selo);
            $($novo_instrumento_container).find('div#selos-container').find('tbody').empty();
            $($novo_instrumento_container).find('div#selos-container').find('tbody').append(
                '<tr>' +
                '<td>' + $selo.afixado_em + '</td>' +
                '<td>' + $selo.tecnico + '</td>' +
                '<td>' + $selo.numeracao + '</td>' +
                '<td>' + $selo.numeracao_dv + '</td>' +
                '</tr>'
            );
        }

        $lacres = $(this).data('lacres-afixados');
        if (($lacres != null) && ($lacres != "")) {
            ver_selo_lacre = 1;
            console.log($lacres);
            $($novo_instrumento_container).find('div#lacres-container').find('tbody').empty();
            $.each($lacres, function (i, lacre) {
                $($novo_instrumento_container).find('div#lacres-container').find('tbody').append(
                    '<tr>' +
                    '<td>' + lacre.afixado_em + '</td>' +
                    '<td>' + lacre.tecnico + '</td>' +
                    '<td>' + lacre.numeracao + '</td>' +
                    '</tr>'
                );
            });
        }
        if (ver_selo_lacre) $($novo_instrumento_container).find('div.lacres-selos').show();

        $ACTION_EDIT = "{{route('instrumentos.update',0)}}";
        console.log($ACTION_EDIT.replace('/0', '/' + $dados.idinstrumento));
        $($novo_instrumento_container).find('form').get(0).setAttribute('action', $ACTION_EDIT.replace('/0', '/' + $dados.idinstrumento));
        $($novo_instrumento_container).find('form').append('<input name="_method" type="hidden" value="PATCH">');
        $($novo_instrumento_container).find('div.instrumento div.x_title small').html('#' + $dados.idinstrumento);
        html_foto = '';

        $.each($dados, function(i,v){
//            console.log(i + " = " + v);
//            console.log("input[name="+ i +"] = " + v);
            if(i!='foto') {
                $($novo_instrumento_container).find('div#instrumento-container').find(":input[name="+ i +"]").val(v);
            } else {
                console.log('v-' + v + '-');
                if(v!='' && v!=null){
                    $($novo_instrumento_container).find('div#campo-fotos').parent('div.x_panel').removeClass('hide');
                    foto = $CAMINHO_FOTO_INSTRUMENTO.replace('X',v);
                    console.log(foto);
                    html_foto = '<div class="form-group">'+
                        '<div class="peca_image">' +
                        '<img width="70%" src="' + foto + '" />' +
                        '</div>' +
                        '</div>';
                    $($novo_instrumento_container).find('div#campo-fotos').append(html_foto);

                }
            }
        });

    });
</script>