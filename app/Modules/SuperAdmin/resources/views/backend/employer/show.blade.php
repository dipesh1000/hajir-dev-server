@extends('layouts.admin.master')

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
                        <h5 style="display: inline;">View Employer Details - <span class="font-weight-bolder">{{ $client->client_name ?? 'N/A'}}</span></h5>
                        <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>
                    </div>
                    <hr>
                    <div class="col-xl-12 col-md-12 col-sm-12">
                       
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="name"> Name </label>
                                    <h6 style="font-weight:600;">{{ $employer->name ?? 'N/A'}}</h6>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="email">Email</label>
                                    <h6>{{ $employer->email ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="address">Address</label>
                                    <h6>{{ $employer->address ?? 'N/A'}}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group data-items">
                                    <label for="phone">Phone No.</label>
                                    <h6>{{ $employer->phone ?? 'N/A'}}</h6>
                                      
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <hr>
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                       
                            <div class="col-12 mt-2">
                                <h5 style="display: inline;">Employer Companies Table</h5>
                                {{-- <a class="btn btn-success float-right " href="{{ route('receptionist.client.create')}}">Create</a> --}}
            
                            </div>
                            <div class="table-responsive mb-2 mt-3">
                                <table id="global-table" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">S.no.</th>
                                            <th width="15%">Name</th>
                                            <th width="10%">Code</th>
                                            <th width="15%">Email</th>
                                            <th width="5%">Status</th>
                                            <th width="10%">Phone No.</th>
                                            <th width="15%">Address</th>
                                            <th width="3%">Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        @if(isset($employer) && $employer->employerCompany != null && $employer->employerCompany->count() > 0)
                                        @foreach ($employer->employerCompany as $company)
                                            <tr>   
                                                <td>{{ $loop->iteration}} 
                                                <td>{{ $company->name ?? 'N/A'}}</td>
                                                <td>{{ $company->code ?? 'N/A'}}</td>
                                                <td>{{ $company->email ?? 'N/A'}}</td>
                                                <td><span class="badge badge-{{ $company->status=="Active" ? "success" : 'danger' }}">{{ $company->status ?? 'N/A'}}</span></td>
                                                <td>{{ $company->phone ?? 'N/A'}}</td>
                                                <td>{{ $company->address ?? 'N/A'}}</td>
                                                <td><a href="{{ route('backend.company.show',$company->slug) }}" class="btn btn-primary">View</a></td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                      
                    </div>
                    
                </div>
            </div>
        </div>
    
 @endsection
 @push('scripts')


     
 @endpush

