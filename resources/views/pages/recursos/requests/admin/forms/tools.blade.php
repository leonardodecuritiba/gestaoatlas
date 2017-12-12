{!! Form::open(['route' => [$Page->extras['type'] . '.repasse'],
                'method' => 'POST',
                'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
<input type="hidden" name="id">
<div class="form-group">
    <button class="btn btn-success btn-lg pull-right"><i class="fa fa-check fa-2"></i> Confirmar
    </button>
    <a class="btn btn-danger btn-lg pull-right btn-cancel"><i class="fa fa-times fa-2"></i> Cancelar</a>
</div>
{!! Form::close() !!}