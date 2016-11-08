@extends('layouts.template')
@section('page_content')
	<!-- Seach form -->
	@include('layouts.search.form')
	<!-- Upmenu form -->
	<?php $route_importacao = "#";  ?>
	<?php $route_exportacao = "#"; ?>
	@include('layouts.menus.upmenu-reduzido')

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
								<th>Descrição</th>
								<th>Ações</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($Buscas as $forma_pagamento)
								<tr>
									<td>{{$forma_pagamento->created_at}}</td>
									<td>{{$forma_pagamento->descricao}}</td>
									<td>
										<a class="btn btn-default btn-xs"
												href="{{route($Page->link.'.show',$forma_pagamento->idforma_pagamento)}}">
											<i class="fa fa-edit"></i> Visualizar / Editar</a>
										<button class="btn btn-danger btn-xs"
												data-nome="Grupo: {{$forma_pagamento->descricao}}"
												data-href="{{route($Page->link.'.destroy',$forma_pagamento->idforma_pagamento)}}"
												data-toggle="modal"
												data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i> Excluir</button>
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