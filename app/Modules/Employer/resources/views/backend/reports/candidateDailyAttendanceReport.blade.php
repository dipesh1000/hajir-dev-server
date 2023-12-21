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

        .reportActionBtn .active {
            background-color: green;
            color: white
        }

        .dot {
            height: 13px;
            width: 13px;
            border-radius: 50%;
            display: inline-block;
        }

        .breakSection p:last-child {
            font-weight: 700;
            color: #ff0202;
            font-size: 18px;
        }



        .circle-wrap {
            float: left;
            margin: 2rem;
            width: 150px;
            height: 150px;
            background: #f8e0e0;
            border-radius: 50%;
        }

        .circle-wrap h3 {
            font-weight: 700;
            font-size: 1.4rem;
            color: #ff0202;
        }

        .circle-wrap h4 {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .circle-wrap .circle .mask,
        .circle-wrap .circle .fill {
            width: 150px;
            height: 150px;
            position: absolute;
            border-radius: 50%;
            transform: rote(50deg);

        }

        .circle-wrap .circle .mask {
            clip: rect(0px, 150px, 150px, 74px);
        }

        .circle-wrap .circle .mask .fill {
            clip: rect(0px, 75px, 150px, 0px);
            background-color: #008537;
        }

        .circle-wrap .inside-circle {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: absolute;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: #fff;
            margin-top: 10px;
            margin-left: 10px;
            z-index: 1;
            font-weight: 700;
            font-size: 2em;
            text-align: center;
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
                    <div class="">
                        <h6>{{$candidate->candidate->firstname}}<span>&nbsp;[&nbsp;{{ $candidate->code}}&nbsp;]</span></h6>
                    </div>
                </div>
                <hr class="m-0">
                <div class="reportActionBtn mt-2 text-center">
                    <a href="{{ route('employer.company.candidateDailyAttendanceReport', [$candidate->company_id, $candidate->candidate_id]) }}"
                        class="btn active">Daily</a>
                    <a href="{{ route('employer.company.candidateWeeklyAttendanceReport', [$candidate->company_id, $candidate->candidate_id]) }}"
                        class="btn">Weekly</a>
                    <a href="{{ route('employer.company.candidateMonthlyAttendanceReport', [$candidate->company_id, $candidate->candidate_id]) }}"
                        class="btn">Monthly</a>
                    <a href="{{ route('employer.company.candidateYearlyAttendanceReport', [$candidate->company_id, $candidate->candidate_id]) }}"
                        class="btn">Yearly</a>
                </div>
                <div class="reportActionBtn mt-4 d-flex justify-content-center justify-items-center">
                    @if ($status == 'late' || $status == 'present')
                        @if ($status != 'present')
                            <div>
                                <span class="dot bg-warning mr-2"></span>
                                <span class="text-warning">Late</span>
                            </div>
                        @endif
                        <div class="mr-4">
                            <span class="dot bg-success mr-2"></span>
                            <span class="text-success">Present</span>
                        </div>
                    @elseif ($status == 'absent')
                        <div class="mr-4">
                            <span class="dot bg-danger mr-2"></span>
                            <span class="text-danger">Absent</span>
                        </div>
                    @endif
                </div>

                <hr class="m-0 my-2">

                <div class="mt-4 d-flex justify-content-center justify-items-center">
                    
                    @foreach ($weekly_datas as $data => $value)
                        <div class="mr-4">
                            @php
                                $data = Carbon\Carbon::parse($data);
                            @endphp
                            <a href="{{ route('employer.company.candidateDailyAttendanceReport',[$candidate->company_id, $candidate->candidate_id]).'?date='.$data->format('Y-m-d')}}" 
                                class="@if ($value == 'Absent') text-danger
                                        @elseif ($value == 'Present') text-success
                                        @elseif ($value == 'Late') text-warning
                                        @elseif ($value == 'Leave') text-info
                                        @else text-secondary @endif">{{ $data->format('l') }}
                                <p class="text-center @if ($value == 'Absent') text-danger
                                            @elseif ($value == 'Present') text-success
                                            @elseif ($value == 'Late') text-warning
                                            @elseif ($value == 'Leave') text-info
                                            @else text-secondary @endif" >
                                    {{ $data->format('d') }}</p>
                            </a>
                            
                        </div>
                    @endforeach


                </div>

                <div class="d-flex justify-content-around">

                    <p>{{ $start_time ?? '' }}<i class="fas fa-long-arrow-alt-down text-success"></i></p>
                    <p>{{ $end_time ?? '' }} <i class="fas fa-long-arrow-alt-up text-danger"></i></p>

                </div>

                {{-- @dd($weekly_datas) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-center">
                            <div class="breakSection my-auto text-right">
                                <p class="mb-0">Break</p>
                                <p>{{ $break_time ?? '' }}</p>
                            </div>
                            <div class="workSection ml-4"> 
                                <div class="circle-wrap firstPercentage">
                                    <div class="circle">
                                        <div class="mask full">
                                            <div class="fill"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill"></div>
                                        </div>
                                        <div class="inside-circle">
                                            <span class="count d-none">{{ $attendance_duration_percentage ?? 0 }}</span>
                                            <h4>Total Hours</h4>
                                            <h3>{{ $attendance_duration ?? '00:00:00' }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <p>Total Earning: <span>{{ $earning ?? 0 }}/-</span></p>
                        </div>
                    </div>
                    <div class="col-md-6 px-5" style="border-left: #e3e3e3  1px solid">
                        <h6>Notify</h6>
                        <form
                            action="{{ route('employer.company.notificationMessageSent', [$candidate->company_id, $candidate->candidate_id]) }}"
                            method="POST">
                            @csrf
                            <label>Send Message</label>
                            <textarea class="form-control" rows="5" name="message" placeholder="Message">{{ old('message') }}</textarea>
                            <button class="btn btn-primary mt-4">Send</button>
                        </form>

                    </div>
                </div>
                {{-- <hr class="m-0">
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
                                @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status', 'verified'))
                                    @foreach ($companyCandidates as $companyCandidate)
                                        <div class="col-md-4">
                                            <div class="card component-card_8">
                                                <div class="card-body">
                                                    <div class="progress-order">
                                                        <div class="progress-order-header">
                                                            <div class="row">
                                                                <div class="col-md-8 col-sm-8 col-12">
                                                                    <h6>{{ $companyCandidate->candidate->firstname }}</h6>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>
                                                                            {{ isset($companyCandidate->companyCandidateAttendaces) ? $companyCandidate->companyCandidateAttendaces->start_time : '' }}
                                                                            <i
                                                                                class="fas fa-long-arrow-alt-down text-success"></i>
                                                                        </span>
                                                                        <span>
                                                                            {{ isset($companyCandidate->companyCandidateAttendaces) ? $companyCandidate->companyCandidateAttendaces->end_time : '' }}
                                                                            <i
                                                                                class="fas fa-long-arrow-alt-up text-danger"></i></span>
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
                                    @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status', 'verified') && $companyCandidate->activecompanyCandidateAttendaces->exists())
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

                            <div class="tab-pane fade" id="justify-pills-inactive" role="tabpanel"
                                aria-labelledby="pills-inactive-tab">
                                <p class="dropcap  dc-outline-primary">
                                    @if (isset($companyCandidates) && count($companyCandidates) > 0 && $companyCandidates->where('status', 'verified') && $companyCandidate->activecompanyCandidateAttendaces->doesNotExists())

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




                </div> --}}
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <script src="https://unpkg.com/jquerykeyframes@0.1.0/dist/jquery.keyframes.min.js"></script>

    <script>
        var value = $('.firstPercentage').find(".count").text();
        var supportedFlag = $.keyframe.isSupported();
        var finalValue = 1.8 * value + "deg";
        if (finalValue < 1) {
            $('.circle-wrap').find('.fill').css('background-color: #fff;');
        }
        $.keyframe.define([{
            name: 'firstPercentage',
            '0%': {
                transform: 'rotate(' + 1 + 'deg)'
            },
            '100%': {
                transform: 'rotate(' + finalValue + ')'
            }
        }]);
        $('.firstPercentage').find($(".mask.full")).css({
            "animation": "firstPercentage ease-in-out 3.5s forwards"
        });
        $('.firstPercentage').find($(".fill")).css({
            "animation": "firstPercentage ease-in-out 3.5s forwards"
        });
    </script>
@endpush
