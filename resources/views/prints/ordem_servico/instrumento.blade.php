<?php
$Instrumento = $aparelhoManutencao->instrumento;
if ($Instrumento->has_selo_instrumentos()) {
    $selo_afixado = $Instrumento->selo_afixado_numeracao();
    $selo_retirado = '-';
} else {
    $selo_afixado = '-';
    $selo_retirado = '-';
}
if ($Instrumento->has_lacres_instrumentos()) {
    $lacres_afixados = $Instrumento->lacres_afixados_valores();
    $lacres_retirados = '-';
} else {
    $lacres_afixados = '-';
    $lacres_retirados = '-';
}
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
    <td>Selo Retirado:</td>
    <td colspan="2">{{$selo_retirado}}</td>
    <td>Lacres Retirados:</td>
    <td colspan="3">{{$lacres_retirados}}</td>
</tr>
<tr>
    <td>Selo Afixado:</td>
    <td colspan="2">{{$selo_afixado}}</td>
    <td>Lacres Afixados:</td>
    <td colspan="3">{{$lacres_afixados}}</td>
</tr>