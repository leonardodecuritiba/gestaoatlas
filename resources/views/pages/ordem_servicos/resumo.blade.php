@extends('layouts.template')
@section('page_content')
	@include('pages.ordem_servicos.popup.ordem_servico')
	@include('pages.ordem_servicos.popup.aparelho')

	<div class="page-title">
		<div class="title_left">
			<h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
		</div>
	</div>
	<section class="row">
		<div class="x_panel">
			<div class="x_content">
				<div class="alert fade in alert-{{$OrdemServico->getStatusType()}}" role="alert">
					Status da Ordem de Serviço: <b>{{$OrdemServico->situacao->descricao}}</b>
				</div>
				@include('pages.ordem_servicos.parts.cliente_show')
                <section class="row">
					@if(!$OrdemServico->status())
                    {!! Form::open(['route' => [$Page->link.'.fechar',$OrdemServico->idordem_servico],
                        'method' => 'POST',
                        'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nº do Chamado: <span class="required">*</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input name="numero_chamado" type="text" maxlength="100" class="form-control"
									   value="{{(isset($OrdemServico))?$OrdemServico->numero_chamado:old('numero_chamado')}}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome Responsável: <span
										class="required">*</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input name="responsavel" type="text" maxlength="100" class="form-control"
									   value="{{(isset($OrdemServico))?$OrdemServico->responsavel:old('responsavel')}}"
									   required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">CPF: <span
										class="required">*</span></label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<input name="responsavel_cpf" type="text" maxlength="16" class="form-control show-cpf"
									   value="{{(isset($OrdemServico))?$OrdemServico->responsavel_cpf:old('responsavel_cpf')}}"
									   required>
							</div>
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cargo: <span
										class="required">*</span></label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<input name="responsavel_cargo" type="text" maxlength="50" class="form-control"
									   value="{{(isset($OrdemServico))?$OrdemServico->responsavel_cargo:old('responsavel_cargo')}}"
									   required>
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
								<div class="col-md-4 col-sm-4 col-xs-12">
									<a target="_blank"
									   href="{{route('ordem_servicos.imprimir',$OrdemServico->idordem_servico)}}"
									   class="btn btn-default btn-lg btn-block"><i class="fa fa-print fa-2"></i>
										Imprimir</a>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-12">
                                    <a href="{{route('ordem_servicos.show',$OrdemServico->idordem_servico)}}"
                                       class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left fa-2"></i> Editar</a>
                                </div>
								<div class="col-md-4 col-sm-4 col-xs-12 ">
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
			@if($AparelhoManutencao->idinstrumento != NULL)
                <?php $Instrumento = $AparelhoManutencao->instrumento;?>
				@include('pages.ordem_servicos.parts.instrumentos.resume')
			@else
                <?php $Equipamento = $AparelhoManutencao->equipamento;?>
				@include('pages.ordem_servicos.parts.equipamentos.resume')
			@endif
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
        //ABRE MODAL APARELHO.
        $('div#modalPopupAparelho').on('show.bs.modal', function (e) {
            $origem = $(e.relatedTarget);
            aparelho_ = $($origem).data('aparelho');
            href_ = $($origem).data('href');
            urlfoto_ = $($origem).data('urlfoto');
            aparelho_.marca = aparelho_.marca.descricao;
            console.log(aparelho_);
            if ($($origem).data('tipo') == 'instrumento') {
                $(this).find('div.perfil ul.instrumento').show();
                titulo = 'Instrumento (#' + aparelho_['idinstrumento'] + ')';
                campos = ['marca', 'modelo', 'numero_serie', 'inventario', 'patrimonio', 'ano', 'portaria', 'divisao', 'capacidade', 'ip', 'endereco', 'setor'];
            } else {
                $(this).find('div.perfil ul.instrumento').hide();
                titulo = 'Equipamento (#' + aparelho_['idequipamento'] + ')';
                campos = ['marca', 'modelo', 'numero_serie'];
            }

            $(this).find('img').attr('src', urlfoto_);
            $(this).find('div.modal-header h2').html(titulo);
            $(this).find('h4.brief i').html(aparelho_.descricao);

            $this = $(this);
            $(campos).each(function (i, v) {
                $($this).find('div.perfil ul b#' + v).html(aparelho_[v]);

            });
            $(this).find('.btn-ok').attr("href", href_);
        });
	</script>
@endsection