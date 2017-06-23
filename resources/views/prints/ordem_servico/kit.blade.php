<tr class="fundo_titulo_3">
    <th class="linha_titulo" colspan="8">Peças</th>
</tr>
<tr class="row_cabecalho">
    <th width="7%">Cod</th>
    <th width="30%">Descrição</th>
    <th width="7%">Garantia</th>
    <th width="10%">Gar. Negada</th>
    <th width="10%">V. un</th>
    <th width="6%">Qtde</th>
    <th width="10%">Desconto</th>
    <th width="10%">V. Total</th>
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