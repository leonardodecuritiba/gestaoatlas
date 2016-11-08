<section id="novo-peca" style="display: none" >
    <div class="x_panel hide">
        <div class="x_content" id="campo-fotos">
        </div>
    </div>
    {!! Form::open(['route' => 'pecas.store', 'method' => 'POST', 'files' => true,
        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
        <input type="hidden" name="idfornecedor" value="{{$Fornecedor->idfornecedor}}">

    <div class="x_panel">
        <div class="x_title">
            <h2>Dados da Peça</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content" id="peca-container">

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Foto:</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <input name="foto" type="file" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Código:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <input name="codigo" type="text" class="form-control" placeholder="Código">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Cód. Auxiliar:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <input name="codigo_auxiliar" type="text" class="form-control" placeholder="Código Auxiliar">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Cód. de Barras:</label>
                <div class="col-md-10 col-sm-10 col-xs-12 form-group">
                    <input name="codigo_barras" type="text" class="form-control" placeholder="Código de Barras" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Descrição:</label>
                <div class="col-md-10 col-sm-10 col-xs-12 form-group">
                    <input name="descricao" type="text" class="form-control" placeholder="Descrição" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Desc. Técnico:</label>
                <div class="col-md-10 col-sm-10 col-xs-12 form-group">
                    <input name="descricao_tecnico" type="text" class="form-control" placeholder="Descrição Técnico" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Marca:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <select name="marca" class="select2_single form-control" tabindex="-1" required>
                        <option value="">Escolha a Marca</option>
                        <option value="1">MARCA TESTE 1</option>
                        <option value="2">MARCA TESTE 2</option>
                    </select>
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Unidade:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <select name="unidade" class="select2_single form-control" tabindex="-1" required>
                        <option value="">Escolha a Unidade</option>
                        <option value="1">Kg</option>
                        <option value="2">Cm</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Grupo:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    {{--<input name="grupo" type="text" class="form-control" placeholder="Grupo" >--}}
                    <select name="grupo" class="select2_single form-control" tabindex="-1" required>
                        <option value="">Escolha o Grupo</option>
                        <option value="1">GRUPO TESTE 1</option>
                        <option value="2">GRUPO TESTE 2</option>
                    </select>
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Subgrupo:</label>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <select name="sub_grupo" class="select2_single form-control" tabindex="-1" required>
                        <option value="">Escolha o Subgrupo</option>
                        <option value="1">SUBGRUPO TESTE 1</option>
                        <option value="2">SUBGRUPO TESTE 2</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Valores da Peça</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content" id="peca-container">

            {{--COMISSÕES--}}
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Comissão Téc.:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input name="comissao_tecnico" type="text" class="form-control show-porcento" placeholder="Comissão Técnico" >
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Comissão Vend.:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input name="comissao_vendedor" type="text" class="form-control show-porcento" placeholder="Comissão Vendedor" >
                </div>
            </div>
            <div class="ln_solid"></div>

            {{--CUSTO REAIS--}}
            <div id="custo_reais">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Compra:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_compra" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Compra" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Frete:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_frete" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Frete" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Imposto:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_imposto" type="text" class="form-control show-valor calc-custo-reais" placeholder="Custo Imposto" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Final:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_final" disabled type="text" class="form-control show-valor" placeholder="Custo Final" >
                    </div>
                </div>
                <div class="ln_solid"></div>
            </div>

            {{--CUSTO DÓLAR--}}
            <div id="custo_dolar">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Câmbio:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_dolar_cambio" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Câmbio" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_dolar" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Frete:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_dolar_frete" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Frete" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Custo Dólar Imposto:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="custo_dolar_imposto" type="text" class="form-control show-valor-dolar calc-dolar" placeholder="Custo Dólar Imposto" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="preco" disabled type="text" class="form-control show-valor" placeholder="Preço" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Frete:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="preco_frete" disabled type="text" class="form-control show-valor" placeholder="Preço Frete" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Imposto:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="preco_imposto" disabled type="text" class="form-control show-valor" placeholder="Preço Imposto" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Final:</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="preco_final" disabled  type="text" class="form-control show-valor" placeholder="Preço Final" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="x_panel" id="tributacao">
        <div class="x_title">
            <h2>Tributação</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="form-horizontal form-label-left">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">NCM: </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <select name="idncm" class="select2_single form-control" tabindex="-1" required>
                            <option value="">Escolha o NCM</option>
                            @foreach($Page->extras['ncm'] as $sel)
                                <option value="{{$sel->idncm}}">({{$sel->codigo}}) {{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Categoria Tributável: </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <select name="idcategoria_tributacao" class="select2_single form-control" tabindex="-1" required>
                            <option value="">Escolha a Categoria</option>
                            @foreach($Page->extras['categoria_tributacao'] as $sel)
                                <option value="{{$sel->idcategoria_tributacao}}">({{$sel->codigo}}) {{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Origem: </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <select name="idorigem_tributacao" class="select2_single form-control" tabindex="-1" required>
                            <option value="">Escolha a Origem</option>
                            @foreach($Page->extras['origem_tributacao'] as $sel)
                                <option value="{{$sel->idorigem_tributacao}}">({{$sel->codigo}}) {{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">CST IPI: </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <select name="idcst_ipi" class="select2_single form-control" tabindex="-1" required>
                            <option value="">Escolha o CST IPI</option>
                            @foreach($Page->extras['cst_ipi'] as $sel)
                                <option value="{{$sel->idcst_ipi}}">({{$sel->codigo}}) {{$sel->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Peso Líquido </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="peso_liquido" type="text" class="form-control show-peso" placeholder="Peso Líquido" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Peso Bruto </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="peso_bruto" type="text" class="form-control show-peso" placeholder="Peso Bruto" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">IPI</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="ipi" type="text" class="form-control show-porcento" placeholder="IPI (%)" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input name="isencao_icms" type="checkbox" class="flat"> Isento ICMS
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input name="ipi_venda" type="checkbox" class="flat"> IPI na Venda?
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input name="reducao_icms" type="checkbox" class="flat"> Redução ICMS
                            </label>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>

                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="icms" type="text" class="form-control show-valor" placeholder="ICMS" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Red. de BC do ICMS </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="reducao_bc_icms" type="text" class="form-control show-porcento" placeholder="Redução de BC do ICMS (%)" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Red. de BC do ICMS ST</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="reducao_bc_icms_st" type="text" class="form-control show-porcento" placeholder="Redução de BC do ICMS (%)" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. ICMS Cupom Fiscal</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="aliquota_icms" type="text" class="form-control show-porcento" placeholder="Alíquota ICMS Cupom Fiscal (%)" >
                    </div>
                </div>
                <div class="ln_solid"></div>

                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. II </label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="aliquota_ii" type="text" class="form-control show-porcento" placeholder="Alíquota II (%)" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">ICMS Import.</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="icms_importacao" type="text" class="form-control show-valor" placeholder="ICMS Importação (%)" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. COFINS Import.</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="aliquota_cofins_importacao" type="text" class="form-control show-porcento" placeholder="Alíquota COFINS Importação (%)" >
                    </div>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Alíq. PIS Import.</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input name="aliquota_pis_importacao" type="text" class="form-control show-porcento" placeholder="Alíquota PIS Importação (%)" >
                    </div>
                </div>
            </div>
        </div>

    </div>
    <section class="row">
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <button type="reset" id="cancel-peca" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                </div>
            </div>
        </section>
    {{ Form::close() }}
</section>

<section class="x_panel" id="resultados-peca">
    <div class="x_title">
        <h2>Peças encontradas</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <button class="btn btn-primary" id="add-peca"><i class="fa fa-plus-circle fa-2"></i> Nova peça</button>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            @if($Fornecedor->has_peca())
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
                            @foreach($Fornecedor->pecas as $peca)
                                <tr>
                                    <td>
                                        <img src="{{$peca->getFotoThumb()}}" class="avatar" alt="Avatar">
                                    </td>
                                    <td>{{$peca->descricao}}</td>
                                    <td>{{$peca->codigo}}</td>
                                    <td>{{$peca->marca}}</td>
                                    <td>{{$peca->grupo}}</td>
                                    <td>
                                        <button class="btn btn-default btn-xs edit-peca"
                                           data-dados="{{$peca}}"
                                           data-tributacao="{{$peca->tributacao}}" >
                                            <i class="fa fa-edit"></i> Visualizar / Editar</button>

                                        <button class="btn btn-danger btn-xs"
                                                data-nome="peca: {{$peca->descricao}}"
                                                data-href="{{route('pecas.destroy',$peca->idpeca)}}"
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
                    <p>Este fornecedor não possui nenhuma peça cadastrada.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<script>

    var $novo_container      = $('section#novo-peca');
    var $novo_container_form = $($novo_container).find('form');

    var required_fields = ['codigo',
        'descricao',
        'descricao_tecnico',
        'marca',
        'unidade',
        'grupo',
        'comissao_tecnico',
        'comissao_vendedor',
        'custo_compra',
        'custo_imposto',

        'idncm',
        'idcategoria_tributacao',
        'idorigem_tributacao',
        'idcst_ipi',
        'peso_liquido',
        'peso_bruto',
        'ipi',
        'icms',
        'reducao_bc_icms',
        'reducao_bc_icms_st',
        'aliquota_icms'
    ];

    $(document).ready(function(){
        $.each(required_fields, function(i,v){
            $($novo_container_form).find('input[name="' + v +'"]').prop('required',true);
        });
    });

    $ACTION_NEW = "{{route('pecas.store')}}";
    $CAMINHO_FOTO = "{{asset('../storage/uploads/pecas/X')}}";
    function peca_toggle(){
        $($novo_container).find('div#campo-fotos').parent('div.x_panel').addClass('hide');
        $($novo_container).find('div#campo-fotos').empty();
        $($novo_container).toggle('fast');
        $('section#resultados-peca').toggle('fast');
    }

    $('button#add-peca, button#cancel-peca').click(function() {
        $($novo_container).find('form').get(0).setAttribute('action', $ACTION_NEW);
        peca_toggle();
        $($novo_container).find('form').find('input[name="_method"]').remove();
    });

    $('button.edit-peca').click(function(){
        peca_toggle();
        $dados = $(this).data('dados');
        console.log($dados);
        $ACTION_EDIT = "{{route('pecas.update',0)}}";
        console.log( $ACTION_EDIT.replace(0,$dados.idpeca));
        $($novo_container).find('form').get(0).setAttribute('action', $ACTION_EDIT.replace(0,$dados.idpeca));
        $($novo_container).find('form').append('<input name="_method" type="hidden" value="PATCH">');
        html_foto = '';
        $.each($dados, function(i,v){
            if(i!='foto') {
                $($novo_container).find('div#peca-container').find(":input[name="+ i +"]").val(v);
            } else {
                console.log('v-' + v + '-');
                if(v!='' && v!=null){
                    $($novo_container).find('div#campo-fotos').parent('div.x_panel').removeClass('hide');
                    foto = $CAMINHO_FOTO.replace('X',v);
                    html_foto = '<div class="col-md-55">' +
                            '<div class="image view view-first">' +
                            '<img style="width: 100%; display: block;" src="' + foto + '" alt="image" />' +
                            '</div>' +
                            '</div>';
                    $($novo_container).find('div#campo-fotos').append(html_foto);
                }
            }
        });

        $tributacao = $(this).data('tributacao');
        console.log($tributacao);

        nomes_bool = ['isencao_icms','ipi_venda','reducao_icms'];
        $.each($tributacao, function(i,v){
            console.log(i + " = " + v);
            console.log(":input[name="+ i +"] = " + v);
            if($.inArray(i, nomes_bool)!= -1){
                if(v==1){
                    $($novo_container).find('div#tributacao').find(":input[name="+ i +"]").parents('div.icheckbox_flat-green').addClass('checked');
                    $($novo_container).find('div#tributacao').find(":input[name="+ i +"]").attr('checked', 'checked');
                }
            }
            $($novo_container).find('div#tributacao').find(":input[name="+ i +"]").val(v);

        });
    });


    //cálculos
    $(document).ready(function(){
        $('input.calc-custo-reais').change(function(){
            $parent = $(this).parents('div#custo_reais');
            var campos = ['custo_compra','custo_frete','custo_imposto'];
            var custo_final = 0;
            $.each(campos, function(i,v){
                valor = $($parent).find('input[name='+v+']').maskMoney('unmasked');
                custo_final += valor[0];
            });
            $($parent).find('input[name=custo_final]').maskMoney('mask', custo_final);

        });
        $('input.calc-dolar').change(function(){
            $parent = $(this).parents('div#custo_dolar');
            var campos = ['custo_dolar_cambio','custo_dolar','custo_dolar_frete','custo_dolar_imposto'];

            soma = 0;

            //Cálculo do preço
            custo_dolar_cambio = $($parent).find('input[name=custo_dolar_cambio]').maskMoney('unmasked');
            custo_dolar        = $($parent).find('input[name=custo_dolar]').maskMoney('unmasked');
            preco = custo_dolar_cambio[0] * custo_dolar[0];
            soma += preco;
            $($parent).find('input[name=preco]').maskMoney('mask', preco);

            //Cálculo do preço do frete
            custo_dolar_frete = $($parent).find('input[name=custo_dolar_frete]').maskMoney('unmasked');
            preco = custo_dolar_cambio[0] * custo_dolar_frete[0];
            soma += preco;
            $($parent).find('input[name=preco_frete]').maskMoney('mask', preco);

            //Cálculo do preço do frete
            custo_dolar_imposto = $($parent).find('input[name=custo_dolar_imposto]').maskMoney('unmasked');
            preco = custo_dolar_cambio[0] * custo_dolar_imposto[0];
            soma += preco;
            $($parent).find('input[name=preco_imposto]').maskMoney('mask', preco);

            //Cálculo do preço final
            $($parent).find('input[name=preco_final]').maskMoney('mask', soma);
        });
    });
</script>