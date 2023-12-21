<?php

namespace Employer\Http\Resources;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
            "allowance_type" => 'required|in:Daily,Weekly',
            'paid_amount' => 'required',
            'status' => 'required'
        ];
    }
}
