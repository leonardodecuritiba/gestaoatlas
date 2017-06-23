<?php
$Instrumento = $aparelhoManutencao->instrumento;
?>

<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="7">
        Instrumento (#{{$Instrumento->idinstrumento}}) {{$Instrumento->descricao}}
    </th>
</tr>
<tr>
    <td>Marca: {{$Instrumento->marca->descricao}}</td>
    <td>Modelo: {{$Instrumento->modelo}}</td>
    <td>N° de Série: {{$Instrumento->numero_serie}}</td>
    <td>Patrimônio: {{$Instrumento->patrimonio}}</td>
    <td>Ano: {{$Instrumento->ano}}</td>
    <td colspan="2">Inventário: {{$Instrumento->inventario}}</td>
</tr>
<tr>
    <td>Portaria: {{$Instrumento->portaria}}</td>
    <td>Capacidade: {{$Instrumento->capacidade}}</td>
    <td>Divisão: {{$Instrumento->divisao}}</td>
    <td>Setor: {{$Instrumento->setor}}</td>
    <td>Endereço: {{$Instrumento->endereco}}</td>
    <td colspan="2">IP: {{$Instrumento->ip}}</td>
</tr>
<tr>
    <th>Selo Retirado:</th>
    <td colspan="2">{{$aparelhoManutencao->numeracao_selo_retirado()}}</td>
    <th>Lacres Retirados:</th>
    <td colspan="3">{{$aparelhoManutencao->numeracao_lacres_retirados()}}</td>
</tr>
<tr>
    <th>Selo Afixado:</th>
    <td colspan="2">{{$aparelhoManutencao->numeracao_selo_afixado()}}</td>
    <th>Lacres Afixados:</th>
    <td colspan="3">{{$aparelhoManutencao->numeracao_lacres_afixados()}}</td>
</tr>