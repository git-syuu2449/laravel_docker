<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Choice;

use function PHPUnit\Framework\isNull;

class ChoiceController extends Controller
{
    public function index(int $question_id)
    {
        $choices = Choice::query()->questionId($question_id)->get();

        return response()->json([
            'status' => true,
            'choices' => $choices
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($question_id, $choice_id)
    {
        try
        {
            $choice = Choice::where([
                'id' => $choice_id,
                'user_id' => Auth::id(),
                'question_id' => $question_id,
            ])->firstOrFail();

            $choice->delete();

            return response()->json(['message' => '削除に成功しました'], 200);

        }
        // 404
        catch (ModelNotFoundException $e) {
            report($e);

            return response()->json(['message' => '削除に失敗しました'], 404);
        }
        catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => '削除に失敗しました'], 500);
        }
    }

}
