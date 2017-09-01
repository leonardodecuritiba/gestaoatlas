<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::text('description', old('description'), ['class'=>'form-control', 'required'])}}
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Medida: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('measure', old('measure'), ['class'=>'form-control show-valor-real', 'required'])}}
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idunit', $Page->extras['unities'], old('idunit'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('cost', 'Marca <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idbrand', $Page->extras['brands'], old('idbrand'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('cost', 'Custo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('cost', old('cost'), ['class'=>'form-control show-valor', 'placeholder' => 'Custo', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('class', 'Classe <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {!!Form::text('class', old('class'), ['class'=>'form-control', 'placeholder' => 'Classe', 'required'])!!}
    </div>
</div>
