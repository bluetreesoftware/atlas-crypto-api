<?php

namespace App\Http\Requests\Consumer\Wallet;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read $currency_id
 */
class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_id' => ['required', 'integer', 'exists:currencies,id']
        ];
    }
}
