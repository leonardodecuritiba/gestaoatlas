
<div class="x_title">
    <h2>Tabela Preço</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        @foreach($Peca->tabela_preco_peca as $tabela_preco_peca)
            <div class="x_title">
                <h4>{{$tabela_preco_peca->tabela_preco->descricao}}</h4>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem" name="margem[{{$tabela_preco_peca->idtabela_preco}}]"
                           value="{{$tabela_preco_peca->margem}}"
                           class="form-control show-porcento calc-tabela_margem" placeholder="Margem">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco" name="preco[{{$tabela_preco_peca->idtabela_preco}}]"
                           value="{{$tabela_preco_peca->preco}}"
                           class="form-control show-valor calc-tabela_preco" placeholder="Preço">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem Mínima:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem_minimo" name="margem_minimo[{{$tabela_preco_peca->idtabela_preco}}]"
                           value="{{$tabela_preco_peca->margem_minimo}}"
                           class="form-control show-porcento calc-tabela_margem-min" placeholder="Margem Mínima">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Mínimo:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco_minimo" name="preco_minimo[{{$tabela_preco_peca->idtabela_preco}}]"
                           value="{{$tabela_preco_peca->preco_minimo}}"
                           class="form-control show-valor calc-tabela_preco-min" placeholder="Preço Mínimo">
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('helpers.tabela_preco.tabela_preco')