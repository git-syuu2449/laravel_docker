<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\Users\CsvExportSimpleExcelService;

class UsersController extends Controller
{
    public function index()
    {
        return view(view: 'admin.users.index');
    }

    public function show()
    {

    }

    /**
     * Summary of csvExportSimpleExcel
     * @param \App\Services\Admin\Users\CsvExportSimpleExcelService $service
     */
    public function csvExportSimpleExcel(CsvExportSimpleExcelService $service)
    {
        Log::info("csvExportSimpleExcel");
        return $service->download();
    }

    public function csvExportLaravelExcel()
    {
        
    }
}
