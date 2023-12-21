<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="name">Company Name <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="name" placeholder="Company Name..." name="name"
                value="{{ $company->name ?? old('name') }}" required>
                @error('company_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>

    {{-- <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Company Phone No. <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="phone" placeholder="Client Name..." phone="phone"
                value="{{ $client->phone ?? old('phone') }}" required>
                @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div> --}}
    <div class="col-md-4">
        <div class="form-group">
            <label for="code">Staff Code <span class="text-danger"> *</span></label><br>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="code" name="code" class="custom-control-input" value="1" {{ ($company->code??old('code')) == 1 ? 'checked' : ''}}>
                <label class="custom-control-label" for="code" >Auto Generated</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="code2" name="code" class="custom-control-input" value="0" {{ ($company->code??old('code')) == 0 ? 'checked' : ''}}>
                <label class="custom-control-label" for="code2">Custom</label>
            </div>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
   

    {{-- <div class="col-md-4">
        <div class="form-group">
            <label for="grand_father_name">Date Selection<span class="text-danger"> *</span></label>
            <br>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                <label class="custom-control-label" for="customRadioInline1">English</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
                <label class="custom-control-label" for="customRadioInline2">Nepali</label>
            </div>
        
            @error('grand_father_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div> --}}

    <div class="col-md-4">
        <div class="form-group">
            <label for="grand_father_name">Salary Calculation Day<span class="text-danger"> *</span></label><br>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="calculation_type" name="calculation_type" class="custom-control-input"  
                value="calendar_days" {{ ($company->code|| old('calculation_type')) == "calendar_days" ? 'checked' : ''}}>
                <label class="custom-control-label" for="calculation_type">Calendar Days</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="calculation_type2" name="calculation_type" class="custom-control-input" 
                value="30_days" {{ ($company->code || old('calculation_type')) == "30_days" ? 'checked' : ''}}>
                <label class="custom-control-label" for="calculation_type2" >30 Days</label>
            </div>
            @error('calculation_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">

            <label for="business_leave">Business Leave Days<span class="text-danger"> *</span></label><br>            
            <div class="n-chk">
                @if (isset($businessLeaves) && count($businessLeaves)>0)
                    @foreach ($businessLeaves as $businessLeave)
                        <label class="new-control new-checkbox checkbox-primary mr-4">
                            <input type="checkbox" name="business_leave[]" class="new-control-input" value="{{ $businessLeave->id }}"
                            @if (isset($company) && isset($company->businessLeaves))
                                    @foreach ($company->businessLeaves as $companyLeave)
                                        @if ($companyLeave->id == $businessLeave->id)
                                            checked
                                        @endif
                                    @endforeach
                                @endif>
                            <span class="new-control-indicator"></span>{{ $businessLeave->title }}
                        </label>
                    @endforeach
                @endif
            </div>
            @error('grand_father_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="government_leavedates">Government Holiday<span class="text-danger"> *</span></label><br>
            <input type="text" name="government_leavedates" class="form-control" id="dateTimeFlatpickr"  placeholder="Government Holidays..."
                value="@if(isset($company) && isset($company->govLeaves))@foreach ($company->govLeaves as $leave){{$leave->leave_date}},@endforeach @endif">
            @error('government_leavedates')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="special_leavedates">Offical Holiday<span class="text-danger"> *</span></label><br>
            <input type="text" name="special_leavedates" class="form-control " id="dateTimeFlatpickr2" placeholder="Offical Holidays..."
                value="@if(isset($company) && isset($company->specialLeaves))
                            @foreach ($company->specialLeaves as $leave)
                               
                                    {{$leave->leave_date}}
                            @endforeach
                        @endif">
            @error('special_leavedates')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

  

    <div class="col-md-4">
        <label for="leave_duration_type">Sick Leave Allowed<span class="text-danger"> *</span></label><br>
        <div class="input-group mb-4">
            <select class="form-control mr-2" name="leave_duration_type">
                <option selected disabled>Choose Sick Leave Allowed...</option>
                <option {{ (isset($company) && $company->leave_duration_type) || old('leave_duration_type') == 'Weekly' ? 'selected' : ''}}>Weekly</option>
                <option {{ (isset($company) && $company->leave_duration_type) || old('leave_duration_type') == 'Monthly' ? 'selected' : ''}}>Monthly</option>
                <option {{ (isset($company) && $company->leave_duration_type) || old('leave_duration_type') == 'Yearly' ? 'selected' : ''}}>Yearly</option>
            </select>
            <div class="input-group-prepend" style="width:25%;">
                <div class="">
                    <label class="">
                        <input class="form-control" type="number" min=0  name="leave_duration" value="{{ $company->leave_duration ?? old('leave_duration') }}">
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label for="probation_period">Probation Period (Month)<span class="text-danger"> *</span></label><br>
            <select class="form-control mr-2" name="probation_period">
                <option selected disabled>Choose Probation Period...</option>
                <option {{ (isset($company) && $company->probation_period) || old('probation_period') == 2 ? 'selected' : ''}}>2</option>
                <option {{ (isset($company) && $company->probation_period) || old('probation_period')  == 3 ? 'selected' : ''}}>3</option>
            </select>
            @error('probation_period')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="office_hour">Office Working Hours<span class="text-danger"> *</span></label><br>
            <input class="form-control" name="office_hour" id="office_hour" value="{{ $company->office_hour ?? old('office_hour')}}">
            @error('office_hour')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
   
</div>



