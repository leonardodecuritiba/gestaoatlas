<table border="0" class="table table-hover">
	<thead>
	<tr>
		<th>Nome</th>
		<th>Placa</th>
		<th>Status</th>
		<th>Ações</th>
	</tr>
	</thead>
	<tbody>
		@foreach ($Buscas as $carro)
			<tr>
				<td>{{$carro->DES}}</td>
				<td>{{$carro->PLA}}</td>
				<td class="td-active">
					@if($carro->ATI)
						<span class="btn btn-success btn-xs">Ativo</span>
					@else
						<span class="btn btn-danger btn-xs">Inativo</span></td>
				@endif
				</td>
				<td>
					<a href="{{route(strtolower($link).'.edit',$carro->CAR_ID)}}"><i class="fa fa-edit"></i>Editar</a>
					<a href="javascript:void(0)" class="btn-active"
					   data-value="{{$carro->ATI}}"
					   data-table="sup_car"
					   data-pk="CAR_ID"
					   data-id="{{$carro->CAR_ID}}">
						@if($carro->ATI)
							<i class="fa fa-eye-slash"></i>Desativar
						@else
							<i class="fa fa-eye"></i>Ativar
						@endif
					</a>

					<a class="btn btn-danger btn-xs"
					   data-nome="{{$carro->DES}}"
					   data-href="{{route(strtolower($link).'.destroy',$carro->CAR_ID)}}"
					   data-toggle="modal"
					   data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>Excluir </a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
