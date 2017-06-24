<tr class="fundo_titulo">
    <th class="linha_titulo" colspan="6">CLIENTE N° {{$Cliente->idcliente}}</th>
</tr>
<tr class="campo">
    <td width="35%">CLIENTE/RAZÃO SOCIAL</td>
    <td colspan="2" width="25%">FANTASIA</td>
    <td>CONTATO</td>
    <td width="15%">CNPJ/CPF</td>
    <td width="13%">IE</td>
</tr>
<tr>
    <td>{{$ClienteType->razao_social}}</td>
    <td colspan="2">{{$ClienteType->nome_principal}}</td>
    <td>{{$Cliente->nome_responsavel}}</td>
    <td>{{$ClienteType->entidade}}</td>
    <td>{{$ClienteType->ie}}</td>
</tr>
<tr class="campo">
    <td colspan="3">ENDEREÇO</td>
    <td colspan="2">BAIRRO/DISTRITO</td>
    <td>CEP</td>
</tr>
<tr>
    <td colspan="3">{{$Contato->getRua()}}</td>
    <td colspan="2">{{$Contato->bairro}}</td>
    <td>{{$Contato->cep}}</td>
</tr>
<tr class="campo">
    <td>MUNICÍPIO</td>
    <td>UF</td>
    <td colspan="2">FONE</td>
    <td colspan="2">EMAIL NOTA</td>
</tr>
<tr>
    <td>{{$Contato->cidade}}</td>
    <td>{{$Contato->estado}}</td>
    <td colspan="2">{{$Contato->telefone}}</td>
    <td colspan="2">{{$Cliente->email_nota}}</td>
</tr>