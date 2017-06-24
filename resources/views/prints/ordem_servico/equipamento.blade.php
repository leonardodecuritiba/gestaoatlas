<?php
$Equipamento = $aparelhoManutencao->equipamento;
?>
<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="4">
        EQUIPAMENTO Nº {{$Equipamento->idequipamento}}
    </th>
</tr>
<tr class="campo">
    <td width="10%">NÚMERO</td>
    <td width="50%">DESCRIÇÃO</td>
    <td width="25%">MODELO</td>
    <td>Nº DE SÉRIE</td>
</tr>
<tr>
    <td>{{$Equipamento->idequipamento}}</td>
    <td>{{$Equipamento->descricao}}</td>
    <td>{{$Equipamento->modelo}}</td>
    <td>{{$Equipamento->numero_serie}}</td>
</tr>