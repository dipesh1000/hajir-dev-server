<legend>Employee Details</legend>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="name">Full Name <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="name" placeholder="First Name..." name="name"
                value="{{ $companyCandidate->candidate->name ?? old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
   
    <div class="col-md-3">
        <div class="form-group">
            <label for="designation">Designation<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="designation" placeholder="Designation..." name="designation"
                value="{{ $companyCandidate->designation ?? old('designation') }}" required>
            @error('designation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="contact">Mobile Number<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="contact" placeholder="Mobile Number..." name="contact"
                value="{{ $companyCandidate->candidate->phone ?? old('contact') }}" required>
            @error('contact')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="confirm_contact">Confirm Mobile Number <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="confirm_contact" placeholder="Confirm Mobile Number..."
                name="confirm_contact" min=10 value="{{ $companyCandidate->candidate->phone ?? old('confirm_contact') }}" required
              >
            @error('confirm_contact')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="text" class="form-control" id="email" placeholder="Email Address..." name="email"
                value="{{ $companyCandidate->candidate->email ?? old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="dob">Date Of Birth</label>
            <input type="text" class="form-control" placeholder="Date Of Birth..." name="dob"  id="dateTimeFlatpickr"   max="{{ date('Y-m-d')}}"
                value="{{ (isset($companyCandidate) && $companyCandidate->candidate->dob != null) ? $companyCandidate->candidate->dob->format('Y-m-d') : old('dob',date('Y-m-d')) }}">
            @error('dob')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<hr>
<legend>Employee Company Details</legend>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            
            <label for="code">Staff Code<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="code" placeholder="Staff Code..." name="code"
                value="{{ $companyCandidate->code ?? (isset($code) && $code != null ? $code : '') ?? old('code') }}" required
                {{ ((isset($company) && $company->code) || (isset($companyCandidate) && $companyCandidate->code)) ? 'readonly' : ''}}>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="salary_type">Salary Type<span class="text-danger"> *</span></label>
            <select name="salary_type" class="form-control" id="salary_type">
                <option disabled selected>Choose One...</option>
                <option value="daily" {{ ((isset($companyCandidate) && $companyCandidate->salary_type) || old('salay_type')) == "daily" ? 'selected' : ''}}>Daily</option>
                <option value="weekly" {{ ((isset($companyCandidate) && $companyCandidate->salary_type) || old('salay_type')) == "weekly" ? 'selected' : ''}}>Weekly</option>
                <option value="monthly" {{ ((isset($companyCandidate) && $companyCandidate->salary_type) || old('salay_type')) == "monthly" ? 'selected' : ''}}>Monthly</option>
            </select>

            @error('salary_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="salary_amount">Salary Amount<span class="text-danger"> *</span></label>
            <input type="number" class="form-control" id="salary_amount" placeholder="Salary Amount..."
                name="salary_amount" value="{{ $companyCandidate->salary_amount ?? old('salary_amount') }}" required>
            @error('salary_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="joining_date">Joining Date<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" placeholder="Joining..."  id="dateTimeFlatpickr2" 
                name="joining_date" value="{{(isset($companyCandidate) && $companyCandidate->joining_date != null) ? $companyCandidate->joining_date->format('Y-m-d') : old('joining_date',date('Y-m-d')) }}" required>
            @error('joining_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <label for="duty_time">Duty Time<span class="text-danger"> *</span></label>
            <div class="d-flex">
                <input type="time" class="form-control" id="duty_time" placeholder="eg,10:00" name="duty_time" 
                    value="{{isset($companyCandidate) ? date('H:i', strtotime($companyCandidate->duty_time)) : (old('duty_time') ?? '10:00') }}" required>
            </div>
            @error('place_of_issue')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="working_hours">Candidate Working Hours<span class="text-danger"> *</span></label>
            <div class="container d-flex">
                {{-- <button data-decrease class="btn btn-primary" type="button"><span class="icon">-</span></button> --}}
                <input type="string" class="form-control" id="working_hours" name="working_hours" placeholder="eg: 8:00, 8:30" pattern="[0-9]{1}:[0-9]{2}"
                    value="{{ $companyCandidate->working_hours ?? old('working_hours') ?? "8:00" }}" required>
                {{-- <button data-increase class="btn btn-primary" type="button">+</button> --}}
            </div>

            @error('working_hours')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="over_time">Overtime [Ration]</label>
            <div class="d-flex">
               
                <input type="number" min="0" step=".5" class="form-control ml-2" id="overtime" name="over_time"
                    placeholder="eg, 1, 1.5, or 2,..."  value="{{ $companyCandidate->overtime ?? old('over_time') }}">
                @error('over_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="allow_late_attendance">Allow Late Attendance</label>
            <div class="d-flex">
                {{-- <div class="input-group-text">
                    <input type="checkbox" class="largerCheckbox"aria-label="Checkbox for following text input">
                </div> --}}
            
                <div class=" d-flex ml-2">
                    {{-- <button data-decrease class="btn btn-primary" type="button"><span
                            class="icon">-</span></button> --}}
                    <input data-value type="text" name="allow_late_attendance" value="{{ $companyCandidate->allow_late_attendance ?? old('allow_late_attendance') ?? 1}}" class="form-control" />
                    {{-- <button data-increase class="btn btn-primary" type="button">+</button> --}}
                </div>
                {{-- <input type="text" class="form-control" id="allow_late_attendance" placeholder="Pan No..."
                name="allow_late_attendance"
                value="{{ $companyCandidate->allow_late_attendance ?? old('allow_late_attendance') }}" required> --}}
                @error('allow_late_attendance')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="allowance_amount">Allowance</label>
            <div class="d-flex">
                <select class="form-control mr-2 ml-2" name="allowance_type">
                    <option value="daily" {{ ((isset($companyCandidate) && $companyCandidate->allowance_type) || old('allowance_type')) == "daily" ? 'selected' : ''}}>Daily</option>
                    <option value="weekly" {{ ((isset($companyCandidate) && $companyCandidate->allowance_type) || old('allowance_type')) == "weekly" ? 'selected' : ''}}>Weekly</option>
                    <option value="monthly" {{ ((isset($companyCandidate) && $companyCandidate->allowance_type) || old('allowance_type')) == "monthly" ? 'selected' : ''}}>Monthly</option>
                    <option value="yearly" {{ ((isset($companyCandidate) && $companyCandidate->allowance_type) || old('allowance_type')) == "yearly" ? 'selected' : ''}}>Yearly</option>
                </select>
                <input type="number" step="any" class="form-control" id="allowance_amount" placeholder="eg.5000"
                    name="allowance_amount" value="{{ $companyCandidate->allowance_amount ?? old('allowance_amount') }}"
                    >
            </div>
            @error('allowance_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            {{-- allowance_type --}}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="casual_leave">All Casual Leave</label>
            <div class="d-flex">
               
                <select class="form-control mr-2 ml-2" name="casual_leave_type">
                    <option value="weekly" {{ ((isset($companyCandidate) && $companyCandidate->casual_leave_type) || old('casual_leave_type')) == "weekly" ? 'selected' : ''}}>Weekly</option>
                    <option value="monthly" {{ ((isset($companyCandidate) && $companyCandidate->casual_leave_type) || old('casual_leave_type')) == "monthly" ? 'selected' : ''}}>Monthly</option>
                    <option value="yearly" {{ ((isset($companyCandidate) && $companyCandidate->casual_leave_type) || old('casual_leave_type')) == "yearly" ? 'selected' : ''}}>Yearly</option>
                </select>
                <input type="number" step="any" class="form-control" id="casual_leave" placeholder="eg.1..."
                    name="casual_leave" value="{{ $companyCandidate->casual_leave ?? old('casual_leave') }}">
                @error('casual_leave')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                {{-- casual_leave_type --}}
            </div>


        </div>
    </div>

</div>
