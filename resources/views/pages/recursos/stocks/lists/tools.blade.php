<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Responsável</th>
        <th>Void</th>
        <th>Custo</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->id}}</td>
            <td>{{$sel->tool->getResume()}}</td>
            <td>{{$sel->owner->nome}}</td>
            <td>{{$sel->void_tool->void->number}}</td>
            <td>{{$sel->getCost()}}</td>
            <td>
                {{--<a class="btn btn-primary btn-xs"--}}
                {{--href="{{route($Page->link.'.show',$sel->id)}}">--}}
                {{--<i class="fa fa-edit"></i></a>--}}
                {{--<button class="btn btn-danger btn-xs"--}}
                {{--data-nome="Ferramenta: {{$sel->tool->getResume()}}"--}}
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
        <th>Responsável</th>
        <th>Void</th>
        <th>Custo</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    </tbody>
</table>