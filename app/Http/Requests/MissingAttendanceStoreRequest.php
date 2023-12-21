<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MissingAttendanceStoreRequest extends FormRequest
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
            'candidate_id' => 'required|integer',
            
            'start_time' => 'nullable|date_format:H:i|before:end_time',
            'employee_status' => 'required',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'attendance_date' =>'required',
        ];
    }

    public function messages()
    {
        return[
            'candidate_id.required' => "Please Select Candidate.",
            'company_id.required' => "Company Not Found.",
            'employee_status.required' => "Please Select Attendance Status.",
            'attendance_date.required' => "Please Enter Attendance Date.",
            'start_time.after' => "Attendace Start Time Must Start Before End Time.",
            'end_time.before' => "Attendace End Time Must Start After Start Time.",
            'start_time.date_format' => "Invalid Start Time Format.",
            'end_time.date_format' => "Invalid End Time Format.",
        ];
    }


}
