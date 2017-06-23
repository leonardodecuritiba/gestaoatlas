<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="7">Kits</th>
</tr>
<tr class="row_cabecalho">
    <th width="8%">Cod</th>
    <th width="42%">Descrição</th>
    <th width="8%">Garantia</th>
    <th width="10%">Gar. Negada</th>
    <th width="12%">V. un</th>
    <th width="8%">Qtde</th>
    <th width="12%">V. Total</th>
</tr>
@foreach ($Kits as $selecao)
    <tr>
        <td>{{$selecao->kit->idkit}}</td>
        <td>{{$selecao->kit->descricao}}</td>
        <td>-</td>
        <td>-</td>
        <td class="valor">{{$selecao->valor_real()}}</td>
        <td class="valor">{{$selecao->quantidade}}</td>
        <td class="valor">{{$selecao->valor_total_real()}}</td>
    </tr>
@endforeach
<tr class="linha_total">
    <td colspan="4">Total</td>
    <td class="valor">{{$Total}}</td>
</tr>