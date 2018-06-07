@extends('layouts.template')
@section('style_content')
@endsection
@section('page_content')
	@include('pages.ordem_servicos.popup.cliente_show')
	<!-- Seach form -->
	<div id="search" class="x_panel animated flipInX">
		<div class="x_content">
			<div class="col-md-12 col-sm-12 col-xs-12">
				{!! Form::open(array('route'=>$Page->link.'.busca','method'=>'GET','id'=>'search')) !!}
				<div class="col-md-12 col-sm-12 col-xs-12 input-group input-group-lg">
					<input id="buscar" value="{{Request::get('busca')}}" name="busca" type="text" class="form-control" placeholder="{{$Page->Search}}">
					<span class="input-group-btn">
						<button class="btn btn-info" type="submit">Buscar</button>
					</span>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<!-- Upmenu form -->
	@if(count($Buscas) > 0)
		<div class="x_panel">
			<div class="x_title">
				<h2><b>{{$Buscas->total()}}</b> {{$Page->Targets}} encontrados</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
						<table border="0" class="table table-hover">
							<thead>
							<tr>
								<th>ID</th>
								<th>Tipo</th>
								<th>Descrição</th>
								<th>Nome</th>
								<th>Ações</th>
							</tr>
							</thead>
							<tbody>
							@foreach($Buscas as $cliente)
								<?php $tipo_cliente = $cliente->getType(); ?>
								<tr>
									<td>{{$cliente->idcliente}}</td>
									<td>{{$cliente->getType()->tipo}}</td>
									<td>{{$cliente->getType()->entidade}}</td>
									<td>{{$cliente->getType()->nome_principal}}</td>
									<td>
										<a class="btn btn-primary btn-xs"
										   data-href="{{route($Page->link.'.abrir',$cliente->idcliente)}}"
										   data-os="{{route($Page->link.'.cliente',$cliente->idcliente)}}"
										   data-cli="{{route('clientes.show',$cliente->idcliente)}}"
										   data-cliente="{{$cliente->getData()}}"
										   data-endereco="{{$cliente->getEndereco()}}"
										   data-fones="{{$cliente->getFones()}}"
										   data-email="{{$cliente->email_orcamento}}"
										   data-urlfoto="{{$cliente->getURLFoto()}}"
										   data-pessoa="{{$cliente->idpjuridica!=NULL?$cliente->pessoa_juridica:$cliente->pessoa_fisica}}"
										   data-toggle="modal"
										   data-target="#modalPopup">
											<i class="fa fa-eye"></i> Visualizar </a>
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
	<script>
		//ABRE MODAL
		$(document).ready(function() {
			$('div#modalPopup').on('show.bs.modal', function(e) {
				$origem 	= $(e.relatedTarget);
				cliente_ 	= $($origem).data('cliente');
				endereco_ 	= $($origem).data('endereco');
				pessoa_ 	= $($origem).data('pessoa');
				href_ 		= $($origem).data('href');
				os_ = $($origem).data('os');
				cli_ = $($origem).data('cli');
				urlfoto_ 	= $($origem).data('urlfoto');

				endereco 	= $($origem).data('endereco');
				fones 		= $($origem).data('fones');
				email 		= $($origem).data('email');

				$el 		= $($origem).data('elemento');

				var is_pjuridica = (cliente_.idpjuridica != null);
				if(is_pjuridica){
					nome 			= pessoa_.nome_fantasia;
					tipo	 		= "CNPJ: ";
					documento 		= pessoa_.cnpj;
					razao_social 	= pessoa_.razao_social;
					titulo 			= "Cliente Pessoa Jurídica";
				} else {
					nome 			= cliente_.nome_responsavel;
					tipo	 		= "CPF: ";
					documento 		= pessoa_.cpf;
					titulo 			= "Cliente Pessoa Física";

				}

				$(this).find('img').attr('src',urlfoto_);
				$(this).find('h4.brief i').html(titulo);
				$(this).find('div.perfil h2').html(nome);
				$(this).find('div.perfil p strong').html(tipo);
				$(this).find('div.perfil p span').html(documento);
				$(this).find('div.perfil ul span#endereco').html(endereco);
				$(this).find('div.perfil ul span#fones').html(fones);
				$(this).find('div.perfil ul span#email').html(email);

				if(cliente_.available_limit_tecnica <= 0){
				    var text = '<b class="red">';
				} else {
                    var text = '<b class="green">';
				}
				$(this).find('div.perfil ul span#tecnica').html(text + cliente_.available_limit_tecnica_formatted + '</b>');

                if(cliente_.available_limit_comercial <= 0){
                    text = '<b class="red">';
                } else {
                    text = '<b class="green">';
                }
				$(this).find('div.perfil ul span#comercial').html(text + cliente_.available_limit_comercial_formatted + '</b>');

				$(this).find('.btn-ok').html('Abrir O.S.');
				$(this).find('.btn-ok').attr("href", href_);

				$(this).find('.btn-os').html('Consulta O.S.');
				$(this).find('.btn-os').attr("href", os_);

				$(this).find('.btn-cli').html('Consulta Cliente');
				$(this).find('.btn-cli').attr("href", cli_);
			});
		});
	</script>
@endsection