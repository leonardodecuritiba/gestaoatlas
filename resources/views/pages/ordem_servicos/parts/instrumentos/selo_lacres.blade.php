<section class="row">
    <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="profile_img">
            <img class="img-responsive avatar-view" src="{{$Instrumento->getFoto()}}">
        </div>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-12">
        @include('pages.ordem_servicos.parts.instrumentos.descricao')
    </div>
</section>
<div class="ln_solid"></div>
@if(($AparelhoManutencao->defeito != '') && ($AparelhoManutencao->solucao != ''))
    <section class="row animated fadeInDown">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>Defeito:</h2>
            <p>
                {{$AparelhoManutencao->defeito}}
            </p>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Selo Retirado:</h2>
                <p class="green">{{$AparelhoManutencao->numeracao_selo_retirado()}}</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Selo Afixado:</h2>
                <p class="green">{{$AparelhoManutencao->numeracao_selo_afixado()}}</p>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>Solução:</h2>
            <p>
                {{$AparelhoManutencao->solucao}}
            </p>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Lacres Retirados:</h2>
                <p class="green">{{$AparelhoManutencao->numeracao_lacres_retirados()}}</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h2>Lacres Afixados:</h2>
                <p class="green">{{$AparelhoManutencao->numeracao_lacres_afixados()}}</p>
            </div>
        </div>
    </section>
@else
    <div class="alert alert-danger fade in" role="alert">
        <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alerta!</strong>
        Nenhum defeito/solução cadastrado.
    </div>
@endif