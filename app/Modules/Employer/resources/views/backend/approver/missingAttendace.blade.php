@extends('layouts.employer.master')

@section('title', '| Missing Attendance')

@section('breadcrumb', 'Missing Attendance')

@push('styles')
@endpush

@section('content')
    
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-4">
                <div class="col-12">
                    <h5 style="display: inline;">Missing Attendance</h5>
                    <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>

                </div>
                <hr>
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <form action="{{ route('employer.approver.company.candidate.missingAttenanceSubmit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $company->id}}" name="company_id">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Select Candidate  <span class="text-danger">*</span></label>
                                <select class="form-control" name="candidate_id" required>
                                    @if(isset($company) && isset($company->candidates) && $company->candidates->count()>0)
                                        @foreach ($company->candidates as $candidate)
                                            <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ?'selected':'' }}>Sarad</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('candidate_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Select Type <span class="text-danger">*</span></label>
                                <select class="form-control employeeStatus" name="employee_status" required>
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Leave">Leave</option>
                                    <option value="Late">Late</option>
                                </select >
                                @error('employee_status')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Attendance Date  <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="attendance_date" required>
                                @error('attendance_date')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                                
                            </div>
                            <div class="col-md-4">
                                <label>Check In Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control startTime" name="start_time" >
                                @error('start_time')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            
                            </div>
                            <div class="col-md-4">
                                <label>Check Out Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control endTime" name="end_time" >
                                @error('end_time')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            
                            </div>
                            <div class="col-md-4">
                                <label>Overtime</label>
                                <input type="time" class="form-control" name="overtime">
                                @error('overtime')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary float-right mt-2">Submit</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')



<script>

    $(document).on('change','.employeeStatus',function(e){
        e.preventDefault();
        var currentVal = $(this).val()
        if(currentVal == "Present" || currentVal == "Late"){
            var endTimeEl = $(this).parent('div').siblings('div').children('.endTime');
            var startTimeEl = $(this).parent('div').siblings('div').children('.startTime');
            endTimeEl.siblings('label').children('span').removeClass('d-none');
            startTimeEl.siblings('label').children('span').removeClass('d-none');
            endTimeEl.prop('required',true);
            startTimeEl.prop('required',true);
        }else{
            var endTimeEl = $(this).parent('div').siblings('div').children('.endTime');
            var startTimeEl = $(this).parent('div').siblings('div').children('.startTime');
            endTimeEl.siblings('label').children('span').addClass('d-none');
            startTimeEl.siblings('label').children('span').addClass('d-none');
            endTimeEl.prop('required',false);
            startTimeEl.prop('required',false);
        }


    })

</script>

@endpush
