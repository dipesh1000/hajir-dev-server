@extends('layouts.employer.master')

@section('title', '| Weekly Attendance')

@section('breadcrumb', 'Weekly Attendance')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('backendfiles/assets/css/apps/notes.css') }}">
    <link rel="stylesheet" href="{{ asset('backendfiles/assets/css/components/tabs-accordian/custom-tabs.css') }}">


    <style>
        a.active{
            color: rgb(13, 195, 13)
        }
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
    </style>
@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-4">
                <div class="col-12">
                    <h5 style="display: inline;">Weekly Attendance</h5>
                    <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="ml-3">
                        <h6>{{$candidate->candidate->name}}<span>&nbsp;[&nbsp;{{ $candidate->code}}&nbsp;]</span></h6>
                    </div>
                </div>
                {{-- <hr class="m-0"> --}}
                <div class="reportActionBtn mt-2 text-center">
                    <a href="{{ route('employer.company.candidateDailyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn ">Daily</a>
                    <a href="{{ route('employer.company.candidateWeeklyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn active">Weekly</a>
                    <a href="{{ route('employer.company.candidateMonthlyAttendanceReport',[$candidate->company_id,$candidate->candidate_id])}}" class="btn">Monthly</a>
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
                        <span class="text-danger">Leave [{{ $LeaveCount ?? 0}}]</span>
                    </div>
                    <div>
                        <span class="dot bg-warning mr-2"></span>
                        <span class="text-warning">Late [{{ $lateCount ?? 0}}]</span>
                    </div>
                </div>

                <hr class="m-0 my-2">
                <div class="mt-4 d-flex justify-content-center justify-items-center">
                    @foreach ($getWeeks as $index =>$value)
                        <a href="{{ route('employer.company.candidateWeeklyAttendanceReport',[$candidate->company_id,$candidate->candidate_id]).'?from='.$value['start'].'&to='.$value['end']}}
                                " class="mr-4 {{ $index == $weekNumberInMonth ?'active':''}}">WEEK {{ $index}}</a>
                    @endforeach
                </div>
                
                <div class="mt-4 d-flex justify-content-center justify-items-center">
                    @foreach ($weeklyDatas as $data=>$value)
                        <div class="mr-4">
                            @php
                                $data = Carbon\Carbon::parse($data);
                            @endphp
                            <p class="@if ($value == 'Absent') text-primary
                                        @elseif ($value == 'Present') text-success
                                        @elseif ($value == 'Late') text-warning
                                        @elseif ($value == 'Leave') text-danger
                                        
                                        @else text-secondary @endif">
                                        {{$data->format('l')}}</p>
                            <p class="text-center @if ($value == 'Absent') text-primary
                                        @elseif ($value == 'Present') text-success
                                        @elseif ($value == 'Late') text-warning
                                        @elseif ($value == 'Leave') text-danger
                                        
                                        @else text-secondary @endif">
                                        {{ $data->format('d')}}</p>
                        </div>
                    @endforeach
                   

                </div>

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
                                    <td class="text-right">{{ $currentWeekSalary ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td class="text-right">{{ $current_week_overtime ?? 0}}</td>    
                                </tr>

                                <tr>
                                    <td>Bonus</td>
                                    <td class="text-right">{{ $current_week_bonus ?? 0}}</td>    
                                </tr>
                                <tr>
                                    <td>Allowance</td>
                                    <td class="text-right">{{ $current_week_allowance ?? 0}}</td>    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Total</th>
                                <th class="text-right">
                                    {{ $currentWeekSalary+($current_week_overtime??0)+($current_week_bonus??0)+($current_week_allowance??0)}}
                                </th>
                                </tr>
                            </tfoot>

                        </table>
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
            </div>
        </div>
    </div>

@endsection
@push('scripts')

<script>
   
     
</script>
@endpush
