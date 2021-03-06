<?php
$Instrumento = $aparelhoManutencao->instrumento;
$numeracao_selo_afixado = $aparelhoManutencao->numeracao_selo_afixado();
$numeracao_selo_retirado = $aparelhoManutencao->numeracao_selo_retirado();
$numeracao_lacres_afixados = $aparelhoManutencao->numeracao_lacres_afixados();
$numeracao_lacres_retirado = $aparelhoManutencao->numeracao_lacres_retirados();
?>

<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="8">
        INSTRUMENTO Nº {{$Instrumento->idinstrumento}}
    </th>
</tr>
<tr class="campo">
    <td width="10%">NÚMERO</td>
    <td colspan="2">DESCRIÇÃO</td>
    <td colspan="3">MARCA/MODELO</td>
    <td>Nº DE SÉRIE</td>
    <td>ANO</td>
</tr>
<tr>
    <td>{{$Instrumento->idinstrumento}}</td>
    <td colspan="2">{{$Instrumento->base->descricao}}</td>
    <td colspan="3">{{$Instrumento->base->getMarcaModelo()}}</td>
    <td>{{$Instrumento->numero_serie}}</td>
    <td>{{$Instrumento->ano}}</td>
</tr>
<tr class="campo">
    <td width="10%">PORTARIA</td>
    <td>CAPACIDADE</td>
    <td>DIVISÃO</td>
    <td>INVENTÁRIO</td>
    <td>PATRIMÔNIO</td>
    <td>SETOR</td>
    <td>ENDEREÇO</td>
    <td>IP</td>
</tr>
<tr>
    <td>{{$Instrumento->base->portaria}}</td>
    <td>{{$Instrumento->base->capacidade}}</td>
    <td>{{$Instrumento->base->divisao}}</td>
    <td>{{$Instrumento->inventario}}</td>
    <td>{{$Instrumento->patrimonio}}</td>
    <td>{{$Instrumento->setor->descricao}}</td>
    <td>{{$Instrumento->endereco}}</td>
    <td>{{$Instrumento->ip}}</td>
</tr>
<tr class="campo">
    <td colspan="2">SELO RETIRADO</td>
    <td colspan="2">SELO AFIXADO</td>
    <td colspan="2">LACRES RETIRADOS</td>
    <td colspan="2">LACRES AFIXADOS</td>
</tr>
<tr>
    <td colspan="2">{{$numeracao_selo_retirado['text']}}</td>
    <td colspan="2">{{$numeracao_selo_afixado['text']}}</td>
    <td colspan="2">{{$numeracao_lacres_retirado['text']}}</td>
    <td colspan="2">{{$numeracao_lacres_afixados['text']}}</td>
</tr>
