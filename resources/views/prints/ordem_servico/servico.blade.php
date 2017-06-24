<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="8">SERVIÇOS</th>
</tr>
<tr class="campo">
    <th width="7%">CÓD.</th>
    <th colspan="3" width="47%">DESCRIÇÃO</th>
    <th width="10%">V. UN.</th>
    <th width="6%">QTDE</th>
    <th width="10%">DESCONTO</th>
    <th width="10%">V. TOTAL</th>
</tr>
@foreach ($Servicos as $selecao)
    <tr>
        <td>{{$selecao->servico->idservico}}</td>
        <td colspan="3">{{$selecao->servico->descricao}}</td>
        <td class="valor">{{$selecao->valor_real()}}</td>
        <td class="valor">{{$selecao->quantidade}}</td>
        <td class="valor">{{$selecao->valor_desconto_real()}}</td>
        <td class="valor">{{$selecao->valor_total_real()}}</td>
    </tr>
@endforeach
<tr class="linha_total">
    <th colspan="7">TOTAL</th>
    <td class="valor">{{$Total}}</td>
</tr>