@extends('layouts.employer.master')

@section('title', '| Daily Attendance')

@section('breadcrumb', 'Daily Attendance')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('backendfiles/assets/css/apps/notes.css') }}">
    <link rel="stylesheet" href="{{ asset('backendfiles/assets/css/components/tabs-accordian/custom-tabs.css') }}">


    <style>
        .note-container.note-grid .note-item {
            max-width: 12% !important;
        }

        .note-container.note-grid .note-item .note-inner-content {
            min-height: 70px !important;
        }

        #content::before {
            content: none !important;
        }

        .col-md-12 .nav-pills {
            background: aliceblue;
            border-radius: 10px;
        }

        .col-md-12 .nav-pills .nav-item {
            border-right: solid white 4px;
        }
    </style>
@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-4">
                <div class="col-12">
                    <h5 style="display: inline;">Daily Attendance</h5>
                    <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>

                </div>
                <hr>
                <div class="d-flex justify-content-between">
                   <h5>{{ $company->name??''}}</h5>
                   <h6>{{date('d, M Y')}}</h6>
                </div>
                <div class="row">
                    <div id="ct" class="note-container note-grid text-center">
                        <div class="note-item all-notes note-personal">
                            <div class="note-inner-content">
                                <div class="note-content">
                                    <h3 class="text-danger font-weight-bold text-center">{{ $totalattendee ?? 0 }}</h3>
                                    <h5 class="font-weight-bold text-center">Attendee</h5>
                                </div>
                            </div>
                        </div>
                        <div class="note-item all-notes note-important">
                            <div class="note-inner-content">
                                <div class="note-content">
                                    <div class="note-content">
                                        <h3 class="text-danger font-weight-bold text-center">{{ $absentCount ?? 0 }}</h3>
                                        <h5 class="font-weight-bold text-center">Absent</h5>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="note-item all-notes note-work">
                            <div class="note-inner-content">
                                <div class="note-content">
                                    <h3 class="text-danger font-weight-bold text-center">{{ $lateCount ?? 0 }}</h3>
                                    <h5 class="font-weight-bold text-center">Late</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="m-0">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-pills mb-3 mt-3 nav-fill" id="justify-pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="justify-pills-all-tab" data-toggle="pill"
                                    href="#justify-pills-all" role="tab" aria-controls="justify-pills-home"
                                    aria-selected="true">All Candidates</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="justify-pills-active-tab" data-toggle="pill"
                                    href="#justify-pills-active" role="tab" aria-controls="justify-pills-profile"
                                    aria-selected="false">Active Candidates</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="justify-pills-inactive-tab" data-toggle="pill"
                                    href="#justify-pills-inactive" role="tab" aria-controls="justify-pills-contact"
                                    aria-selected="false">Inactive Candidates</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="justify-pills-all" role="tabpanel"
                                aria-labelledby="pills-all-tab">
                                @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status','active'))
                                    @foreach ($companyCandidates as $companyCandidate)
                                        <div class="col-md-4">
                                            <div class="card component-card_8">
                                                <div class="card-body">
                                                    <div class="progress-order">
                                                        <div class="progress-order-header">
                                                            <div class="row">
                                                                <div class="col-md-8 col-sm-8 col-12">
                                                                    <h6>{{ (isset($companyCandidate->candidate) && $companyCandidate->candidate != null) ? $companyCandidate->candidate->firstname : '' }}</h6>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>
                                                                            @if (isset($companyCandidate->companyCandidateAttendaces))
                                                                                {{$companyCandidate->companyCandidateAttendaces->start_time}}
                                                                                <i class="fas fa-long-arrow-alt-down text-success"></i>
                                                                            @endif 
                                                                        </span>
                                                                        <span>
                                                                            @if (isset($companyCandidate->companyCandidateAttendaces))
                                                                                {{$companyCandidate->companyCandidateAttendaces->end_time}}
                                                                                <i class="fas fa-long-arrow-alt-up text-danger"></i>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 pl-0 col-sm-4 col-12 text-right">
                                                                    @if (isset($companyCandidate->companyCandidateAttendaces))
                                                                        <span class="badge badge-info">Present</span>
                                                                    @else
                                                                        <span class="badge badge-info">Absent</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h5 class="text-center">No Attendance Today.</h5>
                                @endif

                            </div>

                            <div class="tab-pane fade" id="justify-pills-active" role="tabpanel"
                                aria-labelledby="pills-active-tab">
                                <p class="dropcap  dc-outline-primary">
                                    @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status','active') && ($companyCandidate->activecompanyCandidateAttendaces) != null)
                                        @foreach ($companyCandidates as $companyCandidate)
                                            <div class="col-md-4">
                                                <div class="card component-card_8">
                                                    <div class="card-body">
                                                        <div class="progress-order">
                                                            <div class="progress-order-header">
                                                                <div class="row">
                                                                    <div class="col-md-8 col-sm-8 col-12">
                                                                        <h6>{{ isset($companyCandidate->candidate) ? $companyCandidate->candidate->firstname : '' }}
                                                                        </h6>
                                                                        <div class="d-flex justify-content-between">
                                                                            <span>
                                                                                {{ isset($companyCandidate->activecompanyCandidateAttendaces) ? $companyCandidate->activecompanyCandidateAttendaces->start_time : '' }}
                                                                                <i
                                                                                    class="fas fa-long-arrow-alt-down text-success"></i>
                                                                            </span>
                                                                            <span>
                                                                                {{ isset($companyCandidate->activecompanyCandidateAttendaces) ? $companyCandidate->activecompanyCandidateAttendaces->end_time : '' }}
                                                                                <i
                                                                                    class="fas fa-long-arrow-alt-up text-danger"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pl-0 col-sm-4 col-12 text-right">
                                                                        @if (isset($companyCandidate->activecompanyCandidateAttendaces))
                                                                            <span class="badge badge-info">Present</span>
                                                                        @else
                                                                            <span class="badge badge-info">Absent</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h5 class="text-center">No Attendance Today.</h5>
                                    @endif
                                </p>
                            </div>

                            <div class="tab-pane fade" id="justify-pills-inactive" role="tabpanel"
                                aria-labelledby="pills-inactive-tab">
                                <p class="dropcap  dc-outline-primary">
                                @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status','verified') && $companyCandidate->activecompanyCandidateAttendaces == null )

                                    @foreach ($companyCandidates as $companyCandidate)
                                        <div class="col-md-4">
                                            <div class="card component-card_8">
                                                <div class="card-body">
                                                    <div class="progress-order">
                                                        <div class="progress-order-header">
                                                            <div class="row">
                                                                <div class="col-md-8 col-sm-8 col-12">
                                                                    <h6>{{ $companyCandidate->candidate->firstname }}
                                                                    </h6>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>
                                                                            {{ isset($companyCandidate->activecompanyCandidateAttendaces) ? $companyCandidate->activecompanyCandidateAttendaces->start_time : '' }}
                                                                            <i
                                                                                class="fas fa-long-arrow-alt-down text-success"></i>
                                                                        </span>
                                                                        <span>
                                                                            {{ isset($companyCandidate->activecompanyCandidateAttendaces) ? $companyCandidate->activecompanyCandidateAttendaces->end_time : '' }}
                                                                            <i
                                                                                class="fas fa-long-arrow-alt-up text-danger"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 pl-0 col-sm-4 col-12 text-right">
                                                                    @if (isset($companyCandidate->activecompanyCandidateAttendaces))
                                                                        <span class="badge badge-info">Present</span>
                                                                    @else
                                                                        <span class="badge badge-info">Absent</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h5 class="text-center">No Attendance Today.</h5>
                                @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
