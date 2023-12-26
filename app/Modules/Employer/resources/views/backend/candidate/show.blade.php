@extends('layouts.employer.master')


@section('title', '| View Employer')

@section('breadcrumb', 'View Employer')

@push('styles')
    <style>
        .data-items{
            border-right: solid 2px rgb(207, 201, 201);
            border-bottom: solid 2px rgb(207, 201, 201);
            border-bottom-right-radius: 6px;
            line-height:1;
            padding-left: 10px;
        }
        .data-items h6{
            font-weight: 600;
        }
        
    </style>
    
@endpush

@section('content')
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="col-12">
                        <h5 style="display: inline;">Details of {{$candidate->candidate->name ?? ''}}</h5>
                        <a class="btn btn-secondary float-right " href="{{ route('employer.company.viewEmployees',$candidate->company->slug) }}">Previous Page</a>
                    </div>
                    <hr>
                    <div class="col-xl-12 col-md-12 col-sm-12">
                       
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="name"> Name </label>
                                   
                                    <h6 style="font-weight:600;"> {{$candidate->candidate->name ?? ''}}</h6>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="email">Email</label>
                                    <h6>{{ $candidate->candidate->email ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="address">Address</label>
                                    <h6>{{ $candidate->candidate->address ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Phone No.</label>
                                    <h6>{{ $candidate->candidate->phone ?? 'N/A'}}</h6>
                                      
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Date Of Birth</label>
                                    <h6>{{ $candidate->candidate->dob->format('Y-m-d') ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Code</label>
                                    <h6>{{ $candidate->code ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Verification Status</label>
                                    <h6>{{ ucFirst($candidate->verified_status)}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Joining Date</label>
                                    <h6>{{ $candidate->joining_date->format('Y-m-d') ?? ''}}</h6>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Duty Time</label>
                                    <h6>{{ $candidate->duty_time ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Working Hours</label>
                                    <h6>{{ $candidate->working_hours ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Office Hour Start</label>
                                    <h6>{{ $candidate->office_hour_start ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Office Hour End</label>
                                    <h6>{{ $candidate->office_hour_end ?? ''}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Salary Type</label>
                                    <h6> {{ ucFirst($candidate->salary_type)?? ''}}</h6>
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Salary</label>
                                    <h6>NPR {{ $candidate->salary_amount ?? '0.00'}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Over Time Ratio</label>
                                    <h6>{{ $candidate->overtime ?? '0'}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Allow Late Attendance</label>
                                    <h6>{{ $candidate->allow_late_attendance ?? '0'}}</h6>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Allowance Amount</label>
                                    <h6>NPR {{ $candidate->allowance_amount ?? '0'}} </h6>
                                </div>
                            </div>


                            {{-- "allowance_amount" => 500.0
                            "allowance_type" => "yearly"
                            "casual_leave" => 2
                            "casual_leave_type" => "yearly"
                            @dd($candidate) --}}
                            

                            
                        </div>
                    </div>
                    <hr>                    
                </div>
            </div>
        </div>
    
 @endsection
 @push('scripts')


     
 @endpush

