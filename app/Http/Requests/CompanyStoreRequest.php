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
            'name' => 'required|string|max:255|unique:companies,name',
            'phone' => ['required'] ,
            'address' => ['required','string','max:255'] ,

            'office_hour' => 'required',
            // 'email' => 'required|email',
            'code' =>'required',

            // 'office_hour => 'required',
            // 'calculation_type' => 'required',
            // 'network_ip' => 'required', 
    
            // "leave_duration_type" => 'required',
            // "leave_duration" => 'required',
            // 'probation_period' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.unique' => 'Company Name already exists.',
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
