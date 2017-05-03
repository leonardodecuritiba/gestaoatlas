@extends('layouts.template')
@section('modals_content')
    <!-- Datatables -->
    @include('helpers.datatables.head')
    <!-- /Datatables -->
	@include('layouts.modals.delete')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
	@include('pages.ordem_servicos.popup.cliente')
	<!-- Seach form -->
	{{--@include('layouts.search.form')--}}
	<!-- Upmenu form -->
	<div id="search" class="x_panel animated flipInX">
		<div class="x_content">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					{!! Form::open(array('route'=>'ordem_servicos.index',
                        'method'=>'GET','id'=>'search',
                        'class' => 'form-horizontal form-label-left')) !!}
					<label class="control-label col-md-1 col-sm-1 col-xs-12">Por Data de Abertura:</label>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<input value="{{Request::get('data')}}"
							   type="text" class="form-control data-to-now" name="data" placeholder="Data" required>
					</div>
					<label class="control-label col-md-1 col-sm-1 col-xs-12">Por Tipo:</label>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<select name="situacao" class="form-control select2_single" required>
							@foreach($Page->extras['situacao_ordem_servico'] as $key => $value)
								<option value="{{$key}}"
										@if(Request::has('situacao') && Request::get('situacao')==$key) selected @endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
					<label class="control-label col-md-1 col-sm-1 col-xs-12">Por Cliente:</label>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<select name="idcliente" class="form-control select2_single">
							<option value="">Todos</option>
							@foreach($Page->extras['clientes'] as $cliente)
								<option value="{{$cliente->idcliente}}"
										@if(Request::has('idcliente') && Request::get('idcliente')==$cliente->idcliente) selected @endif>{{$cliente->getType()->nome_principal}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar</button>
						</span>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
			<div class="ln_solid"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					{!! Form::open(array('route'=>['ordem_servicos.index'],
                        'method'=>'GET','id'=>'porid',
                        'class' => 'form-horizontal form-label-left')) !!}
					<label class="control-label col-md-1 col-sm-1 col-xs-12">ID da O.S:</label>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<input value="{{Request::get('idordem_servico')}}" type="text" class="form-control"
							   name="idordem_servico" placeholder="ID O.S.">
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<span class="input-group-btn">
							<button class="btn btn-info" type="submit">Filtrar por ID</button>
						</span>
					</div>
					{!! Form::close() !!}

					{{--<script>--}}
					{{--$(document).ready(function () {--}}
					{{--$('form#porid').on('submit', function(){--}}
					{{--var id = $(this).find('input[name=idordem_servico]').val();--}}
					{{--if(id != ''){--}}
					{{--var url = $(this).attr('action');--}}
					{{--$(this).attr('action',url.replace('XX',id));--}}
					{{--return true;--}}
					{{--}--}}
					{{--return false;--}}
					{{--});--}}
					{{--});--}}
					{{--</script>--}}
				</div>
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
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
							<thead>
							<tr>
								<th>ID</th>
                                <th>Situação</th>
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
                                    <td>{{$selecao->idordem_servico}}</td>
									<td>
										<button class="btn btn-xs btn-{{$selecao->getStatusType()}}">
											{{$selecao->situacao->descricao}}
										</button>
									</td>
									<td>{{$selecao->numero_chamado}}</td>
									<td>{{$selecao->created_at}}</td>
									<td>{{$selecao->colaborador->nome}}</td>
									<td>{{$selecao->cliente->getType()->nome_principal}}</td>
									<td>
										<a class="btn btn-primary btn-xs"
										   href="{{route('ordem_servicos.show',$selecao->idordem_servico)}}">
											<i class="fa fa-eye"></i> Abrir</a>
										@role('admin')
										@if($selecao->getStatusFechada())
											<a class="btn btn-danger btn-xs"
											   data-nome="Ordem de Serviço #{{$selecao->idordem_servico}}"
											   data-href="{{route('ordem_servicos.destroy',$selecao->idordem_servico)}}"
											   data-toggle="modal"
											   data-target="#modalDelecao"><i class="fa fa-trash-o"></i> Remover</a>
											@if($selecao->getStatusFinalizada())
												<a class="btn btn-success btn-xs"
												   href="{{route('fechamento.gerar',$selecao->idordem_servico)}}">
													<i class="fa fa-money"></i> Faturar</a>
											@endif
										@endif
										@endrole
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
    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
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
	<!-- /Datatables -->
	<script>
        <!-- script deleção -->
        $(document).ready(function () {

            $('div#modalDelecao').on('show.bs.modal', function (e) {
                $origem = $(e.relatedTarget);
                nome_ = $($origem).data('nome');
                href_ = $($origem).data('href');
                $el = $($origem).data('elemento');
                $(this).find('.modal-body').html('Você realmente deseja remover <strong>' + nome_ + '</strong> e suas relações? Esta ação é irreversível!');
                $(this).find('.btn-ok').click(function () {
                    window.location.replace(href_);
                });
            });
        });
	</script>
@endsection