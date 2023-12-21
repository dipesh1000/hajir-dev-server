@extends('layouts.employer.master')

@section('title', '| Enroll Attendee')

@section('breadcrumb', 'Enroll Attendee')

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

    a.active{
        color: #fff !important;
        background-color: #2196f3;
    }
</style>
@endpush

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="widget-content widget-content-area br-4">
            <div class="col-12">
                <h5 style="display: inline;">Enroll Attendee</h5>
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
                                    <h3 class="text-danger font-weight-bold text-center">{{ $presentCount ?? 0 }}</h3>
                                    <h5 class="font-weight-bold text-center">Present</h5>
                                </div>
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
            <div class="d-flex justify-content-center">
                <a href="{{ route('employer.approver.company.candidate.allEnroll',$company->slug).'?status=Clock-In'}}" class="btn btn-outline-info {{ $activeStatus=="Clock-In"?'active':''}}">Clock In</a>
                <a href="{{ route('employer.approver.company.candidate.allEnroll',$company->slug).'?status=Clock-Out'}}" class="btn btn-outline-info ml-2 {{ $activeStatus=="Clock-Out"?'active':''}}">Clock Out</a>

            </div>
           
            <hr class="my-2">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="justify-pills-all" role="tabpanel"
                            aria-labelledby="pills-all-tab">
                            @if (isset($companyCandidates) && count($companyCandidates) > 0)
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
                                                                        @if (isset($companyCandidate->companyCandidateAttendaces))
                                                                            {{Carbon\Carbon::parse($companyCandidate->companyCandidateAttendaces->start_time)->format('g:i A')}}
                                                                            <i class="fas fa-long-arrow-alt-down text-success"></i>
                                                                        @endif 
                                                                        
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 pl-0 col-sm-4 col-12 text-right">
                                                                <button class="btn btn-outline-dark reportCandidate" data-href="{{ route('employer.approver.company.candidate.candidateReport',[$company->id,$companyCandidate->candidate_id,$companyCandidate->companyCandidateAttendaces->id]) }}">Report</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h5 class="text-center">No Data Avaliable.</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


  
  <!-- Modal -->
  <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalTitle" aria-hidden="true">
   
  </div>


        
@endsection

@push('scripts')
<script>
    $(document).on('click','.reportCandidate',function(e){
        e.preventDefault();
        var route = $(this).data('href');
        $.ajax({
            type: 'GET',
            url: route,
            beforeSend: function(data) {
                // loader();
            },
            success: function(data) {
                $('#reportModal').modal('show');
        
                $("#reportModal").html(data);
            },
        });
     
    });

    
    $(document).on('submit', '#submit-form', function(e) {
    e.preventDefault();
    var currentevent = $(this);
    currentevent.attr('disabled');
    var form = new FormData($('#submit-form')[0]);
    var params = $('#submit-form').serializeArray();
    var route = $(this).attr('action');

    $.each(params, function(i, val) {
        form.append(val.name, val.value)
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: route,
        contentType: false,
        processData: false,
        data: form,
        beforeSend: function(data) {
            loader();
        },
        success: function(data) {
            toastr.success(data.message);
            $('#submit-form').trigger("reset");
            $('#reportModal').modal('hide');
            currentevent.attr('disabled', false);

        },
        error: function(err) {
            if (err.status == 422) {
                $.each(err.responseJSON.errors, function(i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span style="color: red;">' + error[0] + '</span>')
                        .fadeOut(4000));
                });
            }

            currentevent.attr('disabled', false);
        },complete:function(){

        }
    });

});
</script>

@endpush
