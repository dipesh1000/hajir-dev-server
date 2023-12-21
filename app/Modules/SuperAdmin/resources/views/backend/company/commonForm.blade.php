<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="client_name">Client Name <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="client_name" placeholder="Client Name..." name="client_name"
                value="{{ $client->client_name ?? old('client_name') }}" required>
                @error('client_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="father_name">Father Name<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="father_name" placeholder="Father Name..." name="father_name"
                value="{{ $client->father_name ?? old('father_name') }}" required>
                @error('father_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="grand_father_name">Grand Father Name<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="grand_father_name" placeholder="Grand Father Name..." name="grand_father_name"
                value="{{ $client->grand_father_name ?? old('grand_father_name') }}" required>
                @error('grand_father_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="husband_name">Husband Name</label>
            <input type="text" class="form-control" id="husband_name" placeholder="Husband Name..." name="husband_name"
                value="{{ $client->husband_name ?? old('husband_name') }}">
                @error('husband_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="father_in_law_name">Father in Law's Name</label>
            <input type="text" class="form-control" id="father_in_law_name" placeholder="Father in Law's Name..." name="father_in_law_name"
                value="{{ $client->father_in_law_name ?? old('father_in_law_name') }}">
                @error('father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="spouse_name">Spouse Name</label>
            <input type="text" class="form-control" id="spouse_name" placeholder="Spouse Name..." name="spouse_name"
                value="{{ $client->spouse_name ?? old('spouse_name') }}">
                @error('spouse_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="address">Address<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="address" placeholder="Address..." name="address"
                value="{{ $client->address ?? old('address') }}" required>
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="district">District<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="district" placeholder="District..." name="district"
                value="{{ $client->district ?? old('district') }}" required>
                @error('district')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="citizenship_no">Citizenship No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="citizenship_no" placeholder="Citizenship No..." name="citizenship_no"
                value="{{ $client->citizenship_no ?? old('citizenship_no') }}" required>
                @error('citizenship_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="date_of_issue">Date Of Issue(BS)(Citizenship)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="date_of_issue" placeholder="Date Of Issue(BS)(Citizenship)..." name="date_of_issue"
                value="{{ $client->date_of_issue ?? old('date_of_issue') }}" required>
                @error('date_of_issue')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="place_of_issue">Place Of Issue(Citizenship)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="place_of_issue" placeholder="Place Of Issue(Citizenship)..." name="place_of_issue"
                value="{{ $client->place_of_issue ?? old('place_of_issue') }}" required>
                @error('place_of_issue')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="contact_no">Contact No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="contact_no" placeholder="Contact No..." name="contact_no"
                value="{{ $client->contact_no ?? old('contact_no') }}" required>
                @error('contact_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="reg_no">Reg. No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="reg_no" placeholder="Reg. No..." name="reg_no"
                value="{{ $client->reg_no ?? old('reg_no') }}" required>
                @error('reg_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pan_no">Pan No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="pan_no" placeholder="Pan No..." name="pan_no"
                value="{{ $client->pan_no ?? old('pan_no')}}" required>
                @error('pan_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pan_date_of_issue">Date Of Issue(PAN No.)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="pan_date_of_issue" placeholder="Date Of Issue(PAN No)..." name="pan_date_of_issue"
                value="{{ $client->pan_date_of_issue ?? old('pan_date_of_issue') }}" required>
                @error('pan_date_of_issue')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pan_place_of_issue">Place Of Issue(PAN No.)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="pan_place_of_issue" placeholder="Place Of Issue(PAN No)..." name="pan_place_of_issue"
                value="{{ $client->pan_place_of_issue ?? old('pan_place_of_issue') }}" required>
                @error('pan_place_of_issue')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="share_holders">Share Holders</label>
            <input type="text" class="form-control" id="share_holders" placeholder="Contact No..." name="share_holders"
                value="{{ $client->share_holders ?? old('share_holders') }}">
        </div>
    </div>
</div>
{{-- @dd($client, $client->owner) --}}
<hr style="margin: 0px;">
<div class="row mt-2">
    <div class="col-md-3">
    <div class="form-group">

        <h5>Owner Details</h5>
        <input type="checkbox" class="sameClient" @if (isset($client) && !is_null($client->owner) )
        @if ( $client->citizenship_no == $client->owner->citizenship_no )
        checked
        @endif
        @endif >
        <span class="text-danger pl-2">Owner And Client Are Same</span>
     </div>
    </div>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_name">Owner Name <span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_name" placeholder="Owner Name..." name="owner_name"
                value="{{ $client->owner->owner_name ?? old('owner_name') }}" required>
                @error('father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="mohi_name">Mohi Name</label>
            <input type="text" class="form-control" id="mohi_name" placeholder="Mohi Name..." name="mohi_name"
                value="{{ $client->owner->mohi_name ?? old('mohi_name') }}">
                @error('father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
            <label for="owner_father_name">Father Name<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_father_name" placeholder="Father Name..." name="owner_father_name"
                value="{{ $client->owner->father_name ?? old('owner_father_name') }}" required>
                @error('father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_grand_father_name">Grand Father Name<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_grand_father_name" placeholder="Grand Father Name..." name="owner_grand_father_name"
                value="{{ $client->owner->grand_father_name ?? old('owner_grand_father_name') }}" required>
                @error('owner_grand_father_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_husband_name">Husband Name</label>
            <input type="text" class="form-control" id="owner_husband_name" placeholder="Husband Name..." name="owner_husband_name"
                value="{{ $client->owner->husband_name ?? old('owner_husband_name') }}">
                @error('owner_husband_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_father_in_law_name">Father in Law's Name</label>
            <input type="text" class="form-control" id="owner_father_in_law_name" placeholder="Father in Law's Name..." name="owner_father_in_law_name"
                value="{{ $client->owner->father_in_law_name ?? old('owner_father_in_law_name') }}">
                @error('owner_father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_spouse_name">Spouse Name</label>
            <input type="text" class="form-control" id="owner_spouse_name" placeholder="Spouse Name..." name="owner_spouse_name"
                value="{{ $client->owner->spouse_name ?? old('owner_spouse_name') }}">
                @error('owner_spouse_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_address">Address<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_address" placeholder="Address..." name="owner_address"
                value="{{ $client->owner->address ?? old('owner_address') }}" required>
                @error('father_in_law_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_district">District<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_district" placeholder="District..." name="owner_district"
                value="{{ $client->owner->district ?? old('owner_district') }}" required>
                @error('owner_district')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_citizenship_no">Citizenship No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_citizenship_no" placeholder="Citizenship No..." name="owner_citizenship_no"
                value="{{ $client->owner->citizenship_no ?? old('owner_citizenship_no') }}" required>
                @error('owner_citizenship_no')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_date_of_issue">Date Of Issue(BS)(Citizenship)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_date_of_issue" placeholder="Date Of Issue(BS)(Citizenship)..." name="owner_date_of_issue"
                value="{{ $client->owner->date_of_issue ?? old('owner_date_of_issue') }}" required>
                @error('owner_date_of_issue')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_place_of_issue">Place Of Issue(Citizenship)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_place_of_issue" placeholder="Place Of Issue(Citizenship)..." name="owner_place_of_issue"
                value="{{ $client->owner->place_of_issue ?? old('owner_place_of_issue') }}" required>
                @error('owner_place_of_issue')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_contact_no">Contact No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_contact_no" placeholder="Contact No..." name="owner_contact_no"
                value="{{ $client->owner->contact_no ?? old('owner_contact_no') }}" required>
                @error('owner_contact_no')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_reg_no">Reg. No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_reg_no" placeholder="Reg. No..." name="owner_reg_no"
                value="{{ $client->owner->reg_no ?? old('owner_reg_no') }}" required>
                @error('owner_reg_no')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_pan_no">Pan No.<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_pan_no" placeholder="Pan No..." name="owner_pan_no"
                value="{{ $client->owner->pan_no ?? old('owner_pan_no') }}" required>
                @error('owner_pan_no')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_pan_date_of_issue">Date Of Issue(PAN No.)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_pan_date_of_issue" placeholder="Date Of Issue(PAN No)..." name="owner_pan_date_of_issue"
                value="{{ $client->owner->pan_date_of_issue ?? old('owner_pan_date_of_issue') }}" required>
                @error('owner_pan_date_of_issue')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_pan_place_of_issue">Place Of Issue(PAN No.)<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="owner_pan_place_of_issue" placeholder="Place Of Issue(PAN No)..." name="owner_pan_place_of_issue"
                value="{{ $client->owner->pan_place_of_issue ?? old('owner_pan_place_of_issue') }}" required>
                @error('owner_pan_place_of_issue')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="owner_share_holders">Share Holders</label>
            <input type="text" class="form-control" id="owner_share_holders" placeholder="Contact No..." name="owner_share_holders"
                value="{{ $client->owner->share_holders ?? old('owner_share_holders') }}">
                @error('owner_share_holders')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="relation">Relation<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="relation" placeholder="Relation..." name="relation"
                value="{{ $client->owner->relation ?? old('relation') }}" required>
                @error('relation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="proposed_owner_name">Proposed Owner Name</label>
            <input type="text" class="form-control" id="proposed_owner_name" placeholder="Contact No..." name="proposed_owner_name"
                value="{{ $client->owner->proposed_owner_name ?? old('proposed_owner_name') }}">
                @error('proposed_owner_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>


