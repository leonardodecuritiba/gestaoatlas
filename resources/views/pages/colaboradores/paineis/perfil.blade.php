<div class="profile_img">
    <a href="{{route('colaboradores.show',$Colaborador->idcolaborador)}}"><img width="100%" src="{{asset('imgs/user.png')}}" alt="Avatar"></a>
</div>
<h4>{{$Colaborador->nome}}</h4>
<ul class="list-unstyled user_data">
    <li>
        <i class="fa fa-map-marker user-profile-icon"></i> {{implode(', ',$Colaborador->getEnderecoResumido())}}
    </li>
    <li class="m-top-xs">
        <i class="fa fa-phone user-profile-icon"></i>
        <span class="show-celular">{{$Colaborador->contato->celular}}</span>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-envelope-o user-profile-icon"></i>
        <a href="#" >{{$Colaborador->user->email}}</a>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-calendar-o user-profile-icon"></i>
        <a href="#">{{$Colaborador->created_at}}</a>
    </li>
    @if($Colaborador->isSelf())
        <li class="m-top-xs">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalPWD">Atualizar Senha</button>
        </li>
    @endif
</ul>