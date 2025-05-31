<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchQuestionRequest extends BaseFormRequest
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
            'title' => ['string', 'nullable'],
            'body' => ['string', 'nullable'],
            'pub_date_from' => ['bail', 'nullable', 'date'],
            'pub_date_to' => ['bail', 'nullable', 'date'],
        ];
    }

    /**
     * 属性名の定義
     * @return array{}
     */
    public function attributes()
    {
        return [
            'title' => '質問タイトル',
            'body' => '質問本文',
            'pub_date_from' => '投稿日時：From',
            'pub_date_to' => '投稿日時：To',
        ];
    }

    /**
     * 追加のバリデーション
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator)
    {
        // 両方の入力がある場合のみチェック
        $validator->sometimes('pub_date_from', 'before_or_equal:pub_date_to', function ($input) {
            return !empty($input->pub_date_to);
        });

        $validator->sometimes('pub_date_to', 'after_or_equal:pub_date_from', function ($input) {
            return !empty($input->pub_date_from);
        });
    }
}
