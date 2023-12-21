<?php

namespace SuperAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageStoreRequest extends FormRequest
{
   
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
            'price' =>'required|numeric|min:0',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Please Enter Title.',
            'status.required' => 'Please Enter Status.',
            'price.required' => 'Please Enter Price.',
            'price.numeric' => 'Price Must Be Numberic Value.',
            'status.in' => 'Please Provide A Valid Option.'
        ];
    }
}
