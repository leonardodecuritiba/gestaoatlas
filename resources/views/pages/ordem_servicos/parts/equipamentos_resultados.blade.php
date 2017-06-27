<div class="x_panel">
    <div class="x_title">
        <h2>Equipamentos</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="animated fadeInDown">
            <table id="datatable-responsive"
                   class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Nº de Série</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($Equipamentos as $equipamento)
                    <tr>
                        <td>{{$equipamento->idequipamento}}</td>
                        <td>
                            <img src="{{$equipamento->getFoto()}}" class="avatar" alt="Avatar">
                        </td>
                        <td>{{$equipamento->descricao}}</td>
                        <td>{{$equipamento->marca->descricao}}</td>
                        <td>{{$equipamento->modelo}}</td>
                        <td>{{$equipamento->numero_serie}}</td>
                        <td>
                            <a class="btn btn-primary btn-xs"
                               data-tipo="equipamento"
                               data-numero_chamado="{{$OrdemServico->cliente->numero_chamado}}"
                               data-aparelho="{{$equipamento}}"
                               data-urlfoto="{{$equipamento->getFoto()}}"
                               data-toggle="modal"
                               data-target="#modalPopupAparelho">
                                <i class="fa fa-eye"></i> Visualizar </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>