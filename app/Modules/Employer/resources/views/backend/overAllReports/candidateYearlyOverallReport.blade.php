@extends('layouts.employer.master')

@section('title', '| Yearly Overall Report')

@section('breadcrumb', 'Yearly Overall Report')

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
            flex-direction: row;
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
            align-items: center;
        }
    </style>
@endpush

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="widget-content widget-content-area br-4">
            <div class="col-12">
                <h5 style="display: inline;">Yearly Overall Report</h5>
                <a class="btn btn-secondary float-right " href="{{ route('employer.company.index')}}">Previous Page</a>

            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div class="">
                    <h6>{{ $companyCandidate->company->name}}</h6>
                </div>
            </div>
            <hr class="m-0">
            <div class="reportActionBtn mt-2 text-center">
                <a href="{{ route('employer.company.dailyOverAllReport',$companyCandidate->company_id)}}" class="btn">Daily</a>
                <a href="{{ route('employer.company.weeklyOverAllReport',$companyCandidate->company_id)}}" class="btn">Weekly</a>
                <a href="{{ route('employer.company.monthlyOverAllReport',$companyCandidate->company_id)}}" class="btn">Monthly</a>
                <a href="{{ route('employer.company.yearlyOverAllReport',$companyCandidate->company_id)}}" class="btn active">Yearly</a>
            </div>
           
            <hr class="m-0 my-2">   
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card card-body" style="background: rgb(227, 255, 228);">
                        <div class="d-flex">
                            <h6 class="m-0 ml-4 pr-4 text-danger font-weight-bolder" style="border-right:solid #cacaca 2px">
                                {{ $totalattendee ?? 0}}
                            </h6>
                            <h6 class="m-0 ml-4 font-weight-bolder">Attendee</h6>
                        </div>
                    </div>
                </div>
                {{-- 'presentCount','presentPercentage','companyCandidate' --}}
                <div class="col-md-4">
                    <div class="card card-body" style="background: rgb(234, 216, 217)">
                        <div class="d-flex">
                            <h6 class="m-0 ml-4 pr-4 text-danger font-weight-bolder" style="border-right:solid #cacaca 2px">
                                {{ $absentCount ?? 0}}
                            </h6>
                            <h6 class="m-0 ml-4 font-weight-bolder">Absent</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-body" style="background: rgb(244, 244, 244)">
                        <div class="d-flex">
                            <h6 class="m-0 ml-4 pr-4 text-danger font-weight-bolder" style="border-right:solid #cacaca 2px">
                                {{ $lateCount ??0}}
                            </h6>
                            <h6 class="m-0 ml-4 font-weight-bolder">Late</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="card card-body" style="background: rgb(225, 232, 253)">
                        <div class="d-flex">
                            <h6 class="m-0 ml-4 pr-4 text-danger font-weight-bolder" style="border-right:solid #cacaca 2px">
                                {{ $punchOutCount ?? 0 }}
                            </h6>
                            <h6 class="m-0 ml-4 font-weight-bolder">Early Punch Out</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <div class="card card-body" style="background: rgb(240, 240, 223)">
                        <div class="d-flex">
                            <h6 class="m-0 ml-4 pr-4 text-danger font-weight-bolder" style="border-right:solid #cacaca 2px">
                                {{ $leaveCount ?? 0}}
                            </h6>
                            <h6 class="m-0 ml-4 font-weight-bolder">Sick Leave Taken</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="circle-wrap firstPercentage">
                    <div class="circle">
                        <div class="mask full">
                            <div class="fill"></div>
                        </div>
                        <div class="mask half">
                            <div class="fill"></div>
                        </div>
                        <div class="inside-circle">
                            <span class="count">{{ $presentPercentage ?? 0 }}</span>
                            <span>%</span>
                           
                        </div>
                    </div>
                </div>
                  <h4>Attendance Records</h4>
            </div>  
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
