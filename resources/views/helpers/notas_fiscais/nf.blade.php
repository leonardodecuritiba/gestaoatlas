<script>
    <!-- script consulta NF -->
    $(document).ready(function () {
        $('div#consultaNF').on('show.bs.modal', function (e) {
            var $this = $(this);
            var $loading_modal = $($this).find('div.loading');
            var $origem = $(e.relatedTarget);
            var $listas_nf = $($this).find('ul.listas_nf');
            var $erros_nf = $($this).find('ul.erros_nf');
            var $btn_refresh = $($this).find('div.modal-footer a#btn-refresh');
            var $btn_cancel = $($this).find('div.modal-footer a#btn-cancel');
            var type = $($origem).data('type'); //if is NFe or NFSe
            var debug = $($origem).data('debug'); //if is/not debug
            var idfaturamento = $($origem).data('idfaturamento'); //idfaturamento

            var $btn_enviar_email = $($this).find('ul.listas_nf span#email');

            $($listas_nf).hide();
            $($erros_nf).hide();
            $($this).hide();

            $($btn_refresh).hide();
            $($btn_refresh).attr('href', '');
            $($btn_cancel).hide();
            $($btn_cancel).attr('href', '');

            var href_ = '';
            href_ = '{{route('faturamentos.nf.get',['XXX','debug','type'])}}';
            href_ = href_.replace('XXX', idfaturamento);
            href_ = href_.replace('debug', debug);
            href_ = href_.replace('type', type);
            console.log(href_);

            var url_refresh = '';
            url_refresh = '{{route('faturamentos.nf.resend',['XXX','debug','type'])}}';
            url_refresh = url_refresh.replace('XXX', idfaturamento);
            url_refresh = url_refresh.replace('debug', debug);
            url_refresh = url_refresh.replace('type', type);

            var url_cancel = '';
            url_cancel = '{{route('faturamentos.nf.cancel',['XXX','debug','type'])}}';
            url_cancel = url_cancel.replace('XXX', idfaturamento);
            url_cancel = url_cancel.replace('debug', debug);
            url_cancel = url_cancel.replace('type', type);

            $.ajax({
                url: href_,
                type: 'get',
                dataType: "json",
                beforeSend: function () {
                    $($loading_modal).show();
                },
                complete: function (xhr, textStatus) {
                    $($loading_modal).hide();
                },
                error: function (xhr, textStatus) {
                    $($loading_modal).hide();
                    console.log('xhr-error: ' + xhr);
                    console.log('textStatus-error: ' + textStatus);
                },
                success: function (json) {
                    console.log(json);
                    if (json.status == 200) {
                        var TIPO_NF = json.type;
                        var REF = json.ref;
                        var BODY = json.body;
                        var STATUS = BODY.status;
                        var URL = json.url;
                        var $parent = $($this).find('div.modal-body ul.listas_nf');

                        $($parent).show();

                        $($parent).find('b#ref').html(REF);

                        $($parent).find('span.esconda').hide();

                        if (TIPO_NF == 'nfe') {
                            $($this).find('div.modal-header h4.modal-title b').html('NFe');
                        } else {
                            $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                        }
                        $($this).find('div.modal-header h4.modal-title i').html(json.profile);

                        $.each(BODY, function (i, v) {
                            $($parent).find('b#' + i).html(v);
                        });

//                            autorizado – Neste caso a consulta irá conter os demais dados da nota fiscal
//                            processando_autorizacao – A nota ainda está em processamento. Não será devolvido mais nenhum campo além do status
//                            erro_autorizacao – A nota foi enviada ao SEFAZ mas houve um erro no momento da autorização.O campo status_sefaz e mensagem_sefaz irão detalhar o erro ocorrido. O SEFAZ valida apenas um erro de cada vez.
//                            erro_cancelamento – Foi enviada uma tentativa de cancelamento que foi rejeitada pelo SEFAZ. Os campos status_sefaz_cancelamento e mensagem_sefaz_cancelamento irão detalhar o erro ocorrido. Perceba que a nota neste estado continua autorizada.
//                            cancelado – A nota foi cancelada. Além dos campos devolvidos quanto a nota é autorizada, é disponibilizado o campo caminho_xml_cancelamento que contém o protocolo de cancelamento. O campo caminho_danfe deixa de existir quando a nota é cancelada.

                        switch (STATUS) {
                            case 'autorizado': {
                                $($btn_cancel).show();
                                $($btn_cancel).attr('href', url_cancel);
                                $($parent).find('span#' + TIPO_NF).show();
                                //autorizado
                                if (TIPO_NF == 'nfe') {
                                    $($parent).find('b#numero_serie').html(BODY.numero + '/' + BODY.serie);
                                    $($parent).find('a#url_pdf').attr('href', BODY.uri);
                                    $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                } else {
                                    $($parent).find('a#url_pdf').attr('href', BODY.uri);
                                    $($parent).find('a#url_xml').attr('href', URL + BODY.caminho_xml_nota_fiscal);
                                }

                                //botao enviar para cliente
                                //'notas_fiscais.enviar'

                                //{idfaturamento}/{type}/{link}
                                if (debug == 0) {
                                    $($btn_enviar_email).show();
                                    $($btn_enviar_email).find('a').data('idfaturamento', idfaturamento);
                                    $($btn_enviar_email).find('a').data('link', BODY.uri);
                                }
                                break;
                            }
                            case 'erro_autorizacao': {
                                $($btn_refresh).show();
                                $($btn_refresh).attr('href', url_refresh);
                                $($btn_cancel).show();
                                $($btn_cancel).attr('href', url_cancel);

//                                    if (TIPO_NF == 'nfse') {
//                                        var ERROS = BODY.erros;
//                                        $($parent).find('b#codigo').html(ERROS.codigo);
//                                        $($parent).find('b#correcao').html(ERROS.correcao);
//                                        $($parent).find('b#mensagem').html(ERROS.mensagem);
//                                    }
                                break;
                            }
                        }
                    } else if (json.status == 404) {
                        $($btn_cancel).show();
                        $($btn_cancel).attr('href', url_cancel);

                        var TIPO_NF = json.type;
                        var REF = json.ref;
                        var BODY = json.body;
                        var ERROS = BODY.erros[0];

                        $($erros_nf).show();
                        $($erros_nf).find('b#ref').html(REF);

                        if (TIPO_NF == 'nfe') {
                            $($this).find('div.modal-header h4.modal-title b').html('NFe');
                        } else {
                            $($this).find('div.modal-header h4.modal-title b').html('NFSe');
                        }
                        $($this).find('div.modal-header h4.modal-title i').html(json.profile);

                        $($erros_nf).find('b#codigo').html(ERROS.codigo);
                        $($erros_nf).find('b#mensagem').html(ERROS.mensagem);

                    } else {
                        alert(json.body);
                    }
                }
            });

        });
        $('a.btn-enviar-nota-cliente').click(function () {
            var $this = $(this);
            var $loading_modal = $($this).parents('div#consultaNF').find('div.loading');
            var idfaturamento = $($this).data('idfaturamento');
            var link = $(this).data('link');
            var $form = $(this).parent('form');
            $($form).append('<input type="hidden" name="link" value="' + link + '">');

            var url = $($form).attr('action');
            url = url.replace('XXX', idfaturamento);
            $.ajax({
                url: url,
                type: 'POST',
                data: $($form).serializeArray(),
                dataType: "json",
                beforeSend: function () {
                    $($loading_modal).show();
                },
                complete: function (xhr, textStatus) {
                    $($loading_modal).hide();
                },
                success: function (json) {
                    if (json.code) {
                        alert(json.status);
                    } else {
                        alert(json.status);
                    }
                }
            });
        })

    });
</script>