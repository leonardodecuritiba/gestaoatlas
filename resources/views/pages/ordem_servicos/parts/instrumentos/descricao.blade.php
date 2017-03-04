<h3>{{$Instrumento->descricao}}</h3>
<div class="col-md-6 col-sm-6 col-xs-12">
    <ul class="list-unstyled user_data">
        <li><i class="fa fa-info"></i> Marca:<b> {{$Instrumento->marca->descricao}}</b></li>
        <li><i class="fa fa-info"></i> Nº de Série:<b> {{$Instrumento->numero_serie}}</b></li>
        <li><i class="fa fa-info"></i> Modelo:<b> {{$Instrumento->modelo}}</b></li>
        <li><i class="fa fa-info"></i> Patrimônio:<b> {{$Instrumento->patrimonio}}</b></li>
        <li><i class="fa fa-info"></i> Inventário:<b> {{$Instrumento->inventario}}</b></li>
        <li><i class="fa fa-info"></i> Ano:<b> {{$Instrumento->ano}}</b></li>
    </ul>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
    <ul class="list-unstyled user_data">
        <li><i class="fa fa-info"></i> Portaria:<b> {{$Instrumento->portaria}}</b></li>
        <li><i class="fa fa-info"></i> Divisão:<b> {{$Instrumento->divisao}}</b></li>
        <li><i class="fa fa-info"></i> Capacidade:<b> {{$Instrumento->capacidade}}</b></li>
        <li><i class="fa fa-info"></i> IP:<b> {{$Instrumento->ip}}</b></li>
        <li><i class="fa fa-info"></i> Endereço:<b> {{$Instrumento->endereco}}</b></li>
        <li><i class="fa fa-info"></i> Setor:<b> {{$Instrumento->setor}}</b></li>
    </ul>
</div>