<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="8">KITS</th>
</tr>
<tr class="campo">
    <th width="7%">CÓD.</th>
    <th width="30%">DESCRIÇÃO</th>
    <th width="7%">GARANTIA</th>
    <th width="10%">GARANTIA NEGADA</th>
    <th width="10%">V. UN.</th>
    <th width="6%">QTDE</th>
    <th width="10%">DESCONTO</th>
    <th width="10%">V. TOTAL</th>
</tr>
@foreach ($Kits as $selecao)
    <tr>
        <td>{{$selecao->peca->idpeca}}</td>
        <td>{{$selecao->peca->descricao}}</td>
        <td>-</td>
        <td>-</td>
        <td class="valor">{{$selecao->valor_real()}}</td>
        <td class="valor">{{$selecao->quantidade}}</td>
        <td class="valor">{{$selecao->valor_desconto_real()}}</td>
        <td class="valor">{{$selecao->valor_total_real()}}</td>
    </tr>
@endforeach
<tr class="linha_total">
    <th colspan="7">Total</th>
    <td class="valor">{{$Total}}</td>
</tr>