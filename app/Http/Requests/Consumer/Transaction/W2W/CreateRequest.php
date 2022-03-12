<?php

namespace App\Http\Requests\Consumer\Transaction\W2W;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $recipient_id
 * @property-read int $currency_id
 * @property-read $volume
 */
class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'volume' => ['required']
        ];
    }
}
