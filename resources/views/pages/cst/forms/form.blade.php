<div class="form-group">
    <label class="control-label col-md-2 col-sm-2 col-xs-12">Numeração: <span class="required">*</span></label>
    <div class="col-md-10 col-sm-10 col-xs-12">
        <input name="numeracao" type="text" maxlength="50" class="form-control" required
               value="{{(isset($Data->numeracao))?$Data->numeracao:old('numeracao')}}">
    </div>
</div>

