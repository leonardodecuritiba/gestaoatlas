<tr class="fundo_titulo">
    <th class="linha_titulo" colspan="5">ORDEM DE SERVIÇO N° {{$OrdemServico->idordem_servico}}</th>
</tr>
<tr class="campo">
    <td width="7%">CÓD.</td>
    <td>SITUAÇÃO DA O.S.</td>
    <td>NÚMERO CHAMADO CLIENTE</td>
    <td width="15%">DATA DE ABERTURA</td>
    <td width="15%">DATA DE FECHAMENTO</td>
</tr>
<tr>
    <td>{{$OrdemServico->idordem_servico}}</td>
    <td>{{$OrdemServico->getStatusText()}}</td>
    <td>{{$OrdemServico->numero_chamado}}</td>
    <td>{{$OrdemServico->getDataAbertura()}}</td>
    <td>{{$OrdemServico->getDataFinalizada()}}</td>
</tr>