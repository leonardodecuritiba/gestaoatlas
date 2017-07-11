@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('vendors/iCheck/skins/flat/green.css') !!}

    <!-- Datatables -->
    @include('helpers.datatables.head')
    @include('helpers.select2.head')
    <style>
        .label-info {
            background-color: #5bc0de;
            font-size: large;
        }
        .bootstrap-tagsinput {
            width: 100% !important;
        }
    </style>
@endsection
@section('modals_content')
    <?php $Colaborador = \App\Colaborador::find(2); ?>
    @include('pages.ordem_servicos.popup.ordem_servico')
    @include('pages.ordem_servicos.popup.aparelho')
    @include('layouts.modals.delete')
@endsection
@section('page_content')
    <div class="page-title">
        <div class="title_left">
            <h3>{{$Page->titulo_primario.$Page->Target}}
                <small><i> - Número: {{$OrdemServico->idordem_servico}}</i></small>
            </h3>
        </div>
    </div>
    <section class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-cogs fa-2"></i> Configurações</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-default"
                                data-toggle="modal"
                                data-target="#modalOrdemServico">
                            <i class="fa fa-eye fa-2"></i> Visualizar O.S.
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs right" role="tablist">
                        <li role="presentation" class="">
                            <a href="#tab_equipamento" role="tab" id="equipamento-tab" data-toggle="tab"
                               aria-expanded="false">
                                <i class="fa fa-eye fa-2"></i> Equipamentos</a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#tab_instrumento" role="tab" id="instrumento-tab" data-toggle="tab"
                               aria-expanded="true">
                                <i class="fa fa-eye fa-2"></i> Instrumentos</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" id="tab_instrumento" class="tab-pane fade active in"
                             aria-labelledby="instrumento-tab">
                            @if(count($Instrumentos) > 0)
                                @include('pages.ordem_servicos.parts.instrumentos_resultados')
                            @else
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="row jumbotron">
                                            <h1>Ops!</h1>
                                            <h2>Nenhum instrumento cadastrado!</h2>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div role="tabpanel" id="tab_equipamento" class="tab-pane fade"
                             aria-labelledby="equipamento-tab">
                            @if(count($Equipamentos) > 0)
                                @include('pages.ordem_servicos.parts.equipamentos_resultados')
                            @else
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="row jumbotron">
                                            <h1>Ops!</h1>
                                            <h2>Nenhum equipamento cadastrado!</h2>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($OrdemServico->has_aparelho_manutencaos())
        @foreach($OrdemServico->aparelho_manutencaos as $AparelhoManutencao)
            <section class="row">
                @if($AparelhoManutencao->has_instrumento())
                    <?php $Instrumento = $AparelhoManutencao->instrumento;?>
                    @include('pages.ordem_servicos.parts.instrumentos.show')
                @else
                    <?php $Equipamento = $AparelhoManutencao->equipamento;?>
                    @include('pages.ordem_servicos.parts.equipamentos.show')
                @endif
            </section>
        @endforeach
    @endif
    {{--<!-- /page content -->--}}
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
    {!! Html::script('vendors/jquery.tagsinput/src/jquery.tagsinput.js') !!}

    <script>
        <!-- script deleção -->
        $(document).ready(function () {

            $('div#modalDelecao').on('show.bs.modal', function (e) {
                $origem = $(e.relatedTarget);
                nome_ = $($origem).data('nome');
                href_ = $($origem).data('href');
                $el = $($origem).data('elemento');
                $(this).find('.modal-body').html('Você realmente deseja remover <strong>' + nome_ + '</strong> e suas relações? Esta ação é irreversível!');
                $(this).find('.btn-ok').click(function () {
                    window.location.replace(href_);
                });
            });
        });
    </script>    <!-- jQuery Tags Input -->
    <script>
        $(document).ready(function() {
            $('.tags').tagsInput({
                width: 'auto',
                height:'39px',
                defaultText: 'Adicione',
                removeWithBackspace: true,
                confirmKeys: [13, 44]
            });
        });
    </script>
    <!-- /jQuery Tags Input -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();
        });
    </script>

    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('#datatable-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 5,
                    "columnDefs": [{
                        "targets": 0,
                        "orderable": false
                    }],
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
                }
            );
        });
    </script>
    <!-- /Datatables -->


    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
        //SELECTS ADICIONADOS AO INSTRUMENTO

        function removeEl($this) {
            var $parent = $($this).parents('tr');
            $($parent).remove();
        }
        $(document).ready(function () {
            var x = 0;
            $('a.add').click(function () {
                var data = {};
                var $parent = $(this).parents('tr');
                var id_select = $($parent).find('select').attr('id');
                var $selected = $($parent).find('select#' + id_select).find(":selected");
                if ($($selected).val() != '') {
                    data.id = $($selected).val();
                    data.text = $($selected).html();
                    data.preco = $($selected).data('preco');
                    data.quantidade = $($parent).find('input#quantidade').val();
                    data.desconto = $($parent).find('input#desconto').val();
                    data.desconto_float = $($parent).find('input#desconto').maskMoney('unmasked')[0];
                    data.valor = $($selected).data('preco-float');
                    data.total = $($parent).find('input#total').val();
                    x++;
                    var campo = '<tr>' +
                        '<input name="' + id_select + '_desconto[' + (x) + ']" type="hidden" value="' + data.desconto_float + '" required>' +
                        '<input name="' + id_select + '_quantidade[' + (x) + ']" type="hidden" value="' + data.quantidade + '" required>' +
                        '<input name="' + id_select + '_valor[' + (x) + ']" type="hidden" value="' + data.valor + '" required>' +
                        '<input name="' + id_select + '_id[' + (x) + ']" type="hidden" value="' + data.id + '" required>' +
                        '<td>' + data.text + '</td>' +
                        '<td>R$ ' + data.preco + '</td>' +
                        '<td>' + data.quantidade + ' </td>' +
                        '<td>R$ ' + data.desconto + ' </td>' +
                        '<td>' + data.total + ' </td>' +
                        '<td>' +
                        '<a class="btn btn-danger" onclick="removeEl(this)" title="Excluir">' +
                        '<i class="fa fa-trash fa-lg"></i></a>' +
                        '</td>' +
                        '</tr>';
                    $(campo).insertBefore($parent);
                }
            });
        });
        $(document).ready(function () {
            //Select
            $(".select2_single").on("select2:select", function() {
                //achar parent, pegar próximo td e escrever o valor
                var $sel = $(this).find(":selected");
                var $tr = $(this).parents('tr');
                var $field_preco = $($tr).find("input#valor");
                var $field_quantidade = $($tr).find("input#quantidade");
                var $field_total = $($tr).find("input#total");
                var $field_desconto = $($tr).find("input#desconto");

                $($field_quantidade).val(1);
                $($field_desconto).val(0.00);
                var preco = '';
                if($($sel).val()!=''){
                    preco = $sel.data('preco');
                    preco = 'R$ ' + preco;
                }
                $($field_preco).val(preco);
                $($field_total).val(preco);
            });
            $(".calc-total").on("change", function () {
                //achar parent, pegar próximo td e escrever o valor


                var $sel = $(this).parents('td').prevAll().find(":selected");
                var $tr = $(this).parents('tr');
                var $field_preco = $($tr).find("input#valor");
                var $field_quantidade = $($tr).find("input#quantidade");
                var $field_total = $($tr).find("input#total");
                var $field_desconto = $($tr).find("input#desconto");

                var quantidade = $($field_quantidade).val();
                var desconto = $($field_desconto).maskMoney('unmasked')[0];
                var preco = '';
                if ($($sel).val() != '') {
                    preco = $sel.data('preco-float');
                    preco = preco * quantidade;
                    preco = preco - desconto;
                }
                $($field_total).maskMoney('mask', preco);
//                $($field_total ).maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});
            });
        });

        //seleção do selos
        $(document).ready(function(){
            var remoteDataConfigSelo = {
                width: 'resolve',
                ajax: {
                    url: "{{url('getSelosDisponiveis')}}",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            value   : params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                language: "pt-BR"
//                templateResult: formatState
            };
            var remoteDataConfigLacres = {
                width: 'resolve',
                ajax: {
                    url: "{{url('getLacresDisponiveis')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value   : params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                language: "pt-BR"
//                templateResult: formatState
            };
            $(".select2_single-ajax").select2(remoteDataConfigSelo);
            $(".select2_multiple-ajax").select2(remoteDataConfigLacres);
        });
    </script>
    <script>
        //ABRE MODAL INSTRUMENTO.
        var $_ID_ORDEM_SERVICO = {{$OrdemServico->idordem_servico}};
        $(document).ready(function () {
            $('div#modalPopupAparelho').on('show.bs.modal', function (e) {
                var $origem = $(e.relatedTarget);
                var aparelho_ = $($origem).data('aparelho');
                var cliente_numero_chamado = $($origem).data('numero_chamado');
                var urlfoto_ = $($origem).data('urlfoto');
                console.log(cliente_numero_chamado);
                console.log(aparelho_);
                if ($($origem).data('tipo') == 'instrumento') {
                    $(this).find('div.perfil ul.instrumento').show();
                    var titulo = 'Instrumento (#' + aparelho_['idinstrumento'] + ')';
                    var campos = ['marca', 'modelo', 'numero_serie', 'inventario', 'patrimonio', 'ano', 'portaria', 'divisao', 'capacidade', 'ip', 'endereco', 'setor'];
                    var id = aparelho_['idinstrumento'];
                    aparelho_.marca = aparelho_.base.modelo.marca.descricao;
                    aparelho_.modelo = aparelho_.base.modelo.descricao;
                    aparelho_.setor = aparelho_.setor.descricao;
                    aparelho_.descricao = aparelho_.base.descricao;
                    aparelho_.divisao = aparelho_.base.divisao;
                    aparelho_.portaria = aparelho_.base.portaria;
                    aparelho_.capacidade = aparelho_.base.capacidade;
                    var _URL_ = "{{route('ordem_servicos.instrumentos.adiciona',['_IDOS_','_ID_'])}}";
                } else {
                    $(this).find('div.perfil ul.instrumento').hide();
                    var titulo = 'Equipamento (#' + aparelho_['idequipamento'] + ')';
                    var campos = ['marca', 'modelo', 'numero_serie'];
                    var id = aparelho_['idequipamento'];
                    aparelho_.marca = aparelho_.marca.descricao;
                    aparelho_.modelo = aparelho_.modelo.descricao;
                    var _URL_ = "{{route('ordem_servicos.equipamentos.adiciona',['_IDOS_','_ID_'])}}";
                }

                $(this).find('img').attr('src', urlfoto_);
                $(this).find('div.modal-header h2').html(titulo);
                $(this).find('h4.brief i').html(aparelho_.descricao);

                $this = $(this);
                $(campos).each(function (i, v) {
                    $($this).find('div.perfil ul b#' + v).html(aparelho_[v]);
                });

                var href_ = _URL_.replace('_ID_', id);
                href_ = href_.replace('_IDOS_', $_ID_ORDEM_SERVICO);

                var $form = $(this).find('form').attr('action', href_);
                var $div_numero_chamado = $($form).find('div#numero_chamado');

                if (cliente_numero_chamado) {
                    //mostrar o campo NUMERO_CHAMADO e deixar como REQUIRED
                    $($div_numero_chamado).show();
                    $($div_numero_chamado).find('input').attr('required', true);
                } else {
                    $($div_numero_chamado).hide();
                    $($div_numero_chamado).find('input').attr('required', false);
                }

            });
        });
    </script>

    <!-- Lacres/Selos rompidos -->
    <script>
        var lista_selolacre = ['selo_retirado', 'selo_afixado', "lacre_retirado_livre", 'lacre_retirado[]', 'lacre_afixado[]'];
        $().ready(function(){

            $('input[name=selo_outro]').on('ifChecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                $($parent).find('input[name=selo_retirado]').attr('required',true);
                $($parent).find('input[name=selo_retirado]').attr('disabled',false);
            });
            $('input[name=selo_outro]').on('ifUnchecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                $($parent).find('input[name=selo_retirado]').attr('required',false);
                $($parent).find('input[name=selo_retirado]').attr('disabled',true);
            });

            $('input[name=lacre_outro]').on('ifChecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                $($parent).find('select[name="lacre_retirado[]"]').attr('required',false);
                $($parent).find('select[name="lacre_retirado[]"]').attr('disabled',true);

                $($parent).find('input[name=lacre_retirado_livre]').attr('required',true);
                $($parent).find('input[name=lacre_retirado_livre]').attr('disabled',false);
                $($parent).find('input[name=lacre_retirado_livre]').show();
            });
            $('input[name=lacre_outro]').on('ifUnchecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                $($parent).find('select[name="lacre_retirado[]"]').attr('required',true);
                $($parent).find('select[name="lacre_retirado[]"]').attr('disabled',false);

                $($parent).find('input[name=lacre_retirado_livre]').attr('required',false);
                $($parent).find('input[name=lacre_retirado_livre]').attr('disabled',true);
                $($parent).find('input[name=lacre_retirado_livre]').hide();
            });

            $('input[name=lacre_rompido]').on('ifChanged', function(){
                $(this).parents('div.form-group').next().toggle();
            });
            $('input[name=lacre_rompido]').on('ifChecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                $($($parent).find(':input')).each(function(i,v){
                    if($.inArray(v.name,lista_selolacre)>=0){
                        console.log(v.name);
                        $(v).attr('required',true);
                    }
                });
            });
            $('input[name=lacre_rompido]').on('ifUnchecked', function(){
                var $parent = $(this).parents('div.form-group').next();
                console.log($parent);
                $($($parent).find(':input')).each(function(i,v){
                    if($.inArray(v.name,lista_selolacre)>=0){
                        console.log(v.name);
                        $(v).attr('required',false);
                    }
                });
            });
        });
    </script>
    <!-- /Lacres/Selos rompidos -->
@endsection