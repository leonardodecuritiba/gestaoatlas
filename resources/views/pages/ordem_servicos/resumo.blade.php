@extends('layouts.template')
@section('page_content')
	@include('pages.ordem_servicos.popup.ordem_servico')
	@include('pages.ordem_servicos.popup.instrumento')

	<div class="page-title">
		<div class="title_left">
			<h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
		</div>
	</div>
	<section class="row">
		<div class="x_panel">
			<div class="x_content">
				<div class="alert fade in <?php
                switch ($OrdemServico->idsituacao_ordem_servico) {
                    case config('situacao_os.ABERTA'):
                        echo 'alert-success';
                        break;
                    case config('situacao_os.ATENDIMENTO EM ANDAMENTO'):
                        echo 'alert-warning';
                        break;
                    case config('situacao_os.FINALIZADA'):
                        echo 'alert-danger';
                        break;
                }
                ?>" role="alert">
					Status da Ordem de Serviço: {{$OrdemServico->situacao->descricao}}
				</div>
                <div class="profile_details">
                    <div class="well">
                        <div class="perfil">
							{{$OrdemServico->status()}}
                            <h4>Cliente: <i>{{$OrdemServico->cliente->getType()->nome_principal}}</i></h4>
							<ul class="list-unstyled">
								<li><i class="fa fa-info"></i> Nº da O.S.: <b>{{$OrdemServico->idordem_servico}}</b>
								</li>
								<li><i class="fa fa-calendar"></i> Data de Abertura:
									<b>{{$OrdemServico->created_at}}</b></li>
								@if($OrdemServico->status())
									<li><i class="fa fa-calendar-o"></i> Data de Fechamento: <b>{{$OrdemServico->fechamento}}</b></li>
								@endif
								<li><i class="fa fa-warning"></i> Situação:
									<b>{{$OrdemServico->situacao->descricao}}</b></li>
								<li><i class="fa fa-user"></i> Colaborador: <b>{{$OrdemServico->colaborador->nome}}</b>
								</li>
								@if($OrdemServico->status())
									<li><i class="fa fa-info"></i> Nº do chamado: <b>{{$OrdemServico->numero_chamado}}</b></li>
								@endif
							</ul>
							<ul class="list-unstyled">
								<li><i class="fa fa-money"></i> Custos Deslocamento:
									<b>R$ {{$OrdemServico->custos_deslocamento}}</b></li>
								<li><i class="fa fa-money"></i> Pedágios: <b>R$ {{$OrdemServico->pedagios}}</b></li>
								<li><i class="fa fa-money"></i> Outros Custos:
									<b>R$ {{$OrdemServico->outros_custos}}</b></li>
								<li><i class="fa fa-money"></i> Valor O.S: <b>R$ {{$OrdemServico->valor_total}}</b></li>
								<li><i class="fa fa-money"></i> Valor Total: <b>R$ {{$OrdemServico->valor_final}}</b>
								</li>
							</ul>
                        </div>
                    </div>
                </div>
                <section class="row">
					@if(!$OrdemServico->status())
                    {!! Form::open(['route' => [$Page->link.'.fechar',$OrdemServico->idordem_servico],
                        'method' => 'POST',
                        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nº do Chamado: <span class="required">*</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input name="numero_chamado" type="text" maxlength="100" class="form-control col-md-7 col-xs-12"
									   value="{{(isset($OrdemServico))?$OrdemServico->numero_chamado:old('numero_chamado')}}" required>
							</div>
						</div>
					@endif
                        <div class="ln_solid"></div>
                        <div class="form-group">
							@if($OrdemServico->status())
								<div class="col-md-6 col-sm-6 col-xs-12">
									<a target="_blank"
									   href="{{route('ordem_servicos.imprimir',$OrdemServico->idordem_servico)}}"
									   class="btn btn-default btn-lg btn-block"><i class="fa fa-print fa-2"></i>
										Imprimir</a>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<a href="{{route('ordem_servicos.encaminhar',$OrdemServico->idordem_servico)}}"
									   class="btn btn-primary btn-lg btn-block"><i class="fa fa-envelope fa-2"></i>
										Encaminhar</a>
								</div>
							@else
								<div class="col-md-6 col-sm-6 col-xs-12">
                                    <a href="{{route('ordem_servicos.show',$OrdemServico->idordem_servico)}}"
                                       class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left fa-2"></i> Editar</a>
                                </div>
								<div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <button class="btn btn-success btn-lg btn-block"><i class="fa fa-sign-out fa-2"></i> Fechar</button>
                                </div>
                            @endif
                        </div>
					@if(!$OrdemServico->status())
						{!! Form::close() !!}
					@endif
                </section>
			</div>
		</div>
	</section>
	@foreach($OrdemServico->aparelho_manutencaos as $AparelhoManutencao)
		<?php $Instrumento = $AparelhoManutencao->instrumento;?>
		<section class="row">
			<div class="x_panel">
				<div class="x_title">
					<h2>
						Instrumento
						<small>#{{$Instrumento->idinstrumento}}</small>
					</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="profile_img">
								<img class="img-responsive avatar-view" src="{{$Instrumento->getFoto()}}">
							</div>
						</div>
						<div class="col-md-9 col-sm-9 col-xs-12">
							@include('pages.ordem_servicos.parts.instrumento_descricao')
						</div>
					</div>
					<div class="ln_solid"></div>

					<div class="row">
						<div class="x_panel">
							<div class="x_title">
								<h2>Resumo</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<h2>Defeito:</h2>
									<p>{{$AparelhoManutencao->defeito}}</p>
									<h2>Selo Afixado:</h2>
									<p class="green">{{$AparelhoManutencao->instrumento->selo_afixado_numeracao() }}</p>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<h2>Solução:</h2>
									<p>{{$AparelhoManutencao->solucao}}</p>
									<h2>Lacres Afixados:</h2>
									<p class="green">{{$AparelhoManutencao->instrumento->lacres_afixados_valores()}}</p>
								</div>
							</div>
						</div>
					</div>


					{{--SERVIÇOS--}}
					<div class="row">
						@include('pages.ordem_servicos.insumos.servicos')
					</div>

					{{--PEÇAS/PRODUTOS--}}
					<div class="row">
						@include('pages.ordem_servicos.insumos.pecas')
					</div>

					{{--KITS--}}
					<div class="row">
						@include('pages.ordem_servicos.insumos.kits')
					</div>
				</div>
			</div>
		</section>
	@endforeach
	<!-- /page content -->
@endsection
@section('scripts_content')
    {!! Html::script('js/parsley/parsley.min.js') !!}
	<script>
		//ABRE MODAL O.S.
		$(document).ready(function() {
			$('div#modalPopup').on('show.bs.modal', function(e) {
				$origem 		= $(e.relatedTarget);
				ordem_servico_ 	= $($origem).data('ordem_servico');
				cliente_ 		= $($origem).data('cliente');
				situacao_ 		= $($origem).data('situacao');
				colaborador_ 	= $($origem).data('colaborador');

				idordem_servico = ordem_servico_.idordem_servico;
				data_abertura 	= ordem_servico_.created_at;
				situacao 		= situacao_.descricao;
				colaborador 	= colaborador_.nome;

				$el 		= $($origem).data('elemento');

				$(this).find('h2.brief i').html('Ordem de Serviço');
				$(this).find('div.perfil h4 i').html(cliente_);
				$(this).find('div.perfil ul b#idordem_servico').html(idordem_servico);
				$(this).find('div.perfil ul b#data_abertura').html(data_abertura);
				$(this).find('div.perfil ul b#situacao').html(situacao);
				$(this).find('div.perfil ul b#colaborador').html(colaborador);
			});
		});
		//ABRE MODAL INSTRUMENTO.
		$(document).ready(function() {
			$('div#modalPopupInstrumento').on('show.bs.modal', function(e) {
				$origem 		= $(e.relatedTarget);
				instrumento_ 	= $($origem).data('instrumento');
//				href_ 			= $($origem).data('href');
				urlfoto_ 		= $($origem).data('urlfoto');


				titulo = 'Instrumento';

				$(this).find('img').attr('src',urlfoto_);
				$(this).find('h4.brief i').html(titulo);
				$(this).find('div.perfil h2').html(instrumento_.descricao);

				$this = $(this);
				campos = ['modelo','numero_serie','inventario','patrimonio','ano','portaria','divisao','capacidade','ip','endereco','setor'];
				$(campos).each(function(i,v){
					$($this).find('div.perfil ul b#' + v).html(instrumento_[v]);

				});
//				$(this).find('.btn-ok').attr("href", href_);
			});
		});
	</script>
@endsection