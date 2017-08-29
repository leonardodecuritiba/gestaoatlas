<div class="x_panel">
    <div class="x_title">
        <h2>
            Instrumento
            <small>#{{$Instrumento->idinstrumento}}</small>
            <a class="btn btn-danger btn-xs"
               data-nome="Instrumento: #{{$Instrumento->idinstrumento}}"
               data-href="{{route('ordem_servicos.instrumentos.remove',$AparelhoManutencao->idaparelho_manutencao)}}"
               data-toggle="modal"
               data-target="#modalDelecao">
                <i class="fa fa-times fa-xs"></i> Remover</a>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @include('pages.ordem_servicos.parts.instrumentos.selo_lacres')
        <div class="ln_solid"></div>
        @if(($AparelhoManutencao->defeito != NULL) && ($AparelhoManutencao->solucao != NULL))
            @include('pages.ordem_servicos.insumos.adicionar')
        @else
            @include('pages.ordem_servicos.insumos.setSeloLacre')
        @endif
    </div>
</div>