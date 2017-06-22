<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="7">Kits</th>
</tr>
<tr>
    <th>Cod</th>
    <th>Descrição</th>
    <th>Garantia</th>
    <th>Garantia Negada</th>
    <th>V. un</th>
    <th>Qtde</th>
    <th>V. Total</th>
</tr>
@foreach ($Kits as $selecao)
    <tr>
        <td>{{$selecao->kit->idkit}}</td>
        <td>{{$selecao->kit->descricao}}</td>
        <td>-</td>
        <td>-</td>
        <td>{{$selecao->valor_real()}}</td>
        <td>{{$selecao->quantidade}}</td>
        <td>{{$selecao->valor_total_real()}}</td>
    </tr>
@endforeach
<tr>
    <td colspan="4">Total</td>
    <td>{{$Total}}</td>
    <td></td>
    <td></td>
</tr>