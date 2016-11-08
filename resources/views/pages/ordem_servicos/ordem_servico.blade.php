@extends('layouts.template')
@section('page_content')
	@include('pages.ordem_servicos.popup.ordem_servico')
	@include('pages.ordem_servicos.popup.instrumento')
	<div class="page-title">
		<div class="title_left">
			<h3>{{$Page->titulo_primario.$Page->Targets}}</h3>
		</div>
	</div>
	<div class="x_panel">
		<div class="x_title">
			<ul class="nav navbar-right panel_toolbox">
				<li>
					<button class="btn btn-primary disabled" id="add-peca"><i class="fa fa-arrow-circle-left fa-2"></i> Anterior</button>
				</li>
				<li>
					<button class="btn btn-default"
						data-ordem_servico="{{$OrdemServico}}"
						data-situacao="{{$OrdemServico->situacao}}"
						data-cliente="{{$OrdemServico->cliente->getType()->nome_principal}}"
						data-colaborador="{{$OrdemServico->colaborador}}"
						data-toggle="modal"
						data-target="#modalPopup">
						<i class="fa fa-eye fa-2"></i> Visualizar O.S.</button>
				</li>
				<li>
					<button class="btn btn-primary disabled" id="add-peca"><i class="fa fa-arrow-circle-right fa-2"></i> Próximo</button>
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
							<input id="buscar" value="{{Request::get('busca')}}" name="busca" type="text" class="form-control" placeholder="{{$Page->Search_instrumento}}">
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit">Buscar</button>
							</span>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
			@if(isset($Buscas) && count($Buscas) > 0)
				<div class="x_panel">
					<div class="x_title">
						<h2>{{count($Buscas)}} Instrumentos encontrados</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
								<table border="0" class="table table-hover">
									<thead>
									<tr>
										<th>Imagem</th>
										<th>Descrição</th>
										<th>Marca</th>
										<th>Modelo</th>
										<th>Série</th>
										<th>Ações</th>
									</tr>
									</thead>
									<tbody>
										@foreach($Buscas as $instrumento)
											<tr>
												<td>
													<img src="{{$instrumento->getFoto()}}" class="avatar" alt="Avatar">
												</td>
												<td>{{$instrumento->descricao}}</td>
												<td>{{$instrumento->marca->descricao}}</td>
												<td>{{$instrumento->modelo}}</td>
												<td>{{$instrumento->numero_serie}}</td>
												<td>
													<a class="btn btn-primary btn-xs"
{{--													   data-href="{{route($Page->link.'.abrir',$instrumento->idinstrumento)}}"--}}
													   data-instrumento="{{$instrumento}}"
													   data-urlfoto="{{$instrumento->getFoto()}}"
													   data-toggle="modal"
													   data-target="#modalPopupInstrumento">
														<i class="fa fa-eye"></i> Visualizar </a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			@else
				@include('layouts.search.no-results')
			@endif
		</div>
	</div>
	<!-- /page content -->
@endsection
@section('scripts_content')
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