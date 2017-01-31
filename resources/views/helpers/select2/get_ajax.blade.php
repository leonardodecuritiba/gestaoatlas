<script type="text/javascript">
    $(document).ready(function () {
        $NCM_OPTION = [];
        var remoteDataConfig = {
            width: 'resolve',
            ajax: {
                url: $_SELECT2_AJAX['url'],
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term, // search term
                        table: $_SELECT2_AJAX['table'],
                        field: $_SELECT2_AJAX['field'],
                        pk: $_SELECT2_AJAX['pk'],
                        action: $_SELECT2_AJAX['action']
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 3,
            language: "pt-BR"
        };

        $(".select2_single-ajax").select2(remoteDataConfig);
    });
</script>