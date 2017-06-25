<?php
$Instrumento = $aparelhoManutencao->instrumento;
?>

<tr class="fundo_titulo_2">
    <th class="linha_titulo" colspan="8">
        INSTRUMENTO Nº {{$Instrumento->idinstrumento}}
    </th>
</tr>
<tr class="campo">
    <td width="10%">NÚMERO</td>
    <td colspan="2">DESCRIÇÃO</td>
    <td colspan="2">MARCA</td>
    <td>MODELO</td>
    <td>Nº DE SÉRIE</td>
    <td>ANO</td>
</tr>
<tr>
    <td>{{$Instrumento->idinstrumento}}</td>
    <td colspan="2">{{$Instrumento->descricao}}</td>
    <td colspan="2">{{$Instrumento->marca->descricao}}</td>
    <td>{{$Instrumento->modelo}}</td>
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
    <td>{{$Instrumento->portaria}}</td>
    <td>{{$Instrumento->capacidade}}</td>
    <td>{{$Instrumento->divisao}}</td>
    <td>{{$Instrumento->inventario}}</td>
    <td>{{$Instrumento->patrimonio}}</td>
    <td>{{$Instrumento->setor}}</td>
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
    <td colspan="2">{{$Instrumento->numeracao_selo_retirado()}}</td>
    <td colspan="2">{{$Instrumento->numeracao_selo_afixado()}}</td>
    <td colspan="2">{{$Instrumento->numeracao_lacres_retirados()}}</td>
    <td colspan="2">{{$Instrumento->numeracao_lacres_afixados()}}</td>
</tr>
