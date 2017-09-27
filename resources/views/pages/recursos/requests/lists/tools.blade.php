<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
    <div class="x_panel">
        <div class="row">
            @if($Page->extras['return']->count() > 0)
                <div class="x_title">
                    <h2><b>{{$Page->extras['return']->count()}}</b> {{$Page->search_results}}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
							<?php $Buscas = $Page->extras['return']; ?>
                            @include('pages.recursos.stocks.lists.' . $Page->extras['type'])
                        </div>
                    </div>
                </div>
            @else
                <div class="x_content">
                    <div class="row jumbotron">
                        <h1>Ops!</h1>
                        <h2>{{$Page->search_no_results}}</h2>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="form-group">
                <button class="btn btn-primary pull-right"
                        data-option="{{$Page->Target}}"
                        data-toggle="modal"
                        data-target="#modalRequerer"
                        @if(!$Page->extras['can_request'])
                        disabled
                        @endif
                ><i class="fa fa-plus fa-2"></i> Requerer
                </button>
            </div>
        </div>
    </div>
</div>