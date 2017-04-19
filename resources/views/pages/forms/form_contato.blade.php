<div class="x_title">
    <h2>Dados de Contato</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Telefone </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->telefone:old('telefone')}}" type="text" class="form-control show-telefone" name="telefone">
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Celular </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->celular:old('celular')}}" type="text" class="form-control show-celular" name="celular">
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Skype </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->skype:old('skype')}}" type="text" class="form-control" name="skype">
            </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-12">CEP </label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->cep:old('cep')}}" type="text" class="form-control show-cep" name="cep" id="cep">
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Estado </label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->estado:old('estado')}}" type="text" class="form-control" name="estado">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Cidade </label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->cidade:old('cidade')}}" type="text" class="form-control" name="cidade">
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Cód. Município </label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->codigo_municipio:old('codigo_municipio')}}"
                       type="text" class="form-control" name="codigo_municipio"
                       required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Bairro </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->bairro:old('bairro')}}" type="text" class="form-control" name="bairro">
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-12">Logradouro </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->logradouro:old('logradouro')}}" type="text" class="form-control" name="logradouro">
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->numero:old('numero')}}" type="text" class="form-control" name="numero" placeholder="Número">
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->contato->complemento:old('complemento')}}" type="text" class="form-control" name="complemento" placeholder="Complemento">
            </div>
        </div>
    </div>
</div>

<script>
    //BUSCA CEP
    $(document).ready(function() {
        $("#cep").change(function () {
            var cep_code = $(this).val();
            if (cep_code.length <= 0) return;
            $.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {code: cep_code},
                function (result) {
                    if (result.status != 1) {
                        alert(result.message || "Houve um erro desconhecido");
                        return;
                    }
                    console.log(result);
                    $("input#cep").val(result.code);
                    $("input[name=logradouro]").val(result.address);
                    $("input[name=bairro]").val(result.district);
                    $("input[name=cidade]").val(result.city);
                    $("input[name=estado]").val(result.state);
                });
        });
    });
</script>
