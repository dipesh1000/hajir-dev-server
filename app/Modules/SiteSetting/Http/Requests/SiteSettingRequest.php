<?php

namespace SiteSetting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
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
            'title' => 'required',
            'primary_phone' => 'required',
            'primary_email' => 'required',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please Enter Title',
            'primary_phone.required' => 'Please Enter Primary Phone No.',
            'primary_email.required' => 'Please Enter Primary Email Address',
            'address.required' => 'Please Enter Address',
        ];
    }
}
