<?php

namespace App\Http\Exports\IpemList;

use \Maatwebsite\Excel\Files\NewExcelFile;

class IpemListExport extends NewExcelFile
{
    public function getFilename()
    {
        return 'listaIpem';
    }

    public function getFonts()
    {
        $default_size = '15';
        return (object)[
            'cabecalho_font' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'cabecalho_font_color' => '#FFFFFF',
            'cabecalho_background_color' => '#000000',
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '16',
            ),
            'descricao' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'endereco' => array(
                'size' => $default_size,
            ),
            'quebra' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'normal' => array(
                'size' => $default_size,
            )
        ];
    }
}