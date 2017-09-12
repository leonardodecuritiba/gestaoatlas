<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Numeração</th>
        <th>Situação</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->id}}</td>
            <td>{{$sel->number}}</td>
            <td><span class="btn btn-xs btn-{{$sel->getStatusColor()}}">{{$sel->getStatusText()}}</span></td>
            <td>
                {{--<a class="btn btn-primary btn-xs"--}}
                {{--href="{{route($Page->link.'.show',$sel->id)}}">--}}
                {{--<i class="fa fa-edit"></i></a>--}}
                @if(! $sel->used)
                    <button class="btn btn-danger btn-xs"
                            data-nome="{{$Page->Target . ": " . $sel->number}}"
                            data-href="{{route($Page->link.'.destroy',$sel->id)}}"
                            data-toggle="modal"
                            data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    <tfoot>
    <tr>
        <th>#</th>
        <th>Numeração</th>
        <th>Situação</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    </tbody>
</table>