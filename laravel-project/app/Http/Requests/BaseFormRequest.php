<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Summary of BaseFormRequest
 */
abstract class BaseFormRequest extends FormRequest
{
    // /**
    //  * Handle a failed validation attempt.
    //  *
    //  * @param  Validator  $validator
    //  * @return void
    //  */
    // protected function failedValidation(Validator $validator)
    // {
    //     $url = $this->getRedirectUrl();

    //     $response = redirect($url)
    //         ->withInput($this->except($this->dontFlash))
    //         ->withErrors($validator);

    //     throw new HttpResponseException($response);
    // }

    // /**
    //  * Get the URL to redirect the user to after a failed validation attempt.
    //  *
    //  * @return string
    //  */
    // protected function getRedirectUrl()
    // {
    //     return url()->previous();
    // }
}