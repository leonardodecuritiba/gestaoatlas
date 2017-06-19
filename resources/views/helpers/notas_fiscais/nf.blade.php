<script>
    <!-- script consulta NF -->
    $(document).ready(function () {

        $('div#consultaNF').on('show.bs.modal', function (e) {
            //elementos
            var $this = $(this);
            var $origem = $(e.relatedTarget);
            var $loading_modal = $($this).find('div.loading');
            var $listas_nf = $($this).find('ul.listas_nf');
            var $erros_nf = $($this).find('ul.erros_nf');
            var $btn_refresh = $($this).find('div.modal-footer a#btn-refresh');
            var $btn_enviar_email = $($this).find('ul.listas_nf span#email');
            var $btn_cancel = $($this).find('div.modal-footer button#btn-cancel');
            var $form_cancelamento = $($this).find('div#cancelamento form');

            //data
            var type = $($origem).data('type'); //if is NFe or NFSe
            var debug = $($origem).data('debug'); //if is/not debug
            var idfaturamento = $($origem).data('idfaturamento'); //idfaturamento

            //hidding all
            $($listas_nf).hide();
            $($erros_nf).hide();
            $($this).hide();
            $($btn_refresh).hide();
            $($btn_cancel).hide();
            $($form_cancelamento).hide();

            //get nf data
            var href_ = '';
            href_ = '{{route('faturamentos.nf.get',['_ID_','_DEBUG_','_TYPE_'])}}';
            href_ = href_.replace('_ID_', idfaturamento);
            href_ = href_.replace('_DEBUG_', debug);
            href_ = href_.replace('_TYPE_', type);
            console.log(href_);

            //resend nf
            $($btn_refresh).attr('href', '');

            //cancel nf
            var url_cancel = $($form_cancelamento).attr('action');
            url_cancel = url_cancel.replace('_ID_', idfaturamento);
            url_cancel = url_cancel.replace('_DEBUG_', debug);
            $($form_cancelamento).attr('action', url_cancel.replace('_TYPE_', type));

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
                                //refresh nf
                                var url_refresh = '';
                                url_refresh = '{{route('faturamentos.nf.resend',['_ID_','_DEBUG_','_TYPE_'])}}';
                                url_refresh = url_refresh.replace('_ID_', idfaturamento);
                                url_refresh = url_refresh.replace('_DEBUG_', debug);
                                url_refresh = url_refresh.replace('_TYPE_', type);

                                $($btn_refresh).show();
                                $($btn_refresh).attr('href', url_refresh);
                                $($btn_cancel).show();
                                break;
                            }
                            case 'cancelado': {
                                //renew nf
                                var url_renew = '';
                                url_renew = '{{route('faturamentos.nf.send',['_ID_','_DEBUG_','_TYPE_'])}}';
                                url_renew = url_renew.replace('_ID_', idfaturamento);
                                url_renew = url_renew.replace('_DEBUG_', debug);
                                url_renew = url_renew.replace('_TYPE_', type);

                                $($btn_refresh).show();
                                $($btn_refresh).attr('href', url_renew);
                                break;
                            }
                        }
                    } else if (json.status == 404) {
                        $($btn_cancel).show();

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
        $('div#consultaNF button#btn-cancel').click(function () {
            var $modal_body = $(this).parents('div.modal-footer').prev();
            $($modal_body).find('div#cancelamento form').toggle();
        });

        $('div#consultaNF a.btn-enviar-nota-cliente').click(function () {
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