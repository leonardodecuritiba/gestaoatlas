<div class="x_panel">
    <div class="x_content">
        <div class="animated fadeInDown">
            <table id="datatable-responsive"
                   class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Nº de Série</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($Buscas as $instrumento)
                    <tr>
                        <td>
                            <img src="{{$instrumento->getFoto()}}" class="avatar" alt="Avatar">
                        </td>
                        <td>{{$instrumento->descricao}}</td>
                        <td>{{$instrumento->marca->descricao}}</td>
                        <td>{{$instrumento->modelo}}</td>
                        <td>{{$instrumento->numero_serie}}</td>
                        <td>
                            <a class="btn btn-primary btn-xs"
                               data-href="{{route($Page->link.'.instrumentos.adiciona',[$OrdemServico->idordem_servico,$instrumento->idinstrumento])}}"
                               data-instrumento="{{$instrumento}}"
                               data-urlfoto="{{$instrumento->getFoto()}}"
                               data-toggle="modal"
                               data-target="#modalPopupInstrumento">
                                <i class="fa fa-eye"></i> Visualizar </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>