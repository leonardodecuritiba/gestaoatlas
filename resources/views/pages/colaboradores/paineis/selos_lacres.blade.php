
<?php $lista = ['selo', 'lacre'];?>
@foreach($lista as $l)
    <section class="x_panel">
        <div class="x_title">
            <h2>{{ucfirst($l)}}s encontrados</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <button class="btn btn-primary add"
                            data-option="{{$l}}"
                            data-toggle="modal"
                            data-target="#modalSeloLacre">
                        <i class="fa fa-plus-circle fa-2"></i> Lançar {{ucfirst($l).'s'}}</button>
                </li>
            </ul>
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
                                <th>Numeracao</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Colaborador->tecnico->{$l.'s'} as $sel)
                                <tr>
                                    <td>{{$sel->{'id'.$l} }}</td>
                                    <td>{{$sel->numeracao}}</td>
                                    <td>
                                        #
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
    var last_selo = parseInt('{{$Colaborador->tecnico->selos->first()->numeracao}}')+1;
    var last_lacre = parseInt('{{$Colaborador->tecnico->lacres->first()->numeracao}}')+1;
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

        $('div#modalSeloLacre').on('hide.bs.modal', function (e) {
            $(this).find('div.modal-content form').parsley().reset();
        });
        $('div#modalSeloLacre').on('show.bs.modal', function (e) {
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
            $($input_numeracao_final).attr('min',last+1);
            $($input_numeracao_final).val(last+1);
        });
    });

</script>