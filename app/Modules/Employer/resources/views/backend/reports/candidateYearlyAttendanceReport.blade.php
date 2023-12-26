@extends('layouts.employer.master')

@section('title', '| Yearly Attendance')

@section('breadcrumb', 'Yearly Attendance')

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
        .activeYear{
            color: rgb(3, 209, 3);
            font-weight: 700;
        }
        .circle-wrap {
           
            width: 150px;
            height: 150px;
            background: #fefcff;
            border-radius: 50%;
            border: 1px solid #cdcbd0;
        }
        .circle-wrap .circle .mask,
.circle-wrap .circle .fill {
  width: 150px;
  height: 150px;
  position: absolute;
  border-radius: 50%;
}

.mask .fill {
  clip: rect(0px, 75px, 150px, 0px);
  background-color: #227ded;
}

.circle-wrap .circle .mask {
  clip: rect(0px, 150px, 150px, 75px);
}

.mask.full,
.circle .fill {
  animation: fill ease-in-out 3s;
  transform: rotate(135deg);
}

@keyframes fill{
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(135deg);
  }
}
.circle-wrap .inside-circle {
  width: 122px;
  height: 122px;
  border-radius: 50%;
  background: #d2eaf1;
  line-height: 120px;
  text-align: center;
  margin-top: 14px;
  margin-left: 14px;
  color: #1e51dc;
  position: absolute;
  z-index: 100;
  font-weight: 700;
  font-size: 2em;
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
                        <h6>{{$companycandidate->candidate->name}}<span>[ {{$companycandidate->code ?? ''}}]</span></h6>
                    </div>
                </div>
                <hr class="m-0">
                <div class="reportActionBtn mt-2 text-center">
                    <a href="{{ route('employer.company.candidateDailyAttendanceReport',[$companycandidate->company_id,$companycandidate->candidate_id])}}" class="btn ">Daily</a>
                    <a href="{{ route('employer.company.candidateWeeklyAttendanceReport',[$companycandidate->company_id,$companycandidate->candidate_id])}}" class="btn ">Weekly</a>
                    <a href="{{ route('employer.company.candidateMonthlyAttendanceReport',[$companycandidate->company_id,$companycandidate->candidate_id])}}" class="btn">Monthly</a>
                    <a href="{{ route('employer.company.candidateYearlyAttendanceReport',[$companycandidate->company_id,$companycandidate->candidate_id])}}" class="btn active">Yearly</a>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    @if (isset($years) && count($years) > 0)
                        @foreach ($years as $year)
                            <a href="{{ route('employer.company.candidateYearlyAttendanceReport',[$companycandidate->company_id,$companycandidate->candidate_id]).'?year='.$year}}" 
                                    class="mr-4 activeYear">{{ $year }}</a>
                        @endforeach
                    @endif
                </div>
                <div class="row mt-4">
                    <div class="col-3">
                    </div>
                    <div class="col-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th></th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>                            
                                @if (isset($monthlyPayments) && count($monthlyPayments))
                                    @php
                                        $totalMonthlyPayment = 0;
                                    @endphp
                                    @foreach ($monthlyPayments as $monthlyPayment)
                                        <tr>
                                            <td>{{$monthlyPayment['months']}}</td>
                                            <td class="text-right">Paid</td>
                                            <td class="text-right">{{$monthlyPayment['totalMonthlyPayment'] }}</td>
                                        </tr>   
                                    @endforeach
                                    @php
                                        $totalMonthlyPayment = $totalMonthlyPayment + $monthlyPayment['totalMonthlyPayment'];
                                    @endphp
                                @else
                                <tr>
                                    <td colspan="3" class="text-center">No Information Avaliable.</td>
                                </tr>
                                    
                                @endif
                            </tbody>
                            <tfoot>

                                <tr>
                                    <th colspan="2">Total Amount</th>
                                    <th class="text-right">{{ $totalMonthlyPayment ??0}}</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                    <div class="col-3">
                    </div>
                </div>


            
            </div>
        </div>

    </div>

@endsection
@push('scripts')

<script>
     let options = {
        startAngle: -1.55,
        size: 150,
        value: 0.85,
        fill: {gradient: ['#a445b2', '#fa4299']}
      }
      $(".circle .bar").circleProgress(options).on('circle-animation-progress',
      function(event, progress, stepValue){
        $(this).parent().find("span").text(String(stepValue.toFixed(2).substr(2)) + "%");
      });
     
</script>
@endpush
