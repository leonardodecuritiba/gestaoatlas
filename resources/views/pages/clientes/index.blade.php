@extends('layouts.template')
@section('style_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
@endsection
@section('page_content')
	{{--@include('admin.layouts.alerts.remove')--}}
	<!-- Seach form -->
	@include('layouts.search.form')
	<!-- /Seach form -->
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
	@if(count($Buscas) > 0)
		<div class="x_panel">
			<div class="x_title">
				<h2>{{$Page->Targets}} encontrados</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table id="datatable-responsive"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
							<thead>
								<tr>
                                    <th>#</th>
									<th>Status</th>
									<th>Fantasia</th>
                                    <th>Razão Social</th>
                                    <th>CNPJ/CPF</th>
									<th>Responsável</th>
									<th>Fone</th>
									<th>Ações</th>
								</tr>
							</thead>
							<tbody>
								@foreach($Buscas as $cliente)
									<?php $tipo_cliente = $cliente->getType(); ?>
									<tr>
                                        <td>{{$cliente->idcliente}}</td>
										@if($cliente->validado())
											<td><span class="btn btn-success btn-xs"> Validado</span></td>
										@else
											<td><span class="btn btn-danger btn-xs"> Não Validado</span></td>
										@endif
										<td>{{$tipo_cliente->nome_principal}}</td>
                                        <td>{{$tipo_cliente->razao_social}}</td>
										<td>{{$tipo_cliente->entidade}}</td>
										<td>{{$cliente->nome_responsavel}}</td>
										<td>{{$cliente->contato->telefone}}</td>
										<td>
											<a class="btn btn-default btn-xs"
											   href="{{route($Page->link.'.show',$cliente->idcliente)}}">
												<i class="fa fa-edit"></i> Visualizar / Editar</a>
											@if(Auth::user()->hasRole('admin'))
												<a class="btn btn-danger btn-xs"
												   data-nome="{{$cliente->getType()->entidade}}"
												   data-href="{{route($Page->link.'.destroy',$cliente->idcliente)}}"
												   data-toggle="modal"
												   data-target="#modalRemocao">
													<i class="fa fa-trash-o"></i> Excluir </a>
											@endif
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
	<!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 20,
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false,
                    "order": [0, "desc"]
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection