<div class="form-group">
    {!! Html::decode(Form::label('description', 'Descrição <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::text('description', old('description'), ['class'=>'form-control', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('idcategory', 'Categoria <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idcategory', $Page->extras['categories'], old('idcategory'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('idbrand', 'Marca <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idbrand', $Page->extras['brands'], old('idbrand'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('idunit', 'Unidade <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idunit', $Page->extras['unities'], old('idunit'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('cost', 'Custo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('cost', old('cost'), ['class'=>'form-control show-valor', 'placeholder' => 'Custo', 'required'])}}
    </div>
</div>
