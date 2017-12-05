<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Imagem</th>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Marca</th>
        <th>Fornecedor</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>#</th>
        <th>Imagem</th>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Marca</th>
        <th>Fornecedor</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->idpeca}}</td>
            <td>
                <img src="{{$sel->getFotoThumb()}}" class="avatar" alt="Avatar">
            </td>
            <td>{{$sel->tipo}}</td>
            <td>{{$sel->descricao}}</td>
            <td>{{$sel->nome_marca()}}</td>
            <td>
                @if($sel->has_fornecedor())
                    <a target="_blank" class="btn btn-xs btn-primary"
                       href="{{route('fornecedores.show',$sel->fornecedor->idfornecedor)}}"><i class="fa fa-eye"></i> {{$sel->fornecedor->getType()->nome_principal}}</a>
                @else
                    <i>Não informado</i>
                @endif
            </td>
            <td>
                <a class="btn btn-primary btn-xs"
                   href="{{route($Page->link.'.show',$sel->idpeca)}}">
                    <i class="fa fa-edit"></i></a>
                <button class="btn btn-danger btn-xs"
                        data-nome="Padrão: {{$sel->descricao}}"
                        data-href="{{route($Page->link.'.destroy',$sel->idpeca)}}"
                        data-toggle="modal"
                        data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>