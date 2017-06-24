<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="7">TÉCNICO</th>
</tr>
<tr class="campo">
    <th colspan="3">NOME</th>
    <th colspan="4">CPF</th>
</tr>
<tr>
    <td colspan="3">{{$OrdemServico->colaborador->nome}}</td>
    <td colspan="4">{{$OrdemServico->colaborador->cpf}}</td>
</tr>
<tr>
    <th colspan="4" class="assinatura">Assinatura:</th>
    <td colspan="3" class="sublinhar"></td>
</tr>

<tr class="espaco">
    <th colspan="7"></th>
</tr>

<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="7">RESPONSÁVEL</th>
</tr>
<tr class="campo">
    <th colspan="3">NOME</th>
    <th colspan="2">CPF</th>
    <th colspan="2">CARGO</th>
</tr>
<tr>
    <td colspan="3">{{$OrdemServico->responsavel}}</td>
    <td colspan="2">{{$OrdemServico->responsavel_cpf}}</td>
    <td colspan="2">{{$OrdemServico->responsavel_cargo}}</td>
</tr>
<tr>
    <th colspan="4" class="assinatura">Assinatura:</th>
    <td colspan="3" class="sublinhar"></td>
</tr>