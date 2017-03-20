@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    @if(count($Buscas) > 0)
        <div class="x_panel">
            <div class="x_title">
                <h2>{{$Page->Targets}} encontrados</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Razão Social</th>
                                <th>Nome Fantasia</th>
                                <th>Documento</th>
                                <th>Nº O.S.</th>
                                <th>Data do Reparo</th>
                                <th>Técnico</th>
                                <th>Descrição O.S.</th>
                                <th>Nº de Série</th>
                                <th>Nº do Inventario</th>
                                <th>Marca de reparo</th>
                                <th>Carga</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Buscas as $Aparelho_manutencao)
                                <?php $Ordem_servico = $Aparelho_manutencao->ordem_servico; ?>
                                <?php $Cliente = $Ordem_servico->cliente->getType(); ?>
                                <?php $Instrumento = $Aparelho_manutencao->instrumento; ?>
                                <tr>
                                    <td>{{$Cliente->razao_social}}</td>
                                    <td>{{$Cliente->nome_principal}}</td>
                                    <td>{{$Cliente->documento}}</td>
                                    <td>{{$Ordem_servico->idordem_servico}}</td>
                                    <td>{{$Ordem_servico->created_at}}</td>
                                    <td>{{$Ordem_servico->colaborador->nome}} - {{$Ordem_servico->colaborador->rg}}</td>
                                    <td>
                                        <span class="red">{{$Aparelho_manutencao->defeito}}</span> /
                                        <span class="green">{{$Aparelho_manutencao->solucao}}</span>
                                    <td>{{$Instrumento->numero_serie}}</td>
                                    <td>{{$Instrumento->inventario}}</td>
                                    <td>{{$Instrumento->selo_afixado_numeracao()}}</td>
                                    <td>{{$Instrumento->capacidade}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('layouts.search.no-results')
    @endif
    <!-- /page content -->
@endsection
@section('scripts_content')
    <!-- Datatables -->
    @include('helpers.datatables.foot')
    <script>
        $(document).ready(function () {
            $('.dt-responsive').DataTable(
                {
                    "language": language_pt_br,
                    "pageLength": 20,
                    "columnDefs": [{
                        "targets": 0,
                        "orderable": false
                    }],
                    "bLengthChange": false, //used to hide the property
                    "bFilter": false
                }
            );
        });
    </script>
    <!-- /Datatables -->
@endsection

