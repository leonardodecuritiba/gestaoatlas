<script type="text/javascript">
    $(document).ready(function () {
        //PREÇO
        $(".calc-tabela_preco").change(function () {
            $div_pai = $(this).parents('div.div_pai');
            var custo = $($div_pai).find('input#valor-ref').maskMoney('unmasked')[0];

            //pega preco
            var preco = $(this).maskMoney('unmasked')[0];
            var margem = ((preco - custo) / custo) * 100;

            //atualiza margem
            margem = (margem.toFixed(2));
            $(this).parents('div.form-group').find('input#margem').val(margem);
        });
        //MARGEM
        $(".calc-tabela_margem").change(function () {
            $div_pai = $(this).parents('div.div_pai');
            var custo = $($div_pai).find('input#valor-ref').maskMoney('unmasked')[0];

            //pega margem
            var margem = $(this).maskMoney('unmasked')[0] / 100;
            var preco = custo + (custo * margem);

            //atualiza preco_min
            preco = (preco.toFixed(2));
            $(this).parents('div.form-group').find('input#preco').val(preco);
        });
    });

    $(document).ready(function () {
        //PREÇO MÍNIMO
        $(".calc-tabela_preco-min").change(function () {
            $div_pai = $(this).parents('div.div_pai');
            var custo = $($div_pai).find('input#valor-ref').maskMoney('unmasked')[0];

            //pega preco_min
            var preco = $(this).maskMoney('unmasked')[0];
            var margem = ((preco - custo) / custo) * 100;

            //atualiza margem_minimo
            margem = (margem.toFixed(2));
            $(this).parents('div.form-group').find('input#margem_minimo').val(margem);
        });
        //MARGEM MÍNIMO
        $(".calc-tabela_margem-min").change(function () {
            $div_pai = $(this).parents('div.div_pai');
            var custo = $($div_pai).find('input#valor-ref').maskMoney('unmasked')[0];

            //pega margem_minimo
            var margem = $(this).maskMoney('unmasked')[0] / 100;
            var preco = custo + (custo * margem);

            //atualiza preco_min
            preco = (preco.toFixed(2));
            $(this).parents('div.form-group').find('input#preco_minimo').val(preco);
        });
    });

</script>