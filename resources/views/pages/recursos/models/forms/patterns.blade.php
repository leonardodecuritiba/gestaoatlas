<div class="form-group">
    {!! Html::decode(Form::label('pattern_id', 'Modelo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{Form::select('pattern_id', $Page->extras['patterns'], '', ['class'=>'form-control select2_single', 'required'])}}
    </div>
    {!! Html::decode(Form::label('quantity', 'Quantidade <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-2 col-sm-2 col-xs-12">
        {{Form::number('quantity', '', ['class'=>'form-control', 'placeholder' => 'Quantidade', 'required'])}}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('cost', 'Custo (unid.) <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('cost', '', ['class'=>'form-control show-valor', 'placeholder' => 'Custo', 'required'])}}
    </div>
    {!! Html::decode(Form::label('expiration', 'Validade <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('expiration', '', ['class'=>'form-control data-every', 'placeholder' => 'Validade', 'required'])}}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('certification_cost', 'Custo Certificação (unid.)<span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('certification_cost', '', ['class'=>'form-control show-valor', 'placeholder' => 'Custo Certificação', 'required'])}}
    </div>
    {!! Html::decode(Form::label('certification', 'Certificação <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('certification', '', ['class'=>'form-control', 'placeholder' => 'Certificação', 'required'])}}
    </div>
</div>
