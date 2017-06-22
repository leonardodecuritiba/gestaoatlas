<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{$filename}}</title>
    <style type="text/css">
        html {
            font-family: sans-serif;
            font-size: 11px;
        }

        body {
            margin: 0;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        th {
            text-align: left;
        }

        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }

        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }

        .table > caption + thead > tr:first-child > th,
        .table > colgroup + thead > tr:first-child > th,
        .table > thead:first-child > tr:first-child > th,
        .table > caption + thead > tr:first-child > td,
        .table > colgroup + thead > tr:first-child > td,
        .table > thead:first-child > tr:first-child > td {
            border-top: 0;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 1px solid #ddd;
        }

        .table-bordered > thead > tr > th,
        .table-bordered > thead > tr > td {
            border-bottom-width: 2px;
        }

        .table-condensed > thead > tr > th,
        .table-condensed > tbody > tr > th,
        .table-condensed > tfoot > tr > th,
        .table-condensed > thead > tr > td,
        .table-condensed > tbody > tr > td,
        .table-condensed > tfoot > tr > td {
            padding: 2px;
        }

        .table td {
            text-align: left;
        }

        .fundo_titulo {
            background-color: #000000;
        }

        .fundo_titulo_2 {
            background-color: #1c1c1c;
        }

        .fundo_titulo_3 {
            background-color: #808080;
        }

        .fundo_titulo > .linha_titulo, .fundo_titulo_2 > .linha_titulo, .fundo_titulo_3 > .linha_titulo {
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            text-align: center !important;
        }

        .fundo_titulo > .linha_titulo {
            font-size: 13px !important;
        }

        /*campos*/
        .linha_total > th, .linha_total > td {
            text-align: right !important;
            font-weight: bold;
        }

        .linha_total > td {
            text-align: left !important;
        }

        .sublinhar {
            border-bottom: 1pt solid black !important;
            overflow: hidden;
        }

        .espaco {
            height: 10px;
        }

        .assinatura {
            height: 30px;
            text-align: right;
            vertical-align: bottom !important;
        }

        .page-number:before {
            content: counter(page);
        }
    </style>
    @yield('style_content')
</head>
<body>
@yield('body_content')
</body>
</html>
<style>
</style>