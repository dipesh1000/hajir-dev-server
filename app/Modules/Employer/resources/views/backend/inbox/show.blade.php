@extends('layouts.employer.master')

@section('title', '| View Companies')

@section('breadcrumb', 'View Companies')

@push('styles')
    <style>
        .data-items {
            border-right: solid 2px rgb(207, 201, 201);
            border-bottom: solid 2px rgb(207, 201, 201);

            border-bottom-right-radius: 6px;
            /* border-bottom-left-radius: 6px; */
            line-height: 1;
            /* text-align: center; */
            padding-left: 10px;

        }

        .data-items h6 {
            font-weight: 600;

        }

        label {
            color: #585a64 !important;
        }
    </style>
@endpush

@section('content')
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-6">
                <div class="col-12">
                    <h5 style="display: inline;"><span class="font-weight-bolder">Inbox Details</h5>
                    {{-- <a class="btn btn-secondary float-right " href="{{ route('receptionist.client.index')}}">Previous Page</a> --}}
                </div>
                <hr class="mb-0">
                <div class="col-xl-12 col-md-12 col-sm-12 mt-2">
                    <h5 class="font-weight-bolder">{{ $leave->candidate->firstname . ' ' . $leave->candidate->lastname }}</h5>
                    <p>{{ $leave->created_at->format('Y-m-d') }}</p>
                </div>

                <hr class="m-0">
                <div class="col-xl-12 col-md-12 col-sm-12 mt-2">
                    {!! $leave->remarks !!}
                </div>
                <hr class="m-2">
                <div class="col-xl-12 col-md-12 col-sm-12 mt-2">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Leave</label>
                                    <h6 class="font-weight-bolder">{{ $leave->LeaveType->title }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <label>Type</label>
                                    <h6 class="font-weight-bolder">{{ $leave->type }}</h6>

                                </div>

                                <div class="col-md-6 mt-2">
                                    <label>Duration From</label>
                                    <h6 class="font-weight-bolder">{{ $leave->start_date }}</h6>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label>Duration Till</label>
                                    <h6 class="font-weight-bolder">{{ $leave->end_date }}</h6>

                                </div>

                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12 ">
                                    <form>
                                        <div class="col-md-6">
                                            <select class="form-control" name="leave_paid_status">
                                                <option>Paid</option>
                                                <option>Unpaid</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8 mt-2">
                                            <button class="btn btn-danger">Rejected</button>
                                            <button class="btn btn-success">Approved</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label>Attached</label><br>
                            <img src="{{ getOrginalUrl($leave->document_id) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
