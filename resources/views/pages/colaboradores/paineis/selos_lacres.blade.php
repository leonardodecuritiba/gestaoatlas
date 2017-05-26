<?php $lista = ['selo', 'lacre'];?>
@foreach($lista as $l)
    <section class="x_panel">
        <div class="x_title">
            <h2>{{ucfirst($l)}}s encontrados</h2>
            @role(['admin','financeiro'])
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <button class="btn btn-primary add"
                            data-option="{{$l}}"
                            data-toggle="modal"
                            data-target="#modalAdicionarSeloLacre">
                        <i class="fa fa-plus-circle fa-2"></i> Lançar {{ucfirst($l).'s'}}</button>
                </li>
                <li>
                    <button class="btn btn-warning add"
                            data-option="{{$l}}"
                            data-toggle="modal"
                            data-target="#modalRepassarSeloLacre">
                        <i class="fa fa-minus-circle fa-2"></i> Repassar {{ucfirst($l).'s'}}</button>
                </li>
            </ul>
            @endrole
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                @if($Colaborador->tecnico->{'has_'.$l.'s'}())
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            {{--<table border="0" class="table table-hover">--}}
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Numeração</th>
                                @if(strcmp($l, 'selo')==0)
                                    <td>DV</td>
                                @endif
                                <th>Numeração (externa)</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Colaborador->tecnico->{$l.'s'} as $sel)
                                <tr>
                                    <td>{{$sel->{'id'.$l} }}</td>
                                    @if(strcmp($l, 'selo')==0)
                                        <td>{{$sel->getFormatedSelo()}}</td>
                                        <td>{{$sel->getDV()}}</td>
                                    @else
                                        <td>{{$sel->numeracao}}</td>
                                    @endif
                                    <td>{{$sel->numeracao_externa}}</td>
                                    <td>@if($sel->used)
                                            <button class="btn btn-danger btn-xs">Usado</button>
                                        @else
                                            <button class="btn btn-success btn-xs">Disponível</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="jumbotron">
                        <p>Este colaborador não possui nenhum {{$l}} cadastrado.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endforeach
<script>
    //ABRE MODAL SELO-LACRE
    var last_selo = parseInt('{{$Colaborador->tecnico->last_selo()}}') + 1;
    var last_lacre = parseInt('{{$Colaborador->tecnico->last_lacre()}}') + 1;
    $(document).ready(function () {
        $('form#form-selolacre').on('submit', function (e) {
            var $input_numeracao_inicial = $(this).find('input[name=numeracao_inicial]');
            var $input_numeracao_final = $(this).find('input[name=numeracao_final]');
            var t = $input_numeracao_final.val() - $input_numeracao_inicial.val();
            if(t > 100){
                new PNotify({
                    title: 'Ops!',
                    text: 'Você não pode inserir lotes negativos ou maiores que 100 valores!',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                return false;
            }
        });

        $('div#modalAdicionarSeloLacre').on('hide.bs.modal', function (e) {
            $(this).find('div.modal-content form').parsley().reset();
        });
        $('div#modalAdicionarSeloLacre').on('show.bs.modal', function (e) {
            var $origem = $(e.relatedTarget);
            var opcao = $($origem).data('option');
            $(this).find('div.modal-header h4.modal-title').html('Lançar ' + opcao + 's');
            $(this).find('input[name=opcao]').val(opcao);

            var $input_numeracao_inicial = $(this).find('input[name=numeracao_inicial]');
            var $input_numeracao_final = $(this).find('input[name=numeracao_final]');
            if(opcao=='selo'){
                var last = last_selo;
            } else {
                var last = last_lacre;
            }
            $($input_numeracao_inicial).attr('min',last);
            $($input_numeracao_inicial).val(last);
            $($input_numeracao_final).attr('min', last);
            $($input_numeracao_final).val(last+1);
        });
        $('div#modalRepassarSeloLacre').on('hide.bs.modal', function (e) {
            $(this).find('div.modal-content form').parsley().reset();
        });
        $('div#modalRepassarSeloLacre').on('show.bs.modal', function (e) {
            var $origem = $(e.relatedTarget);
            var opcao = $($origem).data('option');
            $(this).find('div.modal-header h4.modal-title').html('Repassar ' + opcao + 's');
            $(this).find('input[name=opcao]').val(opcao);
            $(this).find('p.obs').html('Obs: Somente serão repassados os ' + opcao + 's' + ' disponíveis.');

            //
//            var $input_numeracao_inicial = $(this).find('input[name=numeracao_inicial]');
//            var $input_numeracao_final = $(this).find('input[name=numeracao_final]');
//            if(opcao=='selo'){
//                var last = last_selo;
//            } else {
//                var last = last_lacre;
//            }
//            $($input_numeracao_inicial).attr('min',last);
//            $($input_numeracao_inicial).val(last);
//            $($input_numeracao_final).attr('min',last+1);
//            $($input_numeracao_final).val(last+1);
        });
    });

</script>