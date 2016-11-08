<section id="novo-equipamento" style="display: none" >
    <div class="x_panel hide">
        <div class="x_content" id="campo-fotos">
        </div>
    </div>
    {!! Form::open(['route' => 'equipamentos.store', 'method' => 'POST', 'files' => true,
        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <div class="x_panel">
            <input type="hidden" name="idcliente" value="{{$Cliente->idcliente}}">
            <div class="x_title">
                <h2>Dados do Equipamento</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="equipamento-container">
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
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Número de Série:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="numero_serie" type="text" class="form-control" placeholder="Número de Série" required>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Foto:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="foto" type="file" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <section class="row">
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <button type="reset" id="cancel-equipamento" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                </div>
            </div>
        </section>
    {{ Form::close() }}
</section>
<section class="x_panel" id="resultados-equipamento">
    <div class="x_title">
        <h2>Equipamentos encontrados</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <button class="btn btn-primary" id="add-equipamento"><i class="fa fa-plus-circle fa-2"></i> Novo equipamento</button>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            @if($Cliente->has_equipamento())
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table border="0" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Descrição</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Série</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Cliente->equipamentos as $equipamento)
                                <tr>
                                    <td>
                                        <img src="{{$equipamento->getFotoThumb()}}" class="avatar" alt="Avatar">
                                    </td>
                                    <td>{{$equipamento->descricao}}</td>
                                    <td>{{$equipamento->marca->descricao}}</td>
                                    <td>{{$equipamento->modelo}}</td>
                                    <td>{{$equipamento->numero_serie}}</td>
                                    <td>
                                        <button class="btn btn-default btn-xs edit-equipamento"
                                           data-dados="{{$equipamento}}">
                                            <i class="fa fa-edit"></i> Visualizar / Editar</button>

                                        <button class="btn btn-danger btn-xs"
                                                data-nome="Patrimônio: {{$equipamento->descricao}}"
                                                data-href="{{route('equipamentos.destroy',$equipamento->idequipamento)}}"
                                                data-toggle="modal"
                                                data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i> Excluir</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
                <ul class="pagination">
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">6</a></li>
                    <li><a href="#">7</a></li>
                    <li><a href="#">8</a></li>
                    <li><a href="#">9</a></li>
                    <li><a href="#">10</a></li>
                </ul>
            @else
                <div class="jumbotron">
                    <p>Este cliente não possui nenhum equipamento cadastrado.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<script>
    var $novo_equipamento_container      = $('section#novo-equipamento');
    $ACTION_NEW_EQUIPAMENTO = "{{route('equipamentos.store')}}";
    $CAMINHO_FOTO_EQUIPAMENTO = "{{asset('../storage/uploads/equipamentos/X')}}";
    function equipamento_toggle(){
        $($novo_equipamento_container).find('div#campo-fotos').parent('div.x_panel').addClass('hide');
        $($novo_equipamento_container).find('div#campo-fotos').empty();
        $($novo_equipamento_container).toggle('fast');
        $('section#resultados-equipamento').toggle('fast');
    }

    $('button#add-equipamento, button#cancel-equipamento').click(function() {
        $($novo_equipamento_container).find('div.lacres-selos').addClass('hide');
        $($novo_equipamento_container).find('form').get(0).setAttribute('action', $ACTION_NEW_EQUIPAMENTO);
        equipamento_toggle();
        $($novo_equipamento_container).find('form').find('input[name="_method"]').remove();
    });

    $('button.edit-equipamento').click(function(){
        equipamento_toggle();
        $dados = $(this).data('dados');
        console.log($dados);
        $ACTION_EDIT = "{{route('equipamentos.update',0)}}";
        console.log( $ACTION_EDIT.replace(0,$dados.idequipamento));
        $($novo_equipamento_container).find('form').get(0).setAttribute('action', $ACTION_EDIT.replace(0,$dados.idequipamento));
        $($novo_equipamento_container).find('form').append('<input name="_method" type="hidden" value="PATCH">');
        html_foto = '';
        $.each($dados, function(i,v){
            if(i!='foto') {
                $($novo_equipamento_container).find('div#equipamento-container').find(":input[name="+ i +"]").val(v);
            } else {
                console.log('v-' + v + '-');
                if(v!='' && v!=null){
                    $($novo_equipamento_container).find('div#campo-fotos').parent('div.x_panel').removeClass('hide');
                    foto = $CAMINHO_FOTO_EQUIPAMENTO.replace('X',v);
                    html_foto = '<div class="form-group">'+
                            '<div class="peca_image">' +
                            '<img src="' + foto + '" />' +
                            '</div>' +
                            '</div>';
                    $($novo_equipamento_container).find('div#campo-fotos').append(html_foto);
                }
            }
        });

    });
</script>