@extends('layouts.template')
@section('style_content')
    @include('helpers.datatables.head')
    <!-- Select2 -->
    @include('helpers.select2.head')
@endsection
@section('page_content')
    <!-- Seach form -->
    <section class="row">
        @if(count($Page->extras['tools']) > 0)
            <div class="x_panel">
                <div class="x_title">
                    <h2><b>{{$Page->extras['tools']->count()}}</b> {{$Page->Targets}} encontrados</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descrição</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Unidade</th>
                                    <th>Custo</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($Page->extras['tools'] as $sel)
                                    <tr>
                                        <td>{{$sel->id}}</td>
                                        <td>{{$sel->description}}</td>
                                        <td>{{$sel->getCategoryText()}}</td>
                                        <td>{{$sel->getBrandText()}}</td>
                                        <td>{{$sel->getUnityText()}}</td>
                                        <td>{{$sel->getCost()}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{route($Page->link.'.show',$sel->id)}}">
                                                <i class="fa fa-edit"></i></a>
                                            <button class="btn btn-danger btn-xs"
                                                    data-nome="Padrão: {{$sel->description}}"
                                                    data-href="{{route($Page->link.'.destroy',$sel->id)}}"
                                                    data-toggle="modal"
                                                    data-target="#modalRemocao"><i class="fa fa-trash-o fa-sm"></i>
                                            </button>
                                        </td>
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
    </section>
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
                    "pageLength": 10,
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
    <!-- Select2 -->
    @include('helpers.select2.foot')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2_single").select2({
                width: 'resolve'
            });
        });
    </script>
    <!-- /Select2 -->
@endsection

