@extends('layouts.employer.master')


@section('title', 'Add New Employee')

@section('breadcrumb', 'Add New Employee')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        legend{
            font-size: 1.25rem !important;
        }
        input.largerCheckbox {
            width: 18px;
            height: 18px;
        }
       
    </style>
@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-4">
                <div class="col-12">
                    <h5 style="display: inline;">Add New Employee</h5>
                    <a class="btn btn-secondary float-right " href="{{ url()->previous() }}">Previous Page</a>

                </div>
                <hr>
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <form action="{{ route('employer.candidate.store',$company->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('Employer::backend.candidate.commonForm')
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr(document.getElementById('dateTimeFlatpickr'), {
        dateFormat: "Y-m-d",
        maxDate: "{{date('Y-m-d')}}",
    });
    flatpickr(document.getElementById('dateTimeFlatpickr2'), {
        dateFormat: "Y-m-d",
    });
</script>

<script>
   $(function() {
	$('[data-decrease]').click(decrease);
	$('[data-increase]').click(increase);
	$('[data-value]').change(valueChange);
});



function decrease() {
	var value = $(this).parent().find('[data-value]').val();
	if(value > 1) {
		value--;
		$(this).parent().find('[data-value]').val(value);
	}
}

function increase() {
	var value = $(this).parent().find('[data-value]').val();
	if(value < 100) {
		value++;
		$(this).parent().find('[data-value]').val(value);
	}
}

function valueChange() {
	var value = $(this).val();
	if(value == undefined || isNaN(value) == true || value <= 0) {
		$(this).val(1);
	} else if(value >= 101) {
		$(this).val(100);
	}
}


</script>

@endpush
