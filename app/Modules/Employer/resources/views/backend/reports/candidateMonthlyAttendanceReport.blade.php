@extends('layouts.employer.master')

@section('title', '| Monthly Attendance')

@section('breadcrumb', 'Monthly Attendance')

@push('styles')
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

        .slick-next::before, .slick-prev::before {
            color: black !important;
        }

        .month-title{
            margin-right: 10px;
            margin-left: 10px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
        }
        .activeMonth a{
            color: #8dbf42  !important;
        }
        

        .circle-wrap {
            float: left;
            margin: 2rem;
            width: 120px;
            height: 120px;
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
            width: 120px;
            height: 120px;
            position: absolute;
            border-radius: 50%;
            transform: rote(50deg);

        }

        .circle-wrap .circle .mask {
            clip: rect(0px, 120px, 120px, 60px);
        }

        .circle-wrap .circle .mask .fill {
            clip: rect(0px, 61px, 120px, 0px);
            background-color: #008537;
        }

        .circle-wrap .inside-circle {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: absolute;
            width: 100px;
            height: 100px;
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
                    <h5 style="display: inline;">Monthly Attendance</h5>
                    <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="ml-3">
                        <h6>{{$candidate->candidate->firstname}}<span>&nbsp;[&nbsp;{{ $candidate->code}}&nbsp;]</span></h6>
                    </div>
                </div>
                {{-- <hr class="m-0"> --}}
                <div class="reportActionBtn mt-2 text-center">
                    <a href="{{ route('employer.company.candidateDailyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn ">Daily</a>
                    <a href="{{ route('employer.company.candidateWeeklyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn ">Weekly</a>
                    <a href="{{ route('employer.company.candidateMonthlyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn active">Monthly</a>
                    <a href="{{ route('employer.company.candidateYearlyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn">Yearly</a>
                </div>
                <div class="reportActionBtn mt-4 d-flex justify-content-center justify-items-center">
                  
                    <div class="mr-4">
                        <span class="dot bg-success mr-2"></span>
                        <span class="text-success">Present [{{ $presentCount ?? 0}}]</span>
                    </div>
                    <div class="mr-4">
                        <span class="dot bg-primary mr-2"></span>
                        <span class="text-primary">Absent [{{ $absentCount ?? 0}}]</span>
                    </div>
                    <div class="mr-4">
                        <span class="dot bg-danger mr-2"></span>
                        <span class="text-danger">Leave [{{ $leaveCount ?? 0}}]</span>
                    </div>
                    <div>
                        <span class="dot bg-warning mr-2"></span>
                        <span class="text-warning">Late [{{ $lateCount ?? 0}}]</span>
                    </div>
                   
                </div>
                <div class="monthly-slider mt-4 pb-1 mx-4">
                    @if (isset($allMonths) && count($allMonths)>0)
                        @foreach ($allMonths as $month)
                        <div class="month-title {{ $activeMonth == $month ? 'activeMonth' : ''}}" data-month={{ $month }}>
                            <a href="{{ route('employer.company.candidateMonthlyAttendanceReport',[$candidate->company_id,$candidate->candidate_id]).'?month='.$month}}">
                                {{ $month }}</a>
                        </div>
                        @endforeach
                    @endif
                </div>
                <hr class="m-0 my-2">
                <div class="row mt-4">
                    <div class="col-md-6 px-5">
                        <table class="table">
                            <thead style="border-top:none;">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Salary</td>
                                    <td class="text-right">{{ $totalEarning ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td class="text-right">{{ $overtime ?? 0}}</td>    
                                </tr>

                                <tr>
                                    <td>Bonus</td>
                                    <td class="text-right">{{ $bonus ?? 0}}</td>    
                                </tr>
                                <tr>
                                    <td>Allowance</td>
                                    <td class="text-right">{{ $allowance ?? 0}}</td>    
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td class="text-right">{{ $tax ?? 0}}</td>    
                                </tr>
                                <tr>
                                    <td>Penalty</td>
                                    <td class="text-right">{{ $penalty ?? 0}}</td>    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Balance</th>
                                <th class="text-right">
                                    @php
                                        $grossTotalEarning = $totalEarning+($overtime??0)+($bonus??0)+($allowance??0);
                                        $netDeduction = ($tax ?? 0)+ ($penalty ?? 0);
                                        $netTotalEarning = $grossTotalEarning-$netDeduction;

                                    @endphp
                                    {{ $grossTotalEarning }} - {{ $netDeduction}} =  {{ $netTotalEarning}}
                                </th>
                                </tr>
                            </tfoot>

                        </table>
                        <table class="table">
                            <thead style="border-top:none;">
                                <tr>
                                    <th colspan="2">Sick Leave</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Avaliable</td>
                                    <td class="text-right">{{ $companyTotalAvailableSickLeave ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td>Total Used</td>
                                    <td class="text-right">{{$leaveTaken ?? 0 }}</td>    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Remaining</th>
                                <th class="text-right">
                                    {{ $totalSickDaysLeft ?? 0}}
                                </th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                    <div class="col-md-6 px-5" style="border-left: #e3e3e3  1px solid">
                        <div>
                            <h6>Leave Summary</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 leave-summary-circle">
                                   
                                    <div class="circle-wrap firstPercentage">
                                        <div class="circle">
                                            <div class="mask full">
                                                <div class="fill"></div>
                                            </div>
                                            <div class="mask half">
                                                <div class="fill"></div>
                                            </div>
                                            <div class="inside-circle">
                                                <span class="count d-none">{{ ($totalSickDaysLeft*100)/$companyTotalAvailableSickLeave ?? 0 }}</span>
                                                <h4 class="text-success">{{ $activeMonth ?? ''}}</h4>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <span class="dot bg-primary mr-2"></span>Total<br>
                                    <span class="dot bg-danger mr-2"></span>Taken<br>
                                    <span class="dot bg-success mr-2"></span>Remaining<br>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-primary mr-2">: {{ $companyTotalAvailableSickLeave}} days</span><br>
                                    <span class="text-danger mr-2">: {{$leaveTaken}} days</span><br>
                                    <span class="text-success mr-2">: {{$totalSickDaysLeft ?? 0}} days</span><br>
                                </div>
                            </div>
                        </div>
                        <div>
                            {{ $activeMonth ?? ''}}
                            <h6>Payments</h6>
                            <form method="POST" action="{{ route('employer.company.paymentSubmit',[$candidate->company_id,$candidate->candidate_id])}}">
                                @csrf
                                <div class="row">       
                                    <div class="col-md-4">
                                        <label>Mark As</label>
                                        <select class="form-control" name="status">
                                            <option selected>Paid</option>
                                        </select>
                                    </div>
                                        <input type="hidden" value="{{ $activeMonth ?? ''}}" name="payment_for_month">
                                        <input type="hidden" value="{{ $netTotalEarning ?? 0 }}" name="paid_amount">
                                    <div class="col-md-4">
                                        <label>Bonus</label>
                                        <input class="form-control" step="any" type="number" min="0" name="bonus" placeholder="Bonus.">
                                    </div>  
                                    <div class="col-md-4">
                                        <label>Deduction (Tax)</label>
                                        <input class="form-control" step="any" type="number" min="0" name="tax" placeholder="Deduction (Tax).">
                                    </div>  
                                    <div class="col-md-12 mt-2">
                                        <button class="btn btn-primary float-right"> Submit</button>
                                    </div>
                                </div>
                              
                              

                             
                            </form>
                        </div>
                        <div class="mt-4">
                            <h6>Notify</h6>
                            <form action="{{ route('employer.company.notificationMessageSent', [$candidate->company_id, $candidate->candidate_id]) }}"
                                method="POST">
                                @csrf
                                <label>Send Message</label>
                                <textarea class="form-control" rows="5" name="message" placeholder="Message">{{ old('message') }}</textarea>
                                <button class="btn btn-primary mt-4">Send</button>
                            </form>
                        </div>

                    </div>
                </div> 
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script src="https://unpkg.com/jquerykeyframes@0.1.0/dist/jquery.keyframes.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.monthly-slider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 12,
        slidesToScroll:4,
        swipeToSlide: true,
       
    });
</script>
    <script>
        var currentSlide = $('.month-title').removeClass('slick-current');
        var currentSlide = $('.month-title').removeClass('slick-active');

        $.each($('.month-title'), function() {
            $(this).removeClass('slick-current');
            $(this).removeClass('slick-active');
            $(this).attr('tabindex','-1');

                var data = $(this).data('month');
                if(data == "{{ $activeMonth}}"){
                    $(this).addClass('slick-current')
                    $(this).addClass('slick-active')
                    $(this).attr('tabindex','0');
                }
        });


    </script>


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
