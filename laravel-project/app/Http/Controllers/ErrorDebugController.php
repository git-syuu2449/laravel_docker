<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorDebugController extends Controller
{
    public function exeption_500()
    {
        throw new \Exception('テスト用の500エラー');
    }

    public function exeption_abort_500()
    {
        abort(500, 'テスト用の500エラー');
    }
}
