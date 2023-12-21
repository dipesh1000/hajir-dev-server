<?php

namespace SuperAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveTypeStoreRequest extends FormRequest
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
            'title' => 'required|max:255',
            'status' => 'required|in:Active,Inactive'

        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Please Enter Title.',
            'status.required' => 'Please Enter Status.',
        ];
    }
}
