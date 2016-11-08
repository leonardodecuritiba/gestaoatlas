<div class="profile_img">
    <a href="{{route('clientes.show',$Cliente->idcliente)}}"><img width="100%" src="{{$Cliente->getURLFoto()}}" alt="Avatar"></a>
</div>
<h4>{{$Cliente->getType()->nome_principal}}</h4>
<ul class="list-unstyled user_data">
    <li>
        <i class="fa fa-map-marker user-profile-icon"></i> {{implode(', ',$Cliente->getEnderecoResumido())}}
    </li>
    <li class="m-top-xs">
        <i class="fa fa-phone user-profile-icon"></i>
        <span class="show-telefone">{{$Cliente->contato->telefone}}</span>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-envelope-o user-profile-icon"></i>
        <a href="#" >{{$Cliente->email_orcamento}}</a>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-calendar-o user-profile-icon"></i>
        <a href="#">{{$Cliente->created_at}}</a>
    </li>
</ul>