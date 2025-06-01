<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Choice;

use function PHPUnit\Framework\isNull;

class ChoiceController extends Controller
{
    public function index()
    {
        $choices = Choice::query()->userId(Auth::id())->get();

        $choices->each(function (Choice $choice) {
            $choice['delete_url'] = $choice->delete_url;
        });

        return response()->json([
            'status' => true,
            'choices' => $choices
        ], 201);

    }

}
