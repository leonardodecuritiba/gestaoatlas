
@include('pages.recursos.requests.modal.requerer_parts')
<div class="col-md-12 col-sm-12 col-xs-12 animated fadeInDown">
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2><b>{{count($Page->extras['return'])}}</b> {{$Page->search_results}}</h2>
                <button class="btn btn-primary pull-right"
                        data-option="{{$Page->Target}}"
                        data-toggle="modal"
                        data-target="#modalRequererParts"
                        @if(!$Page->extras['can_request'])
                        disabled
                        @endif
                ><i class="fa fa-plus fa-2"></i> Requerer
                </button>
                <div class="clearfix"></div>
            </div>
            @if(count($Page->extras['return']) > 0)
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
    </div>
</div>