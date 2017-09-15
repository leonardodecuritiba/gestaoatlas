<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Colaborador</th>
        <th>Void</th>
        <th>Custo</th>
        <th>Certificado</th>
        <th>Validade</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($Buscas as $sel)
        <tr>
            <td>{{$sel->id}}</td>
            <td>{{$sel->pattern->getResume()}}</td>
            <td>{{$sel->owner->nome}}</td>
            <td>{{$sel->void_pattern->void->number}}</td>
            <td>{{$sel->getCost()}}</td>
            <td>{{$sel->getCertificationText()}}</td>
            <td>{{$sel->expiration}}</td>
            <td>
                {{--<a class="btn btn-primary btn-xs"--}}
                {{--href="{{route($Page->link.'.show',$sel->id)}}">--}}
                {{--<i class="fa fa-edit"></i></a>--}}
                <button class="btn btn-danger btn-xs"
                        data-nome="Padrão: {{$sel->pattern->getResume()}}"
                        data-href="{{route($Page->link.'.destroy',$sel->id)}}"
                        data-toggle="modal"
                        data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>
                </button>
            </td>
        </tr>
    @endforeach
    <tfoot>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Colaborador</th>
        <th>Void</th>
        <th>Custo</th>
        <th>Certificado</th>
        <th>Validade</th>
        <th>Ações</th>
    </tr>
    </tfoot>
    </tbody>
</table>