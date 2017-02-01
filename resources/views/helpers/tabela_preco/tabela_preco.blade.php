<script type="text/javascript">
    function converteFloatMoeda(valor) {
        var inteiro = null, decimal = null, c = null, j = null;
        var aux = new Array();
        valor = "" + valor;
        c = valor.indexOf(".", 0);
        //encontrou o ponto na string
        if (c > 0) {
            //separa as partes em inteiro e decimal
            inteiro = valor.substring(0, c);
            decimal = valor.substring(c + 1, valor.length);
        } else {
            inteiro = valor;
        }

        //pega a parte inteiro de 3 em 3 partes
        for (j = inteiro.length, c = 0; j > 0; j -= 3, c++) {
            aux[c] = inteiro.substring(j - 3, j);
        }

        //percorre a string acrescentando os pontos
        inteiro = "";
        for (c = aux.length - 1; c >= 0; c--) {
            inteiro += aux[c] + '.';
        }
        //retirando o ultimo ponto e finalizando a parte inteiro

        inteiro = inteiro.substring(0, inteiro.length - 1);

        decimal = parseInt(decimal);
        if (isNaN(decimal)) {
            decimal = "00";
        } else {
            decimal = "" + decimal;
            if (decimal.length === 1) {
                decimal = decimal + "0";
            }
        }
        return inteiro + "," + decimal;
    }
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
            $(this).parents('div.form-group').find('input#margem').val(converteFloatMoeda(margem));
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
            $(this).parents('div.form-group').find('input#preco').val(converteFloatMoeda(preco));
//            $(this).parents('div.form-group').find('input#preco').maskMoney('mask', 1999.99);
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
            $(this).parents('div.form-group').find('input#margem_minimo').val(converteFloatMoeda(margem));
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
            $(this).parents('div.form-group').find('input#preco_minimo').val(converteFloatMoeda(preco));
        });
    });

</script>