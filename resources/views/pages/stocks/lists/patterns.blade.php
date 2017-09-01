<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Collaborador</th>
        <th>Quantidade</th>
        <th>Custo</th>
        <th>Custo Cert.</th>
        <th>Certificado</th>
        <th>Validade</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->id}}</td>
            <td>{{$sel->pattern->description}}</td>
            <td>{{$sel->collaborator->nome}}</td>
            <td>{{$sel->quantity}}</td>
            <td>{{$sel->getCost()}}</td>
            <td>{{$sel->getCostCertification()}}</td>
            <td>{{$sel->certification}}</td>
            <td>{{$sel->expiration}}</td>
            <td>
                {{--<a class="btn btn-primary btn-xs"--}}
                {{--href="{{route($Page->link.'.show',$sel->id)}}">--}}
                {{--<i class="fa fa-edit"></i></a>--}}
                {{--<button class="btn btn-danger btn-xs"--}}
                {{--data-nome="Padrão: {{$sel->description}}"--}}
                {{--data-href="{{route($Page->link.'.destroy',$sel->id)}}"--}}
                {{--data-toggle="modal"--}}
                {{--data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>--}}
                {{--</button>--}}
            </td>
        </tr>
    @endforeach
    <tfoot>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Collaborador</th>
        <th>Quantidade</th>
        <th>Custo</th>
        <th>Custo Cert.</th>
        <th>Certificado</th>
        <th>Validade</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    </tbody>
</table>