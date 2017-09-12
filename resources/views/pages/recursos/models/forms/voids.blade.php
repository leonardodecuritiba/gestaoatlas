<div class="form-group">
    {!! Html::decode(Form::label('ni', 'Início <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('ni', '', ['class'=>'form-control', 'placeholder' => 'Início', 'required'])}}
    </div>
    {!! Html::decode(Form::label('nf', 'Final <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('nf', '', ['class'=>'form-control', 'placeholder' => 'Fim', 'required'])}}
    </div>
</div>