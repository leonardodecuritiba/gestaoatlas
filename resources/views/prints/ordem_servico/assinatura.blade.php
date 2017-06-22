<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="7">Técnico</th>
</tr>
<tr>
    <th colspan="3">Nome:</th>
    <th colspan="4">CPF:</th>
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
    <th class="linha_titulo" colspan="7">Responsável</th>
</tr>
<tr>
    <th colspan="3">Nome:</th>
    <th colspan="2">CPF:</th>
    <th colspan="2">Cargo:</th>
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


{{--<tr>--}}
{{--<th class="assinatura">Assinatura:</th>--}}
{{--<td colspan="3" class="sublinhar"></td>--}}
{{--<td colspan="2"></td>--}}
{{--</tr>--}}
{{--<tr>--}}
{{--<td></th>--}}
{{--<td colspan="3">{{$OrdemServico->responsavel}} / {{$OrdemServico->responsavel_cpf}}</td>--}}
{{--<td colspan="2"></td>--}}
{{--</tr>--}}
