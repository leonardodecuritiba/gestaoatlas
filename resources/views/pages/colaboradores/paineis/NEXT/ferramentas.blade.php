<section id="novo-equipamento" style="display: none" >
    <div class="x_panel hide">
        <div class="x_content" id="campo-fotos">
        </div>
    </div>
    {!! Form::open(['route' => 'equipamentos.store', 'method' => 'POST', 'files' => true,
        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <div class="x_panel">
            <input type="hidden" name="idcolaborador" value="{{$Colaborador->idcolaborador}}">
            <div class="x_title">
                <h2>Dados da Ferramenta</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="equipamento-container">
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Descrição:</label>
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group has-feedback">
                        <input name="descricao" type="text" class="form-control" placeholder="Descrição" required>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                        <input name="codigo" type="text" class="form-control" placeholder="Código" required>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="codigo_auxiliar" type="text" class="form-control" placeholder="Código Auxiliar">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Grupo:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
                        <input name="grupo" type="text" class="form-control" placeholder="Grupo" required>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                        <input name="sub_grupo" type="text" class="form-control" placeholder="Subgrupo">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="marca" type="text" class="form-control" placeholder="Marca" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Foto:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="foto" type="file" class="form-control">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                        <select class="select2_single form-control" name="garantia" tabindex="-1" required>
                            <option value="">Garantia</option>
                            <option value="0">3 meses</option>
                            <option value="1">1 ano</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="taxacao" type="text" class="form-control" placeholder="Taxação" required>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Estoque:</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="estoque_min" type="text" class="form-control" placeholder="Mínimo" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="estoque_med" type="text" class="form-control" placeholder="Médio" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="estoque_max" type="text" class="form-control" placeholder="Máximo" required>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Custo:</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="custo_anterior" type="text" class="form-control" placeholder="Anterior" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_atual" type="text" class="form-control" placeholder="Atual" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_frete" type="text" class="form-control" placeholder="Frete" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-1 col-xs-12" for="first-name">Importação:</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="custo_dolar_anterior" type="text" class="form-control" placeholder="Custo Anterior" >
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="custo_dolar_frete" type="text" class="form-control" placeholder="Frete" >
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input name="custo_dolar_cambio" type="text" class="form-control" placeholder="Câmbio" >
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <input name="custo_dolar_imposto" type="text" class="form-control" placeholder="Imposto" >
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
        <h2>Ferramentas encontrados</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <button class="btn btn-primary" id="add-equipamento"><i class="fa fa-plus-circle fa-2"></i> Novo equipamento</button>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            @if($Colaborador->has_equipamento())
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                    <table border="0" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Descrição</th>
                                <th>Código</th>
                                <th>Marca</th>
                                <th>Grupo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Colaborador->equipamentos as $equipamento)
                                <tr>
                                    <td>
                                        <img src="{{$equipamento->getFotoThumb()}}" class="avatar" alt="Avatar">
                                    </td>
                                    <td>{{$equipamento->descricao}}</td>
                                    <td>{{$equipamento->codigo}}</td>
                                    <td>{{$equipamento->marca}}</td>
                                    <td>{{$equipamento->grupo}}</td>
                                    <td>
                                        <button class="btn btn-default btn-xs edit-equipamento"
                                           data-dados="{{$equipamento}}" >
                                            <i class="fa fa-edit"></i> Visualizar / Editar</button>

                                        <button class="btn btn-danger btn-xs"
                                                data-nome="Ferramenta: {{$equipamento->descricao}}"
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
                    <p>Este colaborador não possui nenhuma ferramenta cadastrado.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<script>
{{--    $ACTION_NEW = "{{route('equipamentos.store')}}";--}}
    $CAMINHO_FOTO = "{{asset('../storage/uploads/equipamentos/X')}}";
    function equipamento_toggle(){
        $('section#novo-equipamento').find('div#campo-fotos').parent('div.x_panel').addClass('hide');
        $('section#novo-equipamento').find('div#campo-fotos').empty();
        $('section#novo-equipamento').toggle('fast');
        $('section#resultados-equipamento').toggle('fast');
    }

    $('button#add-equipamento, button#cancel-equipamento').click(function() {
        $('section#novo-equipamento').find('form').get(0).setAttribute('action', $ACTION_NEW);
        equipamento_toggle();
        $('section#novo-equipamento').find('form').find('input[name="_method"]').remove();
    });

    $('button.edit-equipamento').click(function(){
        equipamento_toggle();
        $dados = $(this).data('dados');
        console.log($dados);
        $ACTION_EDIT = "{{route('equipamentos.update',0)}}";
        console.log( $ACTION_EDIT.replace(0,$dados.idequipamento));
        $('section#novo-equipamento').find('form').get(0).setAttribute('action', $ACTION_EDIT.replace(0,$dados.idequipamento));
        $('section#novo-equipamento').find('form').append('<input name="_method" type="hidden" value="PATCH">');
        html_foto = '';
        $.each($dados, function(i,v){
//            console.log(i + " = " + v);
//            console.log("input[name="+ i +"] = " + v);
            if(i!='foto') {
                $('section#novo-equipamento div#equipamento-container').find("input[name="+ i +"]").val(v);
            } else {
                console.log('v-' + v + '-');
                if(v!='' && v!=null){
                    $('section#novo-equipamento').find('div#campo-fotos').parent('div.x_panel').removeClass('hide');
                    foto = $CAMINHO_FOTO.replace('X',v);
                    html_foto = '<div class="col-md-55">' +
                            '<div class="image view view-first">' +
                            '<img style="width: 100%; display: block;" src="' + foto + '" alt="image" />' +
                            '</div>' +
                            '</div>';
                    $('section#novo-equipamento').find('div#campo-fotos').append(html_foto);
                }
            }
        });

        $contato = $(this).data('contato');
        $.each($contato, function(i,v){
//            console.log(i + " = " + v);
//            console.log("input[name="+ i +"] = " + v);
            $('section#novo-equipamento div#contato-container').find("input[name="+ i +"]").val(v);

        });
    });
</script>