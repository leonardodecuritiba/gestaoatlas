<?php
$Equipamento = $aparelhoManutencao->equipamento;
?>
<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="8">
        EQUIPAMENTO Nº {{$Equipamento->idequipamento}}
    </th>
</tr>
<tr class="campo">
    <td width="10%" colspan="2">NÚMERO</td>
    <td width="50%" colspan="2">DESCRIÇÃO</td>
    <td width="25%" colspan="2">MODELO</td>
    <td colspan="2">Nº DE SÉRIE</td>
</tr>
<tr>
    <td colspan="2">{{$Equipamento->idequipamento}}</td>
    <td colspan="2">{{$Equipamento->descricao}}</td>
    <td colspan="2">{{$Equipamento->modelo}}</td>
    <td colspan="2">{{$Equipamento->numero_serie}}</td>
</tr>