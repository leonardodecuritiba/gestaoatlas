<div class="x_panel">
    <div class="x_title">
        <h2>Instrumentos</h2>
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
                    <th>Nº de Série</th>
                    <th>Imagem</th>
                    <th>Descrição</th>
                    <th>Inventário</th>
                    <th>Setor</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Nº de Série</th>
                    <th>Imagem</th>
                    <th>Descrição</th>
                    <th>Inventário</th>
                    <th>Setor</th>
                    <th>Ações</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($Instrumentos as $instrumento)
                    <tr>
                        <td>{{$instrumento->idinstrumento}}</td>
                        <td>{{$instrumento->numero_serie}}</td>
                        <td><img src="{{$instrumento->getThumbFoto()}}" class="avatar" alt="Avatar"></td>
                        <td>{{$instrumento->getDetalhesBase()}}</td>
                        <td>{{$instrumento->inventario}}</td>
                        <td>{{$instrumento->setor->descricao}}</td>
                        <td>
                            <a class="btn btn-primary btn-xs"
                               data-tipo="instrumento"
                               data-numero_chamado="{{$OrdemServico->cliente->numero_chamado}}"
                               data-aparelho="{{$instrumento}}"
                               data-detalhes="{{$instrumento->getDetalhesBase()}}"
                               data-urlfoto="{{$instrumento->getThumbFoto()}}"
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