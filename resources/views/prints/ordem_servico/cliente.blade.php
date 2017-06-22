<tr>
    <td>Cliente / Razão Social:</td>
    <td>{{$ClienteType->razao_social}}</td>
    <td>Fantasia:</td>
    <td>{{$ClienteType->nome_principal}}</td>
    <td>Data Abertura:</td>
    <td>{{$OrdemServico->created_at}}</td>
</tr>
<tr>
    <td>{{$ClienteType->tipo}}</td>
    <td>{{$ClienteType->entidade}}</td>
    <td>I.E:</td>
    <td>{{$ClienteType->ie}}</td>
    <td>Data Fechamento:</td>
    <td></td>
</tr>
<tr>
    <td>Endereço:</td>
    <td>AV. NELSON NOGUEIRA, 2211</td>
    <td>CEP: {{$Contato->cep}}</td>
    <td>{{$Contato->cidade}} - {{$Contato->estado}}</td>
    <td>Situação da O.S.:</td>
    <td>{{$OrdemServico->getStatusText()}}</td>
</tr>
<tr>
    <td>Telefone:</td>
    <td>{{$Contato->telefone}}</td>
    <td>Contato:</td>
    <td>{{$Cliente->nome_responsavel}}</td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Email (nota):</td>
    <td>{{$Cliente->email_nota}}</td>
    <td>Email (orçamento):</td>
    <td>{{$Cliente->email_orcamento}}</td>
    <td>Nº Chamado Sist. Cliente:</td>
    <td>{{$OrdemServico->numero_chamado}}</td>
</tr>