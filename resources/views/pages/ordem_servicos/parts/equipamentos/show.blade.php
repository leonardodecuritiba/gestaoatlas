<div class="x_panel">
    <div class="x_title">
        <h2>
            Equipamento
            <small>#{{$Equipamento->idequipamento}}</small>
            <a class="btn btn-danger btn-xs"
               data-nome="Equipamento: #{{$Equipamento->idequipamento}}"
               data-href="{{route('ordem_servicos.equipamentos.remove',$AparelhoManutencao->idaparelho_manutencao)}}"
               data-toggle="modal"
               data-target="#modalDelecao">
                <i class="fa fa-times fa-xs"></i> Cancelar</a>
        </h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <section class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="profile_img">
                    <img class="img-responsive avatar-view" src="{{$Equipamento->getFoto()}}">
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                @include('pages.ordem_servicos.parts.equipamentos.descricao')
            </div>
        </section>
        <div class="ln_solid"></div>

        {{--DESCRIÇÃO--}}
        @if(($AparelhoManutencao->defeito == NULL) && ($AparelhoManutencao->solucao == NULL))
            <section class="row">
                {!! Form::open(['route' => ['aparelho_manutencao.update',$AparelhoManutencao->idaparelho_manutencao],
                    'method' => 'POST',
                    'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
                <div class="form-group col-md-6">
                    <label>Defeitos encontrados: <span class="required">*</span></label>
                    <textarea name="defeito" class="form-control" rows="3"
                              required>{{$AparelhoManutencao->defeito}}</textarea>
                </div>
                <div class="form-group col-md-6">
                    <label>Solução: <span class="required">*</span></label>
                    <textarea name="solucao" class="form-control" rows="3"
                              required>{{$AparelhoManutencao->solucao}}</textarea>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <button class="btn btn-success btn-lg pull-right"><i class="fa fa-check fa-lg"></i> Salvar</button>
                </div>
                {!! Form::close() !!}
            </section>
        @else
            @include('pages.ordem_servicos.insumos.adicionar')
        @endif
    </div>
</div>