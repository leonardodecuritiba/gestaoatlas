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
@section('page_content')
    <?php $Colaborador = \App\Colaborador::find(2); ?>
    @include('layouts.modals.delete')
    @include('pages.ordem_servicos.popup.ordem_servico')
    @include('pages.ordem_servicos.popup.instrumento')
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
                <h2>Adicionar Instrumentos</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-default collapse-link">
                            <i class="fa fa-chevron-down fa-2"></i> Adicionar
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-default"
                                data-ordem_servico="{{$OrdemServico}}"
                                data-situacao="{{$OrdemServico->situacao}}"
                                data-cliente="{{$OrdemServico->cliente->getType()->nome_principal}}"
                                data-colaborador="{{$OrdemServico->colaborador}}"
                                data-valores="{{$OrdemServico->getValores()}}"
                                data-toggle="modal"
                                data-target="#modalPopup">
                            <i class="fa fa-eye fa-2"></i> Visualizar O.S.
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div id="search" class="animated flipInX">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            {!! Form::open(array('route'=>array($Page->link.'.instrumentos.busca',$OrdemServico->idordem_servico),'method'=>'GET','id'=>'search')) !!}
                            <div class="col-md-12 col-sm-12 col-xs-12 input-group input-group-lg">
                                <input id="buscar" value="{{Request::get('busca')}}" name="busca" type="text"
                                       class="form-control" placeholder="{{$Page->Search_instrumento}}">
                                <span class="input-group-btn">
								<button class="btn btn-info" type="submit">Buscar</button>
							</span>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                @if(isset($Buscas) && count($Buscas) > 0)
                    @include('pages.ordem_servicos.parts.aparelho_resultados')
                @else
                    @include('layouts.search.no-results')
                @endif
            </div>
        </div>
    </section>
        @if($OrdemServico->has_aparelho_manutencaos())
            @foreach($OrdemServico->aparelho_manutencaos as $AparelhoManutencao)
                <?php $Instrumento = $AparelhoManutencao->instrumento;?>
                <section class="row">
                    @include('pages.ordem_servicos.parts.aparelho_manutencaos')
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
    </script>
    <!-- jQuery Tags Input -->
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
                data.text = $($parent).find('select#' + id_select).find(":selected").html();
//                    data.valor_original = $($parent).find('select#' + id_select).find(":selected").data('valor');
                data.preco = $($parent).find('select#' + id_select).find(":selected").data('preco');
                data.preco_minimo = $($parent).find('select#' + id_select).find(":selected").data('preco_minimo');
                data.id = $($parent).find('select#' + id_select).find(":selected").val();
                data.valor = $($parent).find('input#valor').val();
                x++;
                var campo = '<tr>' +
                    '<input name="' + id_select + '_valor[' + (x) + ']" type="hidden" value="' + data.valor + '" required>' +
                    '<input name="' + id_select + '_id[' + (x) + ']" type="hidden" value="' + data.id + '" required>' +
                    '<td>' + data.text + '</td>' +
                    '<td>R$ ' + data.preco + '</td>' +
                    '<td>R$ ' + data.preco_minimo + ' </td>' +
                    '<td>R$ ' + data.valor + ' </td>' +
                    '<td>' +
                    '<a class="btn btn-danger" onclick="removeEl(this)" title="Excluir">' +
                    '<i class="fa fa-trash fa-lg"></i></a>' +
                    '</td>' +
                    '</tr>';
                $(campo).insertBefore($parent);
            });
        });
        $(document).ready(function () {
            //Select
            $(".select2_single").on("select2:select", function() {
                //achar parent, pegar próximo td e escrever o valor
                var $sel = $(this).find(":selected");
                var $td_preco = $(this).parents('td').next();
                var $td_preco_minimo = $($td_preco).next();
                var $field_preco = $($td_preco_minimo).next().find('input[name=valor]');
                var preco = '#';
                var preco_minimo = '#';
                if($($sel).val()!=''){
                    preco = $sel.data('preco');
                    preco_minimo = $sel.data('preco_minimo');
                    $($field_preco).val(preco);
                    preco = 'R$ ' + preco;
                    preco_minimo = 'R$ ' + preco_minimo;
                }
                $($td_preco).html(preco);
                $($td_preco_minimo).html(preco_minimo);
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
        //ABRE MODAL O.S.
        $(document).ready(function () {
            $('div#modalPopup').on('show.bs.modal', function (e) {
                $origem = $(e.relatedTarget);
                ordem_servico_ = $($origem).data('ordem_servico');
                cliente_ = $($origem).data('cliente');
                situacao_ = $($origem).data('situacao');
                colaborador_ = $($origem).data('colaborador');
                valores_ = $($origem).data('valores');

                idordem_servico = ordem_servico_.idordem_servico;
                data_abertura = ordem_servico_.created_at;
                situacao = situacao_.descricao;
                colaborador = colaborador_.nome;

                $el = $($origem).data('elemento');

                $(this).find('h2.brief i').html('Ordem de Serviço');
                $(this).find('div.perfil h4 i').html(cliente_);
                $(this).find('div.perfil ul b#idordem_servico').html(idordem_servico);
                $(this).find('div.perfil ul b#data_abertura').html(data_abertura);
                $(this).find('div.perfil ul b#situacao').html(situacao);
                $(this).find('div.perfil ul b#colaborador').html(colaborador);

                $(this).find('div.perfil ul b#valor_total_servicos').html('R$ ' + valores_.valor_total_servicos);
                $(this).find('div.perfil ul b#valor_total_pecas').html('R$ ' + valores_.valor_total_pecas);
                $(this).find('div.perfil ul b#valor_total_kits').html('R$ ' + valores_.valor_total_kits);
                $(this).find('div.perfil ul b#valor_deslocamento').html('R$ ' + valores_.valor_deslocamento);
                $(this).find('div.perfil ul b#pedagios').html('R$ ' + valores_.pedagios);
                $(this).find('div.perfil ul b#outros_custos').html('R$ ' + valores_.outros_custos);
                $(this).find('div.perfil ul b#valor_final').html('R$ ' + valores_.valor_final);

            });
        });
        //ABRE MODAL INSTRUMENTO.
        $(document).ready(function () {
            $('div#modalPopupInstrumento').on('show.bs.modal', function (e) {
                $origem = $(e.relatedTarget);
                instrumento_ = $($origem).data('instrumento');
                href_ = $($origem).data('href');
                urlfoto_ = $($origem).data('urlfoto');


                titulo = 'Instrumento';

                $(this).find('img').attr('src', urlfoto_);
                $(this).find('h4.brief i').html(titulo);
                $(this).find('div.perfil h2').html(instrumento_.descricao);

                $this = $(this);
                campos = ['modelo', 'numero_serie', 'inventario', 'patrimonio', 'ano', 'portaria', 'divisao', 'capacidade', 'ip', 'endereco', 'setor'];
                $(campos).each(function (i, v) {
                    $($this).find('div.perfil ul b#' + v).html(instrumento_[v]);

                });
                $(this).find('.btn-ok').attr("href", href_);
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