<section id="peca">
    <div class="x_panel">
        @include('pages.pecas.forms.principal')
    </div>
    <div class="x_panel" id="tributacao">
        @include('pages.pecas.forms.tributacao')
    </div>
    <div class="x_panel" id="valores">
        @include('pages.pecas.forms.valores')
    </div>
    <div class="x_panel" id="tabela_preco">
        @include('pages.pecas.forms.tabela_preco')
    </div>
</section>
<script>

    var $container_form = $('section#peca');

    var required_fields = ['codigo',
        'descricao',
        'descricao_tecnico',
        'tipo',
        'idmarca',
        'idunidade',
        'idgrupo',
        'comissao_tecnico',
        'comissao_vendedor',
        'custo_final'
        /*
         'custo_compra',
         'custo_imposto',

         'idncm',
         'idcategoria_tributacao',
         'idorigem_tributacao',
         'idcst_ipi',
         'peso_liquido',
         'peso_bruto',
         'ipi',
         'icms',
         'reducao_bc_icms',
         'reducao_bc_icms_st',
         'aliquota_icms',

         'aliquota_nacional',
         'aliquota_importacao'
         */
    ];

    $(document).ready(function () {
        $.each(required_fields, function (i, v) {
            $($container_form).find('input[name="' + v + '"]').prop('required', true);
        });
    });

</script>
<script>
    //cálculos
    /*
     $(document).ready(function(){
     $('input.calc-custo-reais').change(function(){
     $parent = $(this).parents('div#custo_reais');
     var campos = ['custo_compra','custo_frete','custo_imposto'];
     var custo_final = 0;
     $.each(campos, function(i,v){
     valor = $($parent).find('input[name='+v+']').maskMoney('unmasked');
     custo_final += valor[0];
     });
     $($parent).find('input[name=custo_final]').maskMoney('mask', custo_final);

     });
     $('input.calc-dolar').change(function(){
     $parent = $(this).parents('div#custo_dolar');
     var campos = ['custo_dolar_cambio','custo_dolar','custo_dolar_frete','custo_dolar_imposto'];

     soma = 0;

     //Cálculo do preço
     custo_dolar_cambio = $($parent).find('input[name=custo_dolar_cambio]').maskMoney('unmasked');
     custo_dolar        = $($parent).find('input[name=custo_dolar]').maskMoney('unmasked');
     preco = custo_dolar_cambio[0] * custo_dolar[0];
     soma += preco;
     $($parent).find('input[name=preco]').maskMoney('mask', preco);

     //Cálculo do preço do frete
     custo_dolar_frete = $($parent).find('input[name=custo_dolar_frete]').maskMoney('unmasked');
     preco = custo_dolar_cambio[0] * custo_dolar_frete[0];
     soma += preco;
     $($parent).find('input[name=preco_frete]').maskMoney('mask', preco);

     //Cálculo do preço do frete
     custo_dolar_imposto = $($parent).find('input[name=custo_dolar_imposto]').maskMoney('unmasked');
     preco = custo_dolar_cambio[0] * custo_dolar_imposto[0];
     soma += preco;
     $($parent).find('input[name=preco_imposto]').maskMoney('mask', preco);

     //Cálculo do preço final
     $($parent).find('input[name=preco_final]').maskMoney('mask', soma);
     });


     $('input.calc-tabela_preco').change(function(){
     $parent = $(this).parent('form-group');
     $preco_final = $($container_form).find('div#valores div#custo_reais input[name=custo_final]');
     //            $preco_final = $($container_form).find('div#valores div#custo_dolar input[name=preco_final]');
     var campos = ['preco','margem'];
     $.each(campos, function(i,v){
     valor = $($parent).find('input[name='+v+']').maskMoney('unmasked');
     custo_final += valor[0];
     });
     $($parent).find('input[name=custo_final]').maskMoney('mask', custo_final);

     });
     });
     */
</script>