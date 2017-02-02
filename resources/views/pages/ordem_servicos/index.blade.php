@extends('layouts.template')
@section('page_content')
	@include('pages.ordem_servicos.popup.cliente')
	<!-- Seach form -->
	@include('layouts.search.form')
	<!-- Upmenu form -->
	@if(count($Buscas) > 0)
		<div class="x_panel">
			<div class="x_title">
				<h2>{{$Page->Targets}} encontrados</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
						<table border="0" class="table table-hover">
							<thead>
							<tr>
								<th>Situação</th>
								<th>ID</th>
								<th>Nº Chamado</th>
								<th>Data de Abertura</th>
								<th>Técnico</th>
								<th>Cliente</th>
								<th>Ações</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($Buscas as $selecao)
								<tr>
									<td>
										<button class="btn btn-xs
										<?php
										switch ($selecao->idsituacao_ordem_servico) {
											case '1':
												echo 'btn-success';
												break;
											case '2':
												echo 'btn-warning';
												break;
                                            case '3':
												echo 'btn-danger';
												break;
										}
										?>"
										>{{$selecao->situacao->descricao}}</button>
									</td>
									<td>{{$selecao->idordem_servico}}</td>
									<td>{{$selecao->numero_chamado}}</td>
									<td>{{$selecao->created_at}}</td>
									<td>{{$selecao->colaborador->nome}}</td>
									<td>{{$selecao->cliente->getType()->nome_principal}}</td>
									<td>
										<a class="btn btn-primary btn-xs"
										   href="{{route('ordem_servicos.show',$selecao->idordem_servico)}}">
											<i class="fa fa-eye"></i> Abrir</a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
						<div class="pull-right">
							{!! $Buscas->appends(Request::only('busca'))->links() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
		@include('layouts.search.no-results')
	@endif
	<!-- /page content -->
@endsection
@section('scripts_content')
@endsection