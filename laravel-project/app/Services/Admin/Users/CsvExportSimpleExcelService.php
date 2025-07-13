<?php

namespace App\Services\Admin\Users;

use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelWriter;


use App\Services\Admin\Users\CsvExporterSimpleExcelService;

class CsvExportSimpleExcelService
{

    public function __construct(private CsvExporterSimpleExcelService $exporter) {}

    /**
     * simple-excelパッケージを使用してCSVファイルを作成する
     */
    public function download()
    {
        $writer = SimpleExcelWriter::streamDownload('users.csv'); // ローカル上に保存する時はcreateメソッド
        $this->exporter->writeTo($writer);
        return $writer->toBrowser();
    }
}