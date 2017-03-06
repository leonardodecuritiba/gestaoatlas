<div class="modal fade modalDocumentos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>
        </div>
    </div>
</div>

{!! Form::model($Colaborador, ['method' => 'PATCH','route'=>[$Page->link.'.update',$Colaborador->idcolaborador],
    'files' => true, 'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}
    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Dados do Colaborador</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <span class="btn btn-default btn-xs" id="ver-documentos" data-toggle="modal"
                                  data-target=".modalDocumentos"
                                  data-documentos="{{$Colaborador->getDocumentos()}}"><i class="fa fa-eye fa-2"></i> Ver Documentos</span>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @include('pages.forms.form_colaborador')
                </div>
            </div>
        </div>
    </section>
    @if($Colaborador->user->hasRole('tecnico'))
        <section class="row" id="form_tecnico">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_tecnico')
                </div>
            </div>
        </section>
        <section class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    @include('pages.forms.form_tecnico_financeiro')
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
    <section class="row">
        <div class="form-group ">
            <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                <button type="submit" class="btn btn-success btn-lg btn-block">Salvar</button>
            </div>
        </div>
    </section>
{{ Form::close() }}
    <script>
        $(document).ready(function(){
            $('div.modalDocumentos').on('show.bs.modal', function(e) {
                $origem = $(e.relatedTarget);
                documentos = $($origem).data('documentos');
                console.log(documentos);
                html = '';
                x=0;
                $.each(documentos,function(i,v){
                    if(x==0){ html += '<div class="item active">'; x++;}
                    else{ html += '<div class="item">';}
                    html += '<img class="img-responsive" src="'+ v +'">' +
                            '<div class="carousel-caption"><h3>' + i + '</h3></div>' +
                            '</div>';
                });
                $(this).find('div.carousel-inner').html(html);
            });
        });
    </script>
