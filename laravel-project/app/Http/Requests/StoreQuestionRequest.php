<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends BaseFormRequest
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
            'title' => ['required', 'string', 'max:255', 'min:10'],
            'body' => ['required', 'string', 'max:500', 'min:10'],
            'images' => ['required', 'array'],
            'images.*' => ['bail', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    /**
     * バリデーション前処理
     * バリデーション前に何か処理が必要な場合はここに差し込める
     * @return void
     */
    protected function prepareForValidation()
    {
        // 入力データ取得
        $title = $this->input('title');
        $body = $this->input('body');
        $images = $this->file('images');
    }

    /**
     * 追加バリデーション処理
     * 独自のバリデーション処理を行う場合はここで行う。
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $title = $this->input('title');
        });
    }

    /**
     * バリデーション後処理
     * バリデーション後に整形等何か処理が必要な場合はここに差し込める
     * @return void
     */
    protected function passedValidation()
    {
        $title = $this->input('title');
    }

    /**
     * エラーメッセージの定義
     * @return array{body.min: string, title.required: string}
     */
    public function messages()
    {
        // validation.phpを参照するようにした。lang/*/validation.phpに記載がある場合は個別の設定は不要
        // 明示的に記載するのはあり
        return [
        ];
    }

    /**
     * 属性名の定義
     * @return array{body: string, images: string, images.*: string, title: string}
     */
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'images' => '画像',
            'images.*' => '画像',
        ];
    }
}
