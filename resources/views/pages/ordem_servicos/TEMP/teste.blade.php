@extends('layouts.template')
@section('page_content')
	@include('pages.ordem_servicos.popup.cliente')
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
	<div class="x_panel">
		<div class="x_title">
			<h2>TESTE</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
					<table border="0" class="table table-hover">
						<thead>
						<tr>
							<th>ID</th>
							<th>Nome</th>
							<th>Valor</th>
							<th>Observação</th>
							<th>Ações</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<select name="idsegmento" class="form-control" required>
										<option value="">Escolha o Segmento</option>
										@foreach($Page->extras['segmentos'] as $segmento)
											<option value="{{$segmento->idsegmento}}">{{$segmento->descricao}}</option>
										@endforeach
									</select> {{$instrumento->descricao}}@endforeach</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<a class="btn btn-primary btn-xs"
									   data-href="{{route($Page->link.'.abrir',$cliente->idcliente)}}"
									   data-cliente="{{$cliente}}"
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

						</tbody>
					</table>
					<div class="form-horizontal form-label-left">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">N: <span class="required">*</span></label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<input name="codigo" type="text" maxlength="50" class="form-control" required
									   value="{{(isset($CstIpi->codigo))?$CstIpi->codigo:old('codigo')}}">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection
@section('scripts_content')
	<script>
		//ABRE MODAL
	</script>
@endsection