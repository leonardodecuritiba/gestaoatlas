<div id="search" class="x_panel animated flipInX">
    <div class="x_content">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {!! Form::open(array('route'=>$Page->link.'.index','method'=>'GET','id'=>'search')) !!}
            <div class="col-md-12 col-sm-12 col-xs-12 input-group input-group-lg">
                <input id="buscar" name="busca" type="text" class="form-control" value="{{Request::get('busca')}}" placeholder="Buscar {{$Page->Target}}...">
        <span class="input-group-btn">
            <button class="btn btn-info" type="submit">Buscar</button>
        </span>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>