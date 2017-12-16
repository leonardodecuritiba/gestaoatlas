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
        @if($AparelhoManutencao->numero_chamado != NULL)
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2>Número do chamado:</h2>
                <p>
                    {{$AparelhoManutencao->numero_chamado}}
                </p>
            </div>
        @endif
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>Defeito:</h2>
            <p>
                {{$AparelhoManutencao->defeito}}
            </p>
            @if($AparelhoManutencao->has_selo_afixado())
				<?php
				$numeracao_selo_afixado = $AparelhoManutencao->numeracao_selo_afixado();
				$numeracao_selo_retirado = $AparelhoManutencao->numeracao_selo_retirado();
		        $numeracao_lacres_afixados = $AparelhoManutencao->numeracao_lacres_afixados();
		        $numeracao_lacres_retirados = $AparelhoManutencao->numeracao_lacres_retirados();
				//                    dd($numeracao_selo_retirado);
				?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Selo Retirado:</h2>
                    <p class="green">{{$numeracao_selo_retirado['text']}}</p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Selo Afixado:</h2>
                    <p class="green">{{$numeracao_selo_afixado['text']}}</p>
                </div>
            @else
                <div class="alert-custos_isento alert alert-danger fade in"
                     role="alert">
                    <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Nenhum Selo Retirado.
                </div>
            @endif
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>Solução:</h2>
            <p>
                {{$AparelhoManutencao->solucao}}
            </p>
            @if($AparelhoManutencao->has_lacres_afixados())
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Lacres Retirados:</h2>
                    <p class="green">{{$numeracao_lacres_retirados['text']}}</p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Lacres Afixados:</h2>
                    <p class="green">{{$numeracao_lacres_afixados['text']}}</p>
                </div>
            @else
                <div class="alert-custos_isento alert alert-danger fade in"
                     role="alert">
                    <strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Nenhum Lacre Retirado.
                </div>
            @endif
        </div>
    </section>
@else
    <div class="alert alert-danger fade in" role="alert">
        <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alerta!</strong>
        Nenhum defeito/solução cadastrado.
    </div>
@endif