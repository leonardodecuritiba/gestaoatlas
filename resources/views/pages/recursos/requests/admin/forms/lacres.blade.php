{!! Form::open(['route' => [$Page->extras['type'] . '.repasse'],
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
<input type="hidden" name="id">
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Origem <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <select class="select2_single form-control" name="idorigem" tabindex="-1">
            @foreach($Page->extras['tecnicos'] as $tecnico)
                <option value="{{$tecnico->idtecnico}}">{{$tecnico->colaborador->nome}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Valores: <span
                class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <select name="valores[]" class="select2_multiple-ajax form-control" multiple tabindex="-1"
                placeholder="Selo afixados" required
                data-parsley-errors-container="#select-errors"></select>
        <div id="select-errors"></div>
    </div>
</div>
<div class="form-group">
    <button class="btn btn-success btn-lg pull-right"><i class="fa fa-check fa-2"></i> Confirmar
    </button>
    <a class="btn btn-danger btn-lg pull-right btn-cancel"><i class="fa fa-times fa-2"></i> Cancelar</a>
</div>
{!! Form::close() !!}