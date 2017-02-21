<?php

namespace App\Models;

use Carbon\Carbon;

class ExcelFile extends \Maatwebsite\Excel\Files\NewExcelFile
{

    public function getFilename()
    {
        $data = new Carbon('now');
        return 'export_' . $data->format('d_m_Y-H_i');
    }
}