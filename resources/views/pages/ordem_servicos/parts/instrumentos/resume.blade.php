<div class="x_panel">
    <div class="x_title">
        <h2>
            Instrumento
            <small>#{{$AparelhoManutencao->idaparelho_manutencao}} - {{$Instrumento->idinstrumento}}</small>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @include('pages.ordem_servicos.parts.instrumentos.selo_lacres')
        <div class="ln_solid"></div>
        @include('pages.ordem_servicos.insumos.listar')
    </div>
</div>