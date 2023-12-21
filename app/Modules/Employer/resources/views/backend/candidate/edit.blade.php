@extends('layouts.employer.master')


@section('title', ' Edit Employee')

@section('breadcrumb', 'Edit Employee')

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

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="col-12">
                    <h5 style="display: inline;">Edit {{ $companyCandidate->candidate->firstname}}</h5>
                    <a class="btn btn-secondary float-right " href="{{ route('employer.company.viewEmployees',$companyCandidate->company->slug) }}">Previous Page</a>
                </div>
                <hr>
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <form action="{{ route('employer.candidate.update',$companyCandidate->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- <input type="hidden" class="form-control" id="updateid" value="{{ $client->id }}"> --}}
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

 @endpush

