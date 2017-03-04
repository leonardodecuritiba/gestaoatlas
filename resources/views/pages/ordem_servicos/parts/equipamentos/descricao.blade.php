<h3>{{$Equipamento->descricao}}</h3>
<div class="col-md-6 col-sm-6 col-xs-12">
    <ul class="list-unstyled user_data">
        <li><i class="fa fa-info"></i> Marca:<b> {{$Equipamento->marca->descricao}}</b></li>
        <li><i class="fa fa-info"></i> Modelo:<b> {{$Equipamento->patrimonio}}</b></li>
        <li><i class="fa fa-info"></i> Nº de Série:<b> {{$Equipamento->numero_serie}}</b></li>
    </ul>
</div>