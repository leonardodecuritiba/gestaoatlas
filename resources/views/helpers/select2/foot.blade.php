{!! Html::script('vendors/select2/dist/js/select2.min.js') !!}
{!! Html::script('vendors/select2/dist/js/i18n/pt-BR.js') !!}

<script type="text/javascript">
    $(document).ready(function () {
        $(".select2_single").select2({
            width: 'resolve'
        });
    });
</script>