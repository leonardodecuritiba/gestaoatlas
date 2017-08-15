<section class="vehicle-search {{(isset($Data)) ? 'esconda' : ''}}">
    <div class="form-group">
        {!! Html::decode(Form::label('tipo', 'Tipo <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::select('tipo', array_merge(["Selecione um Tipo"], $Page->extras['tipos']), old('tipo'), ['class'=>'form-control select2_single', 'required'])}}
        </div>
        {!! Html::decode(Form::label('marca', 'Marca <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::select('marca', [], old('marca'), ['class'=>'form-control select2_single', 'required'])}}
        </div>
    </div>
    <div class="form-group">
        {!! Html::decode(Form::label('veiculo', 'Veículo <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-10 col-sm-10 col-xs-12">
            {{Form::select('veiculo', [], old('veiculo'), ['class'=>'form-control select2_single', 'required'])}}
        </div>
    </div>
    <div class="form-group">
        {!! Html::decode(Form::label('modelo', 'Modelo <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-10 col-sm-10 col-xs-12">
            {{Form::select('modelo', [], old('modelo'), ['class'=>'form-control select2_single', 'required'])}}
        </div>
    </div>
    <div class="ln_solid"></div>
</section>
<section class="vehicle-edit">
    <div class="form-group">
        {!! Html::decode(Form::label('escolhido', 'Veículo Escolhido <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        @if(isset($Data))
            <div class="col-md-8 col-sm-8 col-xs-12">
                {{Form::text('escolhido', $Data->getEscolhido(), ['class'=>'form-control', 'disabled'])}}
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <button class="btn btn-primary btn-block">Alterar Veículo</button>
            </div>
        @else
            <div class="col-md-10 col-sm-10 col-xs-12">
                {{Form::text('escolhido', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        @endif
    </div>
    {{Form::hidden('id_api', old('id_api'))}}
    {{Form::hidden('ano_modelo', old('ano_modelo'))}}
    {{Form::hidden('marca', old('marca'))}}
    {{Form::hidden('name', old('name'))}}
    {{Form::hidden('veiculo', old('veiculo'))}}
    {{Form::hidden('preco', old('preco'))}}
    {{Form::hidden('combustivel', old('combustivel'))}}
    {{Form::hidden('referencia', old('referencia'))}}
    {{Form::hidden('fipe_codigo', old('fipe_codigo'))}}
    {{Form::hidden('key', old('key'))}}
    <section class="vehicle-hidden esconda">
        <div class="form-group">
            {!! Html::decode(Form::label('id_api', 'id_api',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('id_api', '', ['class'=>'form-control', 'disabled'])}}
            </div>
            {!! Html::decode(Form::label('ano_modelo', 'ano_modelo',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('ano_modelo', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('marca', 'marca',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('marca', '', ['class'=>'form-control', 'disabled'])}}
            </div>
            {!! Html::decode(Form::label('name', 'name',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('name', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('veiculo', 'veiculo',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('veiculo', '', ['class'=>'form-control', 'disabled'])}}
            </div>
            {!! Html::decode(Form::label('preco', 'preco',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('preco', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('combustivel', 'combustivel',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('combustivel', '', ['class'=>'form-control', 'disabled'])}}
            </div>
            {!! Html::decode(Form::label('referencia', 'referencia',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('referencia', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('fipe_codigo', 'fipe_codigo',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('fipe_codigo', '', ['class'=>'form-control', 'disabled'])}}
            </div>
            {!! Html::decode(Form::label('key', 'key',
                array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
            <div class="col-md-4 col-sm-4 col-xs-12">
                {{Form::text('key', '', ['class'=>'form-control', 'disabled'])}}
            </div>
        </div>
    </section>
    <div class="form-group">
        {!! Html::decode(Form::label('renavam', 'Renavam',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-10 col-sm-10 col-xs-12">
            {{Form::text('renavam', old('renavam'), ['class'=>'form-control', 'required'])}}
        </div>
    </div>
    <div class="form-group">
        {!! Html::decode(Form::label('plate', 'Placa <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('plate', old('plate'), ['class'=>'form-control show-placa', 'required'])}}
        </div>
        {!! Html::decode(Form::label('km', 'KM <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('km', old('km'), ['class'=>'form-control show-km', 'required'])}}
        </div>
    </div>
    <div class="form-group">
        {!! Html::decode(Form::label('tires', 'Pneus <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('tires', old('tires'), ['class'=>'form-control show-km', 'required'])}}
        </div>
        {!! Html::decode(Form::label('oil', 'Óleo <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('oil', old('oil'), ['class'=>'form-control show-km', 'required'])}}
        </div>
    </div>
    <div class="form-group">
        {!! Html::decode(Form::label('filter', 'Filtro <span class="required">*</span>',
            array('class' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('filter', old('filter'), ['class'=>'form-control show-km', 'required'])}}
        </div>
        {!! Html::decode(Form::label('wash', 'Lavagem <span class="required">*</span>',
            array('class    ' => 'control-label col-md-2 col-sm-2 col-xs-12'))) !!}
        <div class="col-md-4 col-sm-4 col-xs-12">
            {{Form::text('wash', old('wash'), ['class'=>'form-control data-every', 'required'])}}
        </div>
    </div>
</section>
<script>
    //seleção do selos
    var $_SELECT_TIPOS_ = "select[name=tipo]";
    var $_SELECT_MARCAS_ = "select[name=marca]";
    var $_SELECT_VEICULO_ = "select[name=veiculo]";
    var $_SELECT_MODELO_ = "select[name=modelo]";
    var $_TEXT_ESCOLHIDO_ = "input[name=escolhido]";
    var $_SECTION_VEHICLE_ = "section.vehicle-edit";
    var $_SECTION_VEHICLE_SEARCH_ = "section.vehicle-search";
    $(document).ready(function () {
        $($_SELECT_TIPOS_).change(function () {
            $($_SELECT_MARCAS_).empty().trigger('change');
            var valor = $(this).find(':selected').val();
            if ((valor == '') || (valor == '0') || (valor == null) || (typeof valor == 'undefined')) {
                return false;
            }
            $.ajax({
                url: '{{route('getJsonMarcas')}}',
                type: 'get',
                data: {
                    {{--"_token": "{{ csrf_token() }}",--}}
                    "tipo": $($_SELECT_TIPOS_).find(':selected').val()
                },
                dataType: "json",
                beforeSend: function () {
                    $($_LOADING_).show();
                },
                complete: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                },
                error: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                    console.log('xhr-error:');
                    console.log(xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    var option = new Option('Selecione uma marca', '');
                    option.selected = true;
                    $($_SELECT_MARCAS_).append(option);
                    $($_SELECT_MARCAS_).trigger("change");
                    $.each(json, function (i, v) {
                        option = new Option(v.name, v.id);
                        $($_SELECT_MARCAS_).append(option);
                    });
                }
            });
        });
        $($_SELECT_MARCAS_).change(function () {
            $($_SELECT_VEICULO_).empty().trigger('change');
            var valor = $(this).find(':selected').val();
            if ((valor == '') || (valor == '0') || (valor == null) || (typeof valor == 'undefined')) {
                return false;
            }
            $.ajax({
                url: '{{route('getJsonVeiculos')}}',
                type: 'get',
                data: {
                    {{--"_token": "{{ csrf_token() }}",--}}
                    "tipo": $($_SELECT_TIPOS_).find(':selected').val(),
                    "idmarca": $($_SELECT_MARCAS_).find(':selected').val()
                },
                dataType: "json",
                beforeSend: function () {
                    $($_LOADING_).show();
                },
                complete: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                },
                error: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                    console.log('xhr-error:');
                    console.log(xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    var option = new Option('Selecione um veículo', '');
                    option.selected = true;
                    $($_SELECT_VEICULO_).append(option);
                    $($_SELECT_VEICULO_).trigger("change");
                    $.each(json, function (i, v) {
                        option = new Option(v.name, v.id);
                        $($_SELECT_VEICULO_).append(option);
                    });
                }
            });
        });
        $($_SELECT_VEICULO_).change(function () {
            $($_SELECT_MODELO_).empty().trigger('change');
            var valor = $(this).find(':selected').val();
            if ((valor == '') || (valor == '0') || (typeof valor == 'undefined')) {
                return false;
            }
            $.ajax({
                url: '{{route('getJsonModelo')}}',
                type: 'get',
                data: {
                    {{--"_token": "{{ csrf_token() }}",--}}
                    "tipo": $($_SELECT_TIPOS_).find(':selected').val(),
                    "idmarca": $($_SELECT_MARCAS_).find(':selected').val(),
                    "idveiculo": $($_SELECT_VEICULO_).find(':selected').val()
                },
                dataType: "json",
                beforeSend: function () {
                    $($_LOADING_).show();
                },
                complete: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                },
                error: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                    console.log('xhr-error:');
                    console.log(xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    var option = new Option('Selecione um modelo', '');
                    option.selected = true;
                    $($_SELECT_MODELO_).append(option);
                    $($_SELECT_MODELO_).trigger("change");
                    $.each(json, function (i, v) {
                        option = new Option(v.name, v.id);
                        $($_SELECT_MODELO_).append(option);
                    });
                }
            });

        });
        $($_SELECT_MODELO_).change(function () {
            $($_TEXT_ESCOLHIDO_).val('');
            var valor = $(this).val();
            if ((valor == '') || (valor == '0') || (valor == null) || (typeof valor == 'undefined')) {
                return false;
            }
            $.ajax({
                url: '{{route('getJsonVeiculo')}}',
                type: 'get',
                data: {
                    {{--"_token": "{{ csrf_token() }}",--}}
                    "tipo": $($_SELECT_TIPOS_).find(':selected').val(),
                    "idmarca": $($_SELECT_MARCAS_).find(':selected').val(),
                    "idveiculo": $($_SELECT_VEICULO_).find(':selected').val(),
                    "modelo": $($_SELECT_MODELO_).find(':selected').val()
                },
                dataType: "json",
                beforeSend: function () {
                    $($_LOADING_).show();
                },
                complete: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                },
                error: function (xhr, textStatus) {
                    $($_LOADING_).hide();
                    console.log('xhr-error:');
                    console.log(xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    console.log(json);
                    var text = json.veiculo + ' - ' + json.combustivel + ' (' + json.ano_modelo + ')';
                    $($_TEXT_ESCOLHIDO_).val(text);
                    $($_SECTION_VEHICLE_).find('input[name=id_api]').val(json.id);
                    $.each(json, function (i, v) {
                        $($_SECTION_VEHICLE_).find('input[name=' + i + ']').val(v);
                    })
                }
            });

        })
    });
    @if(isset($Data))
    $(document).ready(function () {
        $($_SECTION_VEHICLE_SEARCH_).find('select').attr('required', false);
    });

    @endif

</script>