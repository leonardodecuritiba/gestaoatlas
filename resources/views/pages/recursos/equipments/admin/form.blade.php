@if(isset($Data))
    <div class="x_panel">
        <div class="x_content text-center" id="campo-fotos">
            <div class="form-group">
                <div class="peca_image">
                    <img width="70%" src="{{$Data->getPhoto()}}"/>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="form-group">
    {!! Html::decode(Form::label('description', 'Descrição <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::text('description', old('description'), ['class'=>'form-control', 'placeholder' => 'Descrição', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('idbrand', 'Marca <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('idbrand', $Page->extras['brands'], old('idbrand'), ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('model', 'Modelo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('model', old('model'), ['class'=>'form-control', 'placeholder' => 'Modelo', 'required'])}}
    </div>
</div>

<div class="form-group">
    {!! Html::decode(Form::label('photo', 'Imagem <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {!!Form::file('photo', ['class'=>'form-control', (isset($Data) ? '' :'required')])!!}
    </div>
    {!! Html::decode(Form::label('serial_number', 'Número de Série <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('serial_number', old('serial_number'), ['class'=>'form-control', 'placeholder' => 'Número de Série', 'required'])}}
    </div>
</div>
