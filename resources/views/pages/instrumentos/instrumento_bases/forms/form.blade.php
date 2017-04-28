@if(isset($Data) && ($Data->foto != ""))
    <div class="form-group">
        <div class="col-md-offset-4 col-sm-offset-4 col-xs-4 col-md-4 col-sm-4 col-xs-12">
            <div class="peca_image">
                <img src="{{$Data->getThumbFoto()}}" width="70%"/>
            </div>
        </div>
    </div>
    <div class="ln_solid"></div>
@endif
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Marca/Modelo: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <select name="idmodelo" class="form-control select2_single" required>
            <option value="">Escolha um Modelo</option>
            @foreach($Page->extras['instrumento_modelos'] as $sel)
                <option value="{{$sel->id}}"
                        @if(isset($Data) && ($Data->idmodelo  == $sel->id)) selected @endif>{{$sel->getMarcaModelo()}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="descricao" type="text" maxlength="100" class="form-control" required
               value="{{(isset($Data->descricao))?$Data->descricao:old('descricao')}}">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Divisão: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <input name="divisao" type="text" maxlength="100" class="form-control" required
               value="{{(isset($Data->divisao))?$Data->divisao:old('divisao')}}">
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Portaria: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <input name="portaria" type="text" maxlength="100" class="form-control" required
               value="{{(isset($Data->portaria))?$Data->portaria:old('portaria')}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Capacidade: <span class="required">*</span></label>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <input name="capacidade" type="text" maxlength="100" class="form-control" required
               value="{{(isset($Data->capacidade))?$Data->capacidade:old('capacidade')}}">
    </div>
    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Foto:</label>
    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
        <input name="foto" type="file" class="form-control">
    </div>
</div>