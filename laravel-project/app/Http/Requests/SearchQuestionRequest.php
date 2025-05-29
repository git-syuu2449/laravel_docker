<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 後続のチェックを行うか
     * rulesでbailを指定すると有効となる
     * @return bool
     */
    protected function validateBail()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_text' => ['string', 'nullable'],
            'pub_date_from' => ['bail', 'date', 'before_or_equal:pub_date_to'],
            'pub_date_to' => ['bail', 'date', 'after_or_equal:pub_date_from'],
        ];
    }

    /**
     * 属性名の定義
     * @return array{}
     */
    public function attributes()
    {
        return [
            'question_text' => '質問内容',
            'pub_date_from' => '投稿日時：From',
            'pub_date_to' => '投稿日時：To',
        ];
    }
}
