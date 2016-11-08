<div class="profile_img">
    <a href="{{route('fornecedores.show',$Fornecedor->idfornecedor)}}"><img width="100%" src="{{asset('imgs/user.png')}}" alt="Avatar"></a>
</div>
<h4>{{$Fornecedor->getType()->nome_principal}}</h4>
<ul class="list-unstyled user_data">
    <li>
        <i class="fa fa-map-marker user-profile-icon"></i> {{implode(', ',$Fornecedor->getEnderecoResumido())}}
    </li>
    <li class="m-top-xs">
        <i class="fa fa-phone user-profile-icon"></i>
        <span class="show-celular">{{$Fornecedor->contato->celular}}</span>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-envelope-o user-profile-icon"></i>
        <a href="#" >{{$Fornecedor->email_orcamento}}</a>
    </li>
    <li class="m-top-xs">
        <i class="fa fa-calendar-o user-profile-icon"></i>
        <a href="#">{{$Fornecedor->created_at}}</a>
    </li>
</ul>