<div class="x_title">
    <h2>Tabela Preço</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        <?php $Tabelas_precos = isset($Servico) ? $Servico->tabela_preco : $Page->extras['tabela_preco'];?>
        @foreach($Tabelas_precos as $tabela_preco)
            <div class="x_title">
                <h4>{{isset($Servico)?$tabela_preco->tabela_preco->descricao:$tabela_preco->descricao}}</h4>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem" name="margem[{{$tabela_preco->idtabela_preco}}]"
                           value="{{$tabela_preco->margem}}"
                           class="form-control show-porcento calc-tabela_margem" placeholder="Margem">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco" name="preco[{{$tabela_preco->idtabela_preco}}]"
                           value="{{$tabela_preco->preco}}"
                           class="form-control show-valor calc-tabela_preco" placeholder="Preço">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem Mínima:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem_minimo" name="margem_minimo[{{$tabela_preco->idtabela_preco}}]"
                           value="{{$tabela_preco->margem_minimo}}"
                           class="form-control show-porcento calc-tabela_margem-min" placeholder="Margem Mínima">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Mínimo:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco_minimo" name="preco_minimo[{{$tabela_preco->idtabela_preco}}]"
                           value="{{$tabela_preco->preco_minimo}}"
                           class="form-control show-valor calc-tabela_preco-min" placeholder="Preço Mínimo">
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('helpers.tabela_preco.tabela_preco')