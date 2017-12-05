<div class="form-group">
    {!! Html::decode(Form::label('part_id', 'Modelo <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::select('part_id', $Page->extras['parts'], '', ['class'=>'form-control select2_single', 'required'])}}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('owner_id', 'Responsável <span class="required">*</span>',
        array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
    <div class="col-md-10 col-sm-10 col-xs-12">
        {{Form::select('owner_id', $Page->extras['colaboradores'], '', ['class'=>'form-control select2_single'])}}
    </div>
</div>
