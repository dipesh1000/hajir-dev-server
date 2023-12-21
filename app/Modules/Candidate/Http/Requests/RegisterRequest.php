<?php

namespace Candidate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'phone' => ['required', 'unique:users', 'numeric', 'digits:10', 'regex:/^((98)|(97)|(96))[0-9]{8}/'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Please Enter Phone No',
            'phone.unique' => 'Phone No Already In Use. Please Use New Phone No.',
            'phone.numeric' => 'Phone No. must only be in numbers.',
            'phone.digits' => 'Phone No. must contain 10 digits.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));
    }
}
