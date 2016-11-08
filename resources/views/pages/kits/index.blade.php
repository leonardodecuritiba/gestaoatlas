@extends('layouts.template')
@section('page_content')
	{{--@include('admin.layouts.alerts.remove')--}}
	<!-- top tiles -->
	<!-- Seach form -->
	@include('layouts.search.form')
	<!-- /Seach form -->
	<div class="row">
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="tile-stats">
				<div class="icon"><i class="fa fa-upload"></i>
				</div>
				<div class="count">Exportar</div>
				<h3>Lista de {{$Page->Targets}}</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="tile-stats">
				<div class="icon"><i class="fa fa-download"></i>
				</div>
				<div class="count">Importar</div>
				<h3>Lista de {{$Page->Targets}}</h3>
			</div>
		</div>
	</div>
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
								<th>Nome</th>
								<th>Descrição</th>
								<th>Valor Total</th>
								<th>Ações</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($Buscas as $selecao)
								<tr>
									<td>{{$selecao->created_at}}</td>
									<td>{{$selecao->nome}}</td>
									<td>{{$selecao->descricao}}</td>
									<td>{{$selecao->valor_total()}}</td>
									<td>
										<a class="btn btn-default btn-xs"
										   href="{{route($Page->link.'.show',$selecao->idkit)}}">
											<i class="fa fa-edit"></i> Visualizar / Editar</a>
										<a class="btn btn-danger btn-xs"
										   data-nome="{{$selecao->nome}}"
										   data-href="{{route($Page->link.'.destroy',$selecao->idkit)}}"
										   data-toggle="modal"
										   data-target="#modalRemocao">
											<i class="fa fa-trash-o"></i> Excluir </a>
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