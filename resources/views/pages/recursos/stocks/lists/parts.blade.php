<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Responsável</th>
        <th>Custo</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Responsável</th>
        <th>Custo</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->id}}</td>
            <td>{{$sel->getShortDescritptions()}}</td>
            <td>{{$sel->getShortOwnerName()}}</td>
            <td>{{$sel->getShortCost()}}</td>
            <td>
                @if(!
                Entrust::hasRole('tecnico'))
                    <a class="btn btn-primary btn-xs"
                    href="{{route($Page->link.'.show',$sel->id)}}">
                    <i class="fa fa-edit"></i></a>
                    <button class="btn btn-danger btn-xs"
                        data-nome="Peça: {{$sel->getShortDescritptions()}}"
                        data-href="{{route($Page->link.'.destroy',$sel->id)}}"
                        data-toggle="modal"
                        data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>