{{--
<!-- Modal agendar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Faturamentos pelo profissional Vitor Rebello <small style="float:right;">Recebimentos</small></h4>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled user_data">
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li style="border-bottom:2px solid #E9EDEF;">
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>
                    <li>
                        <h5>09/09/2016  <small style="float:right;">R$500,00</small></h5>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
<!-- cancelar -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja cancelar essa consulta?</h4>
            </div>
            <div class="modal-body" style="background:rgba(217, 83, 79, 0.57);">
                <button type="button" class="btn btn-round btn-success">Sim</button>
                <button type="button" class="btn btn-round btn-danger">Não</button>
            </div>
        </div>
    </div>
</div>
<!-- excluir consulta cancelada -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja excluir essa consulta cancelada?</h4>
            </div>
            <div class="modal-body" style="background:rgba(217, 83, 79, 0.57);">
                <button type="button" class="btn btn-round btn-success">Sim</button>
                <button type="button" class="btn btn-round btn-danger">Não</button>
            </div>
        </div>
    </div>
</div>
<!-- reagendar -->--}}
{{--<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >--}}
    {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content" style="height:330px;">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--<h4 class="modal-title" id="exampleModalLabel">Para qual data ficará essa consulta?</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-md-3 col-sm-3 col-xs-3">Nome do paciente:</label>--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-9">--}}
                        {{--<input type="text" class="form-control" name="nome" maxlength="100" >--}}
                        {{--<span class="fa fa-archive form-control-feedback right" aria-hidden="true"></span>--}}
                    {{--</div>--}}
                {{--</div><br><br>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-md-3 col-sm-3 col-xs-3">Data:</label>--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-9">--}}
                        {{--<input type="date" class="form-control" name="data" maxlength="8" >--}}
                        {{--<span class="fa fa-archive form-control-feedback right" aria-hidden="true"></span>--}}
                    {{--</div>--}}
                {{--</div><br><br>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-md-3 col-sm-3 col-xs-3">Horário:</label>--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-9">--}}
                        {{--<input type="number" class="form-control" name="horario" maxlength="5" >--}}
                        {{--<span class="fa fa-archive form-control-feedback right" aria-hidden="true"></span>--}}
                    {{--</div>--}}
                {{--</div><br><br>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-md-3 col-sm-3 col-xs-3">Telefone:</label>--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-9">--}}
                        {{--<input type="number" class="form-control" name="telefone" maxlength="12" >--}}
                        {{--<span class="fa fa-archive form-control-feedback right" aria-hidden="true"></span>--}}
                    {{--</div>--}}
                {{--</div><br><br><br>--}}
                {{--<div style="float:right;">--}}
                    {{--<button type="button" class="btn btn-round btn-success">Atualizar</button>--}}
                    {{--<button type="button" class="btn btn-round btn-danger">Cancelar</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}


{{--Modal excluir--}}
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="modalExcluir" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="background:rgba(217, 83, 79, 0.57);">
                <button type="button" class="btn btn-round btn-success">Sim</button>
                <button type="button" class="btn btn-round btn-danger">Não</button>
            </div>
        </div>
    </div>
</div>