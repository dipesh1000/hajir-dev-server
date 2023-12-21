<?php

namespace Employer\Repositories\employer;

use Carbon\Carbon;
use Employer\Models\Company;
use Employer\Repositories\employer\EmployerInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class EmployerRepository implements EmployerInterface {

    protected $file =null;
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }


    public function store($request){
        $company = new Company();
        $company->email = $request->email;
        $company->contact = $request->contact;
        $company->address = $request->address;
        $company->working_days = $request->working_days;
        $company->office_hour_start = Carbon::parse($request->office_hour_start)->format('H:i');
        $company->office_hour_end = Carbon::parse($request->office_hour_start)->format('H:i');
        $company->employer_id = auth()->user()->id;
        if($company->save()){
            return $company;
        }
        throw new Exception("Something went wrong please try again later");
    }




}
