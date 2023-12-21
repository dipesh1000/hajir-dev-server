<?php

namespace App\Http\Requests;

use App\Rules\EmployerCompanyExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CompanyStoreRequest extends FormRequest
{ 
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // dd(request()->name);

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
            'phone' => ['required'] ,
            'office_hour' => 'required',
        ];
    }




    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone is required',
            'office_hour.required' => 'Office hour is required',
            'phone.digits' => 'Phone must be of exact 10 digits',
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
