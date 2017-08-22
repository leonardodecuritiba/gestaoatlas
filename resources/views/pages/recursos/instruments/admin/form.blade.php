<div class="form-group">
    {!! Html::decode(Form::label('idbase', 'Modelo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idbase', $Page->extras['bases'], old('idbase'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('year', 'Ano <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('year', old('year'), ['class'=>'form-control', 'placeholder' => 'Ano',  'maxlength'=>'4', 'required'])!!}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('serial_number', 'Número de Série <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::text('serial_number', old('serial_number'), ['class'=>'form-control', 'placeholder' => 'Número de Série', 'required'])!!}
    </div>
    {!! Html::decode(Form::label('inventory', 'Inventário <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('inventory', old('inventory'), ['class'=>'form-control', 'placeholder' => 'Inventário', 'required'])}}
    </div>
</div>
