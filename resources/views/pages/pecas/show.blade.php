@extends('layouts.template')
@section('style_content')
    <!-- icheck -->
    {!! Html::style('css/icheck/flat/green.css') !!}
    <!-- Select2 -->
    {!! Html::style('vendors/select2/dist/css/select2.min.css') !!}
@endsection
@section('page_content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
            {!! Form::open(['route'=>[$Page->link.'.update',$Peca->idpeca],
                'method' => 'PATCH',
                'files' => true,
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <section class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Dados do {{$Page->Target}}</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="form-horizontal form-label-left">
                                    @include('pages.'.$Page->link.'.forms.form')
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @role('admin')
                    <section class="row">
                        <div class="form-horizontal form-label-left">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <a href="{{route($Page->link.'.index')}}" class="btn btn-danger btn-lg btn-block">Cancelar</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </section>
                @endrole
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('scripts_content')
    <!-- form validation -->
    {!! Html::script('js/parsley/parsley.min.js') !!}
    <!-- Select2 -->
    {!! Html::script('vendors/select2/dist/js/select2.min.js') !!}
    {!! Html::script('vendors/select2/dist/js/i18n/pt-BR.js') !!}
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });

        });

        //seleção do ncm
        $(document).ready(function(){
            $($container_form).find('div#tributacao select[name=idncm]').change(function(){
//            $(this).find(':selected').data('id')
                $select = $(this);
                $parent = $($select).parents('div.form-group');
                $data = $NCM_OPTION[0].data;

                valor = parseFloat($data.aliquota_nacional);
                $($parent).find('input[name=aliquota_nacional]').maskMoney('mask', valor*100);

                valor = parseFloat($data.aliquota_importacao);
                $($parent).find('input[name=aliquota_importacao]').maskMoney('mask', valor*100);
            });
            @if(isset($Peca->tributacao->idncm))

            //custo_reais
            $parent = $($container_form).find('div#custo_reais');
            valor = $($parent).find('input[name=custo_final]').val();
            $($parent).find('input[name=custo_final]').maskMoney('mask', valor);

            //custo_dolar
            $parent = $($container_form).find('div#custo_dolar');
            valor = $($parent).find('input[name=preco_final]').val();
            $($parent).find('input[name=preco_final]').maskMoney('mask', valor);



            {{--            $($parent).find('input[name=custo_final]').maskMoney('mask', '{{$Peca->tributacao->idncm}}');--}}


                    idncm = "{{$Peca->tributacao->idncm}}";
            $.ajax({
                url: "{{route('getAjaxDataByID')}}",
                type: 'GET',
                data: {
                    id      : idncm,
                    table   : 'ncm',
                    pk      : 'idncm',
                    retorno : 'aliquota_nacional,aliquota_importacao'},
                dataType: "json",
                beforeSend: function (xhr, textStatus) {
                    $('.loading').show();
                },
                error: function (xhr, textStatus) {
                    console.log('xhr-error: ' + xhr.responseText);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    $('.loading').hide();
                    console.log(json);

                    if(json.status==1) {
                        $obj = json.response[0];
                        $parent = $($container_form).find('div#tributacao');

                        $($parent).find('input[name=aliquota_nacional]').maskMoney('mask', $obj.aliquota_nacional*100);
                        $($parent).find('input[name=aliquota_importacao]').maskMoney('mask', $obj.aliquota_importacao*100);
                    }
                }
            });
            @endif
        });
        $(document).ready(function(){

            $NCM_OPTION = [];
            var remoteDataConfig = {
                width: 'resolve',
                ajax: {
                    url: "{{url('ncm_ajax')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term, // search term
                            field: 'codigo',
                            table: 'ncm',
                            pk: 'idncm',
                            action: 'busca_por_campo'
                        };
                    },
                    processResults: function (data) {
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        $NCM_OPTION = data;
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

            $(".select2_single-ajax").select2(remoteDataConfig);

        });
    </script>
@endsection