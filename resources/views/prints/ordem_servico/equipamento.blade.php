<?php
$Equipamento = $aparelhoManutencao->equipamento;
?>
<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="7">
        Equipamento (#{{$Equipamento->idequipamento}}) {{$Equipamento->descricao}}
    </th>
</tr>
<tr>
    <td colspan="3">Descrição: {{$Equipamento->descricao}}</td>
    <td colspan="2">Modelo: {{$Equipamento->modelo}}</td>
    <td colspan="2">N° de Série: {{$Equipamento->numero_serie}}</td>
</tr>