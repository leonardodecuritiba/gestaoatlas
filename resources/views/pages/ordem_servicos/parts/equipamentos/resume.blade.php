<div class="x_panel">
    <div class="x_title">
        <h2>
            Equipamento
            <small>#{{$Equipamento->idequipamento}}</small>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="profile_img">
                    <img class="img-responsive avatar-view" src="{{$Equipamento->getFoto()}}">
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                @include('pages.ordem_servicos.parts.equipamentos.descricao')
            </div>
        </div>
        <div class="ln_solid"></div>
        <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Resumo</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h2>Defeito:</h2>
                        <p>{{$AparelhoManutencao->defeito}}</p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h2>Solução:</h2>
                        <p>{{$AparelhoManutencao->solucao}}</p>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.ordem_servicos.insumos.listar')
    </div>
</div>