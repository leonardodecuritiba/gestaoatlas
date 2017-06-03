@extends('layouts.template')
@section('style_content')
	<!-- icheck -->
	{!! Html::style('css/icheck/flat/green.css') !!}
@endsection
@section('page_content')
	@include('pages.ordem_servicos.popup.ordem_servico')
	@include('pages.ordem_servicos.popup.aparelho')
	@include('pages.ordem_servicos.popup.tecnico_valores')

	<div class="page-title">
		<div class="title_left">
			<h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
		</div>
	</div>
	<section class="row">
		<div class="x_panel">
			<section class="x_content">
				<div class="alert fade in alert-{{$OrdemServico->getStatusType()}}" role="alert">
					Status da Ordem de Serviço: <b>{{$OrdemServico->situacao->descricao}}</b>
				</div>
				@include('pages.ordem_servicos.parts.cliente_show')
				@role('tecnico')
                @if(!$OrdemServico->status())
                    <section class="row form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Aplicação de Valores: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <button class="btn btn-warning"
                                        data-toggle="modal"
                                        data-target="#modalTecnicoValores"><i class="fa fa-money fa-2"></i> Aplicar
                                    Desconto/Acrescimo
                                </button>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                    </section>
                @endif
				@endrole

				@if(!$OrdemServico->status())
					<section class="row">
						{!! Form::open(['route' => [$Page->link.'.finalizar',$OrdemServico->idordem_servico],
                            'method' => 'POST',
                            'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
						<div class="form-group">
							<div class="alert-custos_isento alert alert-danger fade in @if(isset($OrdemServico) && ($OrdemServico->custos_isento == 0)) esconda @endif"
								 role="alert">
								<strong><i class="fa fa-exclamation-triangle"></i> Atenção!</strong> Esta O.S. está
								sendo isentada de custos com Deslocamentos, Pedágios e Outros Custos.
							</div>
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nº do Chamado: <span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input name="numero_chamado" type="text" maxlength="100" class="form-control"
									   value="{{(isset($OrdemServico))?$OrdemServico->numero_chamado:old('numero_chamado')}}" required>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<div class="checkbox">
									<label>
										<input name="custos_isento" type="checkbox" class="flat"
											   @if(isset($OrdemServico) && ($OrdemServico->custos_isento == 1)) checked="checked" @endif
										> Isenção de Custos
									</label>
								</div>
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
									<button class="btn btn-success btn-lg btn-block"><i class="fa fa-sign-out fa-2"></i>
										Finalizar
									</button>
                                </div>
                            @endif
                        </div>
						@if(!$OrdemServico->status())
						{!! Form::close() !!}
					</section>
				@endif
			</section>
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

        $(document).ready(function () {
            $('select[name=tipo]').change(function () {
                var valor = parseFloat($(this).find(':selected').data('valor')) * 100;
                $(this).parents('div.form-group').next().find("input[name=valor]").maskMoney('mask', valor);
            });
        });
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
        //ISENÇÃO DE DESLOCAMENTO
        $('input[name="custos_isento"]').on('ifToggled', function (event) {
            var $alert = $(this).parents().find('div.alert-custos_isento');
            if (this.checked) {
                $($alert).show();
            } else {
                $($alert).hide();
            }
        });
	</script>
@endsection