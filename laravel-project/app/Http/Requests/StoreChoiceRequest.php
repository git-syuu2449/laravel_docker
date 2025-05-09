<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreChoiceRequest extends BaseFormRequest
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
            // 'question_id' => ['required', 'numeric'],
            'choice_text' => ['required', 'string', 'max:255', 'min:10'],
            'votes' => ['required', 'numeric', 'between:0,5'],
        ];
    }

    /**
     * バリデーション前処理
     * @return void
     */
    protected function prepareForValidation()
    {
    }

    /**
     * 追加バリデーション処理
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
    }

    /**
     * バリデーション後処理
     * @return void
     */
    protected function passedValidation()
    {
    }

    /**
     * エラーメッセージの定義
     * @return array{body.min: string, choice_text.required: string}
     */
    public function messages()
    {
        return [
            'choice_text.required',
            'choice_text.max',
            'choice_text.min',
            // votes
            'votes.required',
            // 通常起こり得ないエラーの為個別に設定
            'votes.numeric' => '不正な値です。',
            'votes.between' => '不正な値です。',

        ];
    }

    /**
     * 属性名の定義
     * @return array{}
     */
    public function attributes()
    {
        return [
            'choice_text' => '評価内容',
            'votes' => '評価',
        ];
    }
}
