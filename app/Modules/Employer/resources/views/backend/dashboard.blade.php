@extends('layouts.employer.master')


@section('title', "| Dashboard")

@push('styles')
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('backendfiles/plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('backendfiles/assets/css/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backendfiles/assets/css/components/custom-counter.css')}}" rel="stylesheet" type="text/css">
@endpush


@section('content')


@endsection


@push('scripts')


<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="{{ asset('backendfiles/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('backendfiles/assets/js/dashboard/dash_1.js') }}"></script>
{{-- <script src="{{ asset('backendfiles/assets/js/scrollspyNav.js') }}"></script> --}}
<script src="{{ asset('backendfiles/plugins/counter/jquery.countTo.js') }}"></script>
<script src="{{ asset('backendfiles/assets/js/components/custom-counter.js') }}"></script>



@endpush
