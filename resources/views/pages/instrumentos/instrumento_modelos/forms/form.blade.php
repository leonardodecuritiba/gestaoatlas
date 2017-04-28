<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Marca: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <select name="idinstrumento_marca" class="form-control select2_single" required>
            <option value="">Escolha uma Marca</option>
            @foreach($Page->extras['instrumento_marcas'] as $sel)
                <option value="{{$sel->id}}"
                        @if((isset($Data) && ($Data->idinstrumento_marca  == $sel->id))
                        || old("idinstrumento_marca") == $sel->idinstrumento_marca) selected @endif>{{$sel->descricao}}</option>
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

