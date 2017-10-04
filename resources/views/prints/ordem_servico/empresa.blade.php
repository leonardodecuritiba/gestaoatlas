@if ($OrdemServico->status() == 0)
    <tr class="fundo_titulo">
        <th class="linha_titulo" colspan="2">ATENÇÃO: Ordem de Serviço não finalizada!</th>
    </tr>
@endif
<tr>
    <td width="60%">Ordem de Serviço - #{{$OrdemServico->idordem_servico}}</td>
    <td width="40%" rowspan="8" style="text-align: right;">
        <img width="280px" src="{{public_path('uploads/institucional/logo_atlas.png')}}"/>
    </td>
</tr>
<tr>
    <td>{{$Empresa->slogan}}</td>
</tr>
<tr>
    <td>{{$Empresa->razao_social}} / CNPJ: {{$Empresa->cnpjFormatted()}}</td>
</tr>
<tr>
    <td>I.E: {{$Empresa->ieFormatted()}}</td>
</tr>
<tr>
    <td>N° de Autorização: {{$Empresa->n_autorizacao}}</td>
</tr>
<tr>
    <td>{{$Empresa->getFullAddress()}}</td>
</tr>
<tr>
    <td>Fone: {{$Empresa->getPhoneAndCellPhone()}}</td>
</tr>
<tr>
    <td>E-mail: {{$Empresa->email_os}}</td>
</tr>