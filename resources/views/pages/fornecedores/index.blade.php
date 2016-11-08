@extends('layouts.template')
@section('page_content')
	{{--@include('admin.layouts.alerts.remove')--}}
	<!-- top tiles -->
	<!-- Seach form -->
	@include('layouts.search.form')
	<!-- /Seach form -->
	@role('admin')
		<div class="row">
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{ route($Page->link.'.create') }}">
					<div class="tile-stats">
						<div class="icon">
							<i class="fa fa-floppy-o"></i>
						</div>
						<div class="count">Cadastrar</div>
						<h3>Novo {{$Page->Target}}</h3>
					</div>
				</a>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-upload"></i>
					</div>
					<div class="count">Exportar</div>
					<h3>Lista de {{$Page->Targets}}</h3>
				</div>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-download"></i>
					</div>
					<div class="count">Importar</div>
					<h3>Lista de {{$Page->Targets}}</h3>
				</div>
			</div>
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-envelope-o"></i>
					</div>
					<div class="count">Email</div>
					<h3>Enviar email</h3>
				</div>
			</div>
		</div>
	@endrole
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
								<th>Data de Cadastro</th>
								<th>Nome do Responsável</th>
								<th>Tipo</th>
								<th>Descrição</th>
								<th>Grupo</th>
								<th>Ações</th>
							</tr>
							</thead>
							<tbody>
								@foreach($Buscas as $fornecedor)
									<?php $tipo_fornecedor = $fornecedor->getType(); ?>
									<tr>
										<td>{{$fornecedor->created_at}}</td>
										<td>{{$fornecedor->nome_responsavel}}</td>
										<td>{{$fornecedor->getType()->tipo}}</td>
										<td>{{$fornecedor->getType()->entidade}}</td>
										<td>{{$fornecedor->grupo}}</td>
										<td>
											<a class="btn btn-default btn-xs"
											   href="{{route($Page->link.'.show',$fornecedor->idfornecedor)}}">
												<i class="fa fa-edit"></i> Visualizar / Editar</a>
											@if(Auth::user()->hasRole('admin'))
												<a class="btn btn-danger btn-xs"
												   data-nome="{{$fornecedor->getType()->entidade}}"
												   data-href="{{route($Page->link.'.destroy',$fornecedor->idfornecedor)}}"
												   data-toggle="modal"
												   data-target="#modalRemocao">
													<i class="fa fa-trash-o"></i> Excluir </a>
											@endif
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