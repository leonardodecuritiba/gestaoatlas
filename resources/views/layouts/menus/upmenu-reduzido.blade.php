<div class="row">
    <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="{{ route($Page->link.'.create') }}">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-floppy-o"></i>
                </div>
                <div class="count">Cadastrar</div>
                <h3>Novo {{$Page->Target}}</h3>
            </div>
        </a>
    </div>
    <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="{{$route_exportacao}}">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-upload"></i>
                </div>
                <div class="count">Exportar</div>
                <h3>Lista de {{$Page->Targets}}</h3>
            </div>
        </a>
    </div>
    <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="{{$route_importacao}}">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-download"></i>
                </div>
                <div class="count">Importar</div>
                <h3>Lista de {{$Page->Targets}}</h3>
            </div>
        </a>
    </div>
</div>