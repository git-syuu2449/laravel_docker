<?php

namespace App\Services\Admin\Users;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use App\Services\Admin\Users\CsvExporterLaravelExcelService;

class CsvExportLaravelExcelService
{
    /**
     * laravel-excelパッケージを使用してCSVファイルを作成する
     */
    public function download(): BinaryFileResponse
    {
        return Excel::download(new CsvExporterLaravelExcelService, 'users.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}