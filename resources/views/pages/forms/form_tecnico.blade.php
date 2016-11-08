<div class="x_title">
    <h2>Dados do Técnico</h2>
    @if($existe_entidade)
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <span class="btn btn-default btn-xs" id="ver-documentos" data-toggle="modal"
                      data-target=".modalDocumentos"
                      data-documentos="{{$Colaborador->tecnico->getDocumentos()}}"><i class="fa fa-eye fa-2"></i> Ver Documentos</span>
            </li>
        </ul>
    @endif
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Cart. IMETRO <span class="required">*</span></label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->tecnico->carteira_imetro:old('carteira_imetro')}}"
                       type="file" class="form-control" name="carteira_imetro" placeholder="Carteira IMETRO" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Cart. IPEM <span class="required">*</span></label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <input value="{{($existe_entidade)?$Entidade->tecnico->carteira_ipem:old('carteira_ipem')}}"
                       type="file" class="form-control" name="carteira_ipem" placeholder="Carteira IPEM" required>
            </div>
        </div>
    </div>
</div>
