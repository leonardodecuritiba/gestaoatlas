@extends('layouts.template')
@section('page_content')
    @include('layouts.modals.sintegra')
    <div class="x_panel">

    </div>
@endsection
@section('scripts_content')



    <script>
        //CONSULTA CNPJ
        $(document).ready(function () {
            $("div#captchaModal").on("hide.bs.modal", function () {
                $parent = $(this).find('div.modal-body');
                $($parent).find("img").attr("src", '');
                $($parent).find("div.form-group").removeClass('has-error');
                $($parent).find("div.form-group span").empty();
                $($parent).find("div.form-group input[name=captcha]").val('');
            });
            $("div#captchaModal").on("show.bs.modal", function () {
                $parent = $(this).find('div.modal-body');
                $loading_modal = $(this).find('div.loading');
                $($loading_modal).show();
                $.ajax({
                    url: "{{route('get_sintegra_params')}}",
                    type: 'GET',
                    dataType: "json",
                    error: function (xhr, textStatus) {
                        console.log('xhr-error: ' + xhr.responseText);
                        console.log('textStatus-error: ' + textStatus);
                    },
                    success: function (result) {
                        console.log(result);
                        $($loading_modal).hide();
                        $($parent).find("img").attr("src", result.captchaBase64);
                        $($parent).find("input[name=paramBot]").val(result.paramBot);
                        $($parent).find("input[name=cookie]").val(result.cookie);
                    }
                });
            });
            $("#consultarCNPJ").click(function () {
                var btn = $(this);
                var old = btn.html();

                $parent = $('div#captchaModal').find('div.modal-body');
                $($parent).find("input[name=captcha]").parent('div.form-group').removeClass('has-error');
                $($parent).find("input[name=captcha]").siblings('span').empty();

                var param = {
                    cnpj: $("input[name=cnpj]").val(),
                    paramBot: $($parent).find("input[name=paramBot]").val(),
                    captcha: $($parent).find("input[name=captcha]").val(),
                    cookie: $($parent).find("input[name=cookie]").val()
                };

                console.log(param);

                btn.html('Aguarde! Consultando..');

                //consulta.php
                $.get("{{route('consulta_sintegra_sp')}}", param, function (retorno) {

                    console.log(retorno.status);
                    if (retorno.status == 1) {

                        $.each(retorno, function (i, v) {
//                            console.log(i);console.log(v);
                            $("input[name=" + i + "").val(v.toUpperCase());
                        });
                        $('#captchaModal').modal('hide');
                    } else {
                        $($parent).find("input[name=captcha]").parent('div.form-group').addClass('has-error');
                        $($parent).find("input[name=captcha]").siblings('span').html(retorno.response);
                    }

                    btn.html(old);

                }, "json");

            });
        });

    </script>

@endsection

