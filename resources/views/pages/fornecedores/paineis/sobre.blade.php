{!! Form::model($Fornecedor, ['method' => 'PATCH','route'=>[$Page->link.'.update',$Fornecedor->idfornecedor],
    'files' => true, 'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Dados do Fornecedor</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Segmento<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select name="idsegmento_fornecedor" class="form-control" required>
                                    <option value="">Escolha o Segmento</option>
                                    @foreach($Page->extras['segmentos_fornecedores'] as $sel)
                                        <option value="{{$sel->idsegmento_fornecedor}}"
                                        @if($Fornecedor->idsegmento_fornecedor == $sel->idsegmento_fornecedor) selected @endif>{{$sel->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Grupo<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="{{$Fornecedor->grupo}}" type="text" class="form-control" name="grupo" placeholder="Grupo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Email Orçamento<span class="required">*</span></label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input value="{{$Fornecedor->email_orcamento}}" type="text" class="form-control" name="email_orcamento" placeholder="Email" required>
                            </div>
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Nome Responsável<span class="required">*</span></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input value="{{$Fornecedor->nome_responsavel}}" type="text" class="form-control" name="nome_responsavel" placeholder="Nome do Responsável">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php $existe_entidade = 1; $Entidade=$Fornecedor; ?>
    @if($Fornecedor->getType()->tipo_fornecedor)
        <section class="row" id="form_pjuridica">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_pjuridica')
                </div>
            </div>
        </section>
    @else
        <section class="row" id="form_pfisica">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_pfisica')
                </div>
            </div>
        </section>
    @endif

    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                @include('pages.forms.form_contato')
            </div>
        </div>
    </section>
    @if(Auth::user()->hasRole('admin'))
        <section class="row">
            <div class="form-group ">
                <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
                </div>
            </div>
        </section>
    @endif
{{ Form::close() }}
