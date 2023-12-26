<?php

namespace Candidate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required',
            // 'lastname' => 'required',
            'email' => 'required|email',
            'dob' => 'required',
            'uploadfile' => 'image|mimes:jpeg,jpg,png,gif|max:2048'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'First Name is required',
            // 'lastname.required' => "Last Name is requried",
            'email.required' => "Email is required",
            'email.email' => "Email must be an valid email",
            'dob.required' => "Date of birth is required",

        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 403));
    }
}
