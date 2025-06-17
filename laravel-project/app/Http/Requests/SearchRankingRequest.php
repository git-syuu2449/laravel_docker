<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use App\Enums\RankingType;

class SearchRankingRequest extends BaseFormRequest
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
     * バリデーション後処理
     * @return void
     */
    protected function passedValidation()
    {
        // typeをenum変換する
        //  →validateを上書きできず、リクエストにmergeするしかないので使わない。
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // dailyなどvalueがくる
            'type' => ['required'], Rule::enum(RankingType::class)
        ];
    }

    /**
     * 属性名の定義
     * @return array{}
     */
    public function attributes()
    {
        return [
            'type' => 'タイプ',
        ];
    }

    /**
     * エラーメッセージの定義
     * @return array{body.min: string, choice_text.required: string}
     */
    public function messages()
    {
        return [
            // 通常起こり得ないエラーの為個別に設定
            'type.required' => '不正な値です。',
            'type.rule.enum' => '不正な値です。',
        ];
    }
}
