@extends('layouts.reception.master')

@section('title', 'NDC | Add New Client')

@section('breadcrumb', 'Add New Client')

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-4">
                <div class="col-12">
                    <h5 style="display: inline;">Add New Client</h5>
                    <a class="btn btn-secondary float-right " href="{{ route('receptionist.client.index')}}">Previous Page</a>

                </div>
                <hr>
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <form action="{{ route('receptionist.client.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('Receptionist::client.commonForm')
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
    if($(".sameClient").checked){
        cloneClientDataToOwnerInput();
    }else{
        removeClonedInputDataFromOwnerInput();
    }

    $(".sameClient").change(function() {
        if(this.checked) {
            cloneClientDataToOwnerInput();
        }else{
            removeClonedInputDataFromOwnerInput();
        }
    });



    function cloneClientDataToOwnerInput(){
        $("[name='owner_name']").attr('readonly', true).val($("[name='client_name']").val());
        $("[name='owner_father_name']").attr('readonly', true).val($("[name='father_name']").val());
        $("[name='owner_grand_father_name']").attr('readonly', true).val($("[name='grand_father_name']").val());
        $("[name='owner_husband_name']").attr('readonly', true).val($("[name='husband_name']").val());
        $("[name='owner_father_in_law_name']").attr('readonly', true).val($("[name='father_in_law_name']").val());
        $("[name='owner_spouse_name']").attr('readonly', true).val($("[name='spouse_name']").val());
        $("[name='owner_address']").attr('readonly', true).val($("[name='address']").val());
        $("[name='owner_district']").attr('readonly', true).val($("[name='district']").val());
        $("[name='owner_citizenship_no']").attr('readonly', true).val($("[name='citizenship_no']").val());
        $("[name='owner_date_of_issue']").attr('readonly', true).val($("[name='date_of_issue']").val());
        $("[name='owner_place_of_issue']").attr('readonly', true).val($("[name='place_of_issue']").val());
        $("[name='owner_contact_no']").attr('readonly', true).val($("[name='contact_no']").val());
        $("[name='owner_reg_no']").attr('readonly', true).val($("[name='reg_no']").val());
        $("[name='owner_pan_no']").attr('readonly', true).val($("[name='pan_no']").val());
        $("[name='owner_pan_date_of_issue']").attr('readonly', true).val($("[name='pan_date_of_issue']").val());
        $("[name='owner_pan_place_of_issue']").attr('readonly', true).val($("[name='pan_place_of_issue']").val());
        $("[name='owner_share_holders']").attr('readonly', true).val($("[name='share_holders']").val());
    }

    function removeClonedInputDataFromOwnerInput(){
        $("[name='owner_name']").attr('readonly', false).val("");
        $("[name='owner_father_name']").attr('readonly', false).val("");
        $("[name='owner_grand_father_name']").attr('readonly', false).val("");
        $("[name='owner_husband_name']").attr('readonly', false).val("");
        $("[name='owner_father_in_law_name']").attr('readonly', false).val("");
        $("[name='owner_spouse_name']").attr('readonly', false).val("");
        $("[name='owner_address']").attr('readonly', false).val("");
        $("[name='owner_district']").attr('readonly', false).val("");
        $("[name='owner_citizenship_no']").attr('readonly', false).val("");
        $("[name='owner_date_of_issue']").attr('readonly', false).val("");
        $("[name='owner_place_of_issue']").attr('readonly', false).val("");
        $("[name='owner_contact_no']").attr('readonly', false).val("");
        $("[name='owner_reg_no']").attr('readonly', false).val("");
        $("[name='owner_pan_no']").attr('readonly', false).val("");
        $("[name='owner_pan_date_of_issue']").attr('readonly', false).val("");
        $("[name='owner_pan_place_of_issue']").attr('readonly', false).val("");
        $("[name='owner_share_holders']").attr('readonly', false).val("");
    }


</script>

@endpush
