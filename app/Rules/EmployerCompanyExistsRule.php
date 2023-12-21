<?php

namespace App\Rules;

use Employer\Models\Company;
use Illuminate\Contracts\Validation\Rule;

class EmployerCompanyExistsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $company = Company::where($attribute, $value)->where('employer_id', auth()->user()->id)->exists();
        if($company == true){
            return false;
        }
        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Company already exists.';
    }
}
