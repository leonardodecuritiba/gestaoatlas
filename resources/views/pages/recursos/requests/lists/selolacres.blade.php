<div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
    <div class="x_panel">
        <div class="row">
            @if($Page->extras['return']->count() > 0)
                <div class="x_title">
                    <h2><b>{{$Page->extras['return']->count()}}</b> Selos encontrados
                        ({{$Page->extras['return']->where('used',0)->count()}} Disponíveis)</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                            <table class="table table-striped table-bordered dt-responsive nowrap"
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numeração</th>
                                    <th>Numeração Externa</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Page->extras['return'] as $sel)
                                    <tr>
                                        <td>{{$sel->idselo }}</td>
                                        <td>{{$sel->numeracao}}</td>
                                        <td>{{$sel->numeracao_externa}}</td>
                                        <td>
                                            <span class="label label-{{$sel->getStatusColor()}} btn-xs">{{$sel->getStatusText()}}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="x_content">
                    <div class="row jumbotron">
                        <h1>Ops!</h1>
                        <h2>Nenhum Selo Encontrado</h2>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="form-group">
                <button class="btn btn-primary pull-right"
                        data-option="selos"
                        data-toggle="modal"
                        data-target="#modalRequerer"
                        @if(!$Page->extras['can_request_selos'])
                        disabled
                        @endif
                ><i class="fa fa-plus fa-2"></i> Requerer
                </button>
            </div>
        </div>
        <div class="row">
            <div>

            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 animated fadeInDown">
    <div class="x_panel">
        <div class="row">
            @if($Page->extras['lacres']->count() > 0)
                <div class="x_title">
                    <h2><b>{{$Page->extras['lacres']->count()}}</b>
                        Lacres encontrados ({{$Page->extras['lacres']->where('used',0)->count()}} Disponíveis)
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
                            <table class="table table-striped table-bordered dt-responsive nowrap"
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numeração</th>
                                    <th>Numeração Externa</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Page->extras['lacres'] as $sel)
                                    <tr>
                                        <td>{{$sel->idlacre }}</td>
                                        <td>{{$sel->numeracao}}</td>
                                        <td>{{$sel->numeracao_externa}}</td>
                                        <td>
                                            <span class="label label-{{$sel->getStatusColor()}} btn-xs">{{$sel->getStatusText()}}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="x_content">
                    <div class="row jumbotron">
                        <h1>Ops!</h1>
                        <h2>Nenhum Lacre Encontrado</h2>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="form-group">
                <button class="btn btn-primary pull-right"
                        data-option="lacres"
                        data-toggle="modal"
                        data-target="#modalRequerer"
                        @if(!$Page->extras['can_request_lacres'])
                        disabled
                        @endif
                ><i class="fa fa-plus fa-2"></i> Requerer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var MAX = [];
    MAX['selos'] = parseInt('{{$Page->extras['max_selos_can_request']}}');
    MAX['lacres'] = parseInt('{{$Page->extras['max_lacres_can_request']}}');
</script>