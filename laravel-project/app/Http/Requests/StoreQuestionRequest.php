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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_text' => ['required', 'string', 'max:255', 'min:10'],
            // 'body'  => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * バリデーション前処理
     * @return void
     */
    protected function prepareForValidation()
    {
        // 入力データ取得
        $question_text = $this->input('question_text');
    }

    /**
     * 追加バリデーション処理
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $question_text = $this->input('question_text');
        });
    }

    /**
     * バリデーション後処理
     * @return void
     */
    protected function passedValidation()
    {
        $question_text = $this->input('question_text');
    }

    /**
     * エラーメッセージの定義
     * @return array{body.min: string, question_text.required: string}
     */
    public function messages()
    {
        return [
            'question_text.required',
            'question_text.max',
            'question_text.min'
        ];
        // validation.phpを参照するようにした
        /*
        return [
            // question_text
            'question_text.required' => ':attribute は必須です。',
            'question_text.max' => ':attribute は :max 文字まで入力可能です。',
            'question_text.min' => ':attribute は最低 :min 文字必要です。',
        ];
        */
    }

    /**
     * 属性名の定義
     * @return array{body: string, question_text: string}
     */
    public function attributes()
    {
        return [
            'question_text' => '質問内容',
        ];
    }
}
