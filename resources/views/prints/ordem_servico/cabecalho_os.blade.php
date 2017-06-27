<tr class="fundo_titulo">
    <th class="linha_titulo" colspan="4">ORDEM DE SERVIÇO N° {{$OrdemServico->idordem_servico}}</th>
</tr>
<tr class="campo">
    <td width="7%">CÓD.</td>
    <td width="43%">SITUAÇÃO DA O.S.</td>
    <td width="25%">DATA DE ABERTURA</td>
    <td width="25%">DATA DE FECHAMENTO</td>
</tr>
<tr>
    <td>{{$OrdemServico->idordem_servico}}</td>
    <td>{{$OrdemServico->getStatusText()}}</td>
    <td>{{$OrdemServico->getDataAbertura()}}</td>
    <td>{{$OrdemServico->getDataFinalizada()}}</td>
</tr>