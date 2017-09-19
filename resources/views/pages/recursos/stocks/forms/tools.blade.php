<div class="form-group">
    {!! Html::decode(Form::label('tool_id', 'Modelo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::select('tool_id', $Page->extras['tools'], '', ['class'=>'form-control select2_single', 'required'])}}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('owner_id', 'Responsável <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::select('owner_id', $Page->extras['colaboradores'], '', ['class'=>'form-control select2_single'])}}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('cost', 'Custo (unid.) <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::text('cost', '', ['class'=>'form-control show-valor', 'placeholder' => 'Custo', 'required'])}}
    </div>
    {!! Html::decode(Form::label('void_id', 'Void <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-4 col-sm-4 col-xs-12">
        {{Form::select('void_id', $Page->extras['voids'], '', ['class'=>'form-control select2_single'])}}
    </div>
</div>
