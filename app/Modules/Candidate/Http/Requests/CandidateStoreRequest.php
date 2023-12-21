<?php

namespace Candidate\Http\Requests;

use App\Rules\CompanyCandidateExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CandidateStoreRequest extends FormRequest
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
            'contact' =>  ['required', new CompanyCandidateExistsRule],
            'email' => [new CompanyCandidateExistsRule],
            'salary_type' => 'required',
            'duty_time' => 'required',
            'code' => 'required|unique:company_candidates,code',
            'salary_amount' => 'required',
            'joining_date' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Name is required",
            "contact.required" => "Contact is required",
            "contact.unique" => "This number has already been taken. Please try another one",
            "email.required" => "Email is required",
            "email.unique" => "This email has already been taken. Please try another one",
            "duty_time.required" => "Duty time is required",
            "code.required" => "Code is required",
            "salary_amount.required" => "Salary amount is required",
            "joining_date.required" => "Joining date is required is required",

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
