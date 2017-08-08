<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::text('description', old('description'), ['class'=>'form-control', 'required'])}}
    </div>
</div>


<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Medida: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('measure', old('measure'), ['class'=>'form-control', 'required'])}}
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idunit', $Page->extras['unities'], old('idunit'), ['class'=>'form-control select2_single'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('brand', 'Marca <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('brand', old('brand'), ['class'=>'form-control', 'required'])}}
    </div>
    {!! Html::decode(Form::label('cost', 'Custo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('cost', old('cost'), ['class'=>'form-control show-valor', 'required', 'placeholder' => 'Custo'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('class', 'Classe <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('class', old('class'), ['class'=>'form-control', 'required', 'placeholder' => 'Classe'])!!}
    </div>
    {!! Html::decode(Form::label('expiration', 'Validade <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('expiration', old('expiration'), ['class'=>'form-control data-every', 'required', 'placeholder' => 'Validade'])!!}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('certification', 'Certificação <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('certification', old('certification'), ['class'=>'form-control', 'required', 'placeholder' => 'Certificação'])!!}
    </div>
    {!! Html::decode(Form::label('cost_certification', 'Custo Certificação <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('cost_certification', old('cost_certification'), ['class'=>'form-control show-valor', 'required', 'placeholder' => 'Custo Certificação'])!!}
    </div>
</div>