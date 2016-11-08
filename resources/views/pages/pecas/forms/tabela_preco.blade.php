
<div class="x_title">
    <h2>Tabela Preço (NÃO ESTÁ FUNCIONAL)</h2>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="form-horizontal form-label-left">
        @foreach($Page->extras['tabela_preco'] as $sel)
            <div class="x_title">
                <h4>{{$sel->descricao}}</h4>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem" name="margem[{{$sel->idtabela_preco}}]" class="form-control show-porcento calc-tabela_preco" placeholder="Margem">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco" name="preco[{{$sel->idtabela_preco}}]" class="form-control show-valor calc-tabela_preco" placeholder="Preço">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Margem Mínima:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="margem_minimo" name="margem_minimo[{{$sel->idtabela_preco}}]" class="form-control show-porcento calc-tabela_preco-min" placeholder="Margem Mínima">
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Preço Mínimo:</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" id="preco_minimo" name="preco_minimo[{{$sel->idtabela_preco}}]" class="form-control show-valor calc-tabela_preco-min" placeholder="Preço Mínimo">
                </div>
            </div>
        @endforeach
    </div>
</div>