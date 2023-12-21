<?php

namespace App\Rules;

use App\GlobalServices\ResponseService;
use App\Models\User;
use Employer\Models\Company;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class CompanyCandidateExistsRule implements Rule
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
        // dd(request()->companyid);
        if(request()->companyid){
            $company = Company::where('id', request()->companyid)->first();
            if($company){
                $user = User::where('email', request()->email)->orWhere('phone', request()->contact)->first();
                if($user){
                    if($user->companyCandidate()->where('company_id', $company->id)->exists()){
                        return false;
                    }
                    return true;
                }
                return true;
            }
            return response([
                'status' => 'error',
                'code' => 404,
                'message' => "Company Not Found."
            ]);
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Either phone or email exists.';
    }
}
