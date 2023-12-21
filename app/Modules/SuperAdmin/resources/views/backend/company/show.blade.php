@extends('layouts.admin.master')

@section('title', '| View Companies')

@section('breadcrumb', 'View Companies')

@push('styles')
    <style>
        .data-items{
            border-right: solid 2px rgb(207, 201, 201);
            border-bottom: solid 2px rgb(207, 201, 201);
          
            border-bottom-right-radius: 6px;
            /* border-bottom-left-radius: 6px; */
            line-height:1;
            /* text-align: center; */
            padding-left: 10px;

        }
        .data-items h6{
            font-weight: 600;
            
        }
        label{
            color: #585a64 !important;
        }
        
    </style>
    
@endpush

@section('content')
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="col-12">
                        <h5 style="display: inline;"><span class="font-weight-bolder text-uppercase">{{ $company->name ?? 'N/A'}}</span> - Details</h5>
                        {{-- <a class="btn btn-secondary float-right " href="{{ route('receptionist.client.index')}}">Previous Page</a> --}}
                    </div>
                    <hr>
                    <div class="col-xl-12 col-md-12 col-sm-12 mt-4">
                       
                        <div class="row ">
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="name">Company Name </label>
                                    <h6 style="font-weight:600;">{{ $company->name ?? 'N/A'}}</h6>
                                </div>
                            </div> 
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="code">Company Code</label>
                                    <h6>{{ $company->code ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="email">Company Email</label>
                                    <h6>{{ $company->email ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="address">Company Address</label>
                                    <h6>{{ $company->address ?? 'N/A'}}</h6>
                                      
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="phone">Phone No.</label>
                                        <h6>{{ $company->phone ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="status">Employer Name</label>
                                    @if ($company->employer != null)
                                        <a href = "{{route('backend.employer.show',$company->employer->id)}}"> <h6><span class="badge badge-primary">{{Str::ucfirst($company->employer->name)}}</span></h6></a>
                                    @else
                                    <h6><span class="text-primary">N/A</span></h6></a>

                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="status">Status</label>
                                        <h6><span class="badge {{  $company->status == "Active" ? 'badge-success' : 'bagde-danger'}}">{{ $company->status}}</span></h6>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="working_days">Working Days</label>
                                        <h6>{{ $company->working_days ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="office_hour_start">Office Hour Start</label>
                                        <h6>{{ $company->office_hour_start ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="office_hour_end">Office Hour End</label>
                                        <h6>{{ $company->office_hour_end ?? 'N/A'}}</h6>

                                </div>
                            </div>
                        
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="calculation_type ">Calculation Type </label>
                                        <h6>{{ $company->calculation_type  ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="form-group data-items">
                                    <label for="salary_type">Salary Type</label>
                                        <h6>{{ $company->salary_type ?? 'N/A'}}</h6>

                                </div>
                            </div>
                           
                        </div> 
                        <hr>
                        <div class="col-xl-12 col-lg-12 col-sm-12">
                       
                            <div class="col-12 mt-2">
                                <h5 style="display: inline;">Companies Candidate Table</h5>
                                <a class="btn btn-success float-right " href="{{ url()->previous() }}">Previous</a>
            
                            </div>
                            <div class="table-responsive mb-2 mt-3">
                                <table id="global-table" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">S.no.</th>
                                            <th width="15%">Name</th>
                                            <th width="15%">Email</th>
                                            <th width="15%">Phone No.</th>
                                            <th width="15%">Address</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        @if(isset($company) && $company->candidates != null && $company->candidates->count() > 0)
                                        @foreach ($company->candidates as $candidate)
                                            <tr>   
                                                {{-- @dd($candidate->pivot) --}}
                                                <td>{{ $loop->iteration}} 
                                                <td>{{ $candidate->name ?? 'N/A'}}</td>
                                             
                                                <td>{{ $candidate->email ?? 'N/A'}}</td> 
                                                {{-- <td><span class="badge badge-{{ $candidate->status=="Active" ? "success" : 'danger' }}">{{ $candidate->status ?? 'N/A'}}</span></td> --}}
                                                <td>{{ $candidate->phone ?? 'N/A'}}</td>
                                                <td>{{ $candidate->address ?? 'N/A'}}</td>
                                              
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                      
                    </div>
                    

                        {{-- <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h5>Owner Details</h5>
                                    <hr style="margin: 0;">
                                </div>
                            </div>
                            <hr style="margin: 0;">
                        </div> --}}
                        {{-- @if (isset($client) && $client->owner != null)
                        <div class="row">
                        
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_name">Owner Name </label>
                                        <h6>{{ $client->owner->owner_name ?? 'N/A'}}</h6>

                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="mohi_name">Mohi Name</label>
                                   
                                        <h6>{{ $client->owner->mohi_name ?? 'N/A'}}</h6>

                                </div>
                            </div> 
                            <div class="col-md-3">
                              <div class="form-group data-items">
                                    <label for="owner_father_name">Father Name</label>
                                        <h6>{{ $client->owner->father_name ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_grand_father_name">Grand Father Name</label>
                                        <h6>{{ $client->owner->grand_father_name ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_husband_name">Husband Name</label>
                                        <h6>{{ $client->owner->husband_name ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_father_in_law_name">Father in Law's Name</label>
                                        <h6>{{ $client->owner->father_in_law_name ?? 'N/A'}}</h6>

                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_spouse_name">Spouse Name</label>
                                        <h6>{{ $client->owner->spouse_name ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_address">Address</label>
                                        <h6>{{ $client->owner->address ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_district">District</label>
                                        <h6>{{ $client->owner->district ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_citizenship_no">Citizenship No.</label>
                                        <h6>{{ $client->owner->citizenship_no ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_date_of_issue">Date Of Issue(BS)(Citizenship)</label>
                                    <h6>{{$client->owner->date_of_issue != null ? $client->owner->date_of_issue->format('Y/d/m') : 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_place_of_issue">Place Of Issue(Citizenship)</label>
                                        <h6>{{ $client->owner->place_of_issue ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_contact_no">Contact No.</label>
                                        <h6>{{ $client->owner->contact_no ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_reg_no">Reg. No.</label>
                                        <h6>{{ $client->owner->reg_no ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_pan_no">Pan No.</label>
                                    
                                        <h6>{{ $client->owner->pan_no  ?? 'N/A'}}</h6>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_pan_date_of_issue">Date Of Issue(PAN No.)</label>
                                    <h6>{{$client->owner->pan_date_of_issue != null ? $client->owner->pan_date_of_issue->format('Y/d/m') : 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_pan_place_of_issue">Place Of Issue(PAN No.)</label>
                                    <h6>{{ $client->owner->pan_place_of_issue ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="owner_share_holders">Share Holders</label>
                                    <h6>{{ $client->owner->share_holders ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="relation">Relation</label>
                                    <h6>{{ $client->owner->relation ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="proposed_owner_name">Proposed Owner Name</label>
                                    <h6>{{ $client->owner->proposed_owner_name ?? 'N/A'}}</h6>
                                </div>
                            </div>
                        </div> 
                        @else
                            <h5>Sorry. No Data Avaliable.</h5>
                        @endif --}}
                    </div>
                    
                </div>
            </div>
        </div>
    
 @endsection
 @push('scripts')


     
 @endpush

