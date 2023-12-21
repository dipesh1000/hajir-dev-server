<link rel="icon" type="image/x-icon" href="{{ asset('backendfiles/assets/img/favicon.ico') }} " />
<link href="{{ asset('backendfiles/assets/css/loader.css') }} " rel="stylesheet" type="text/css" />

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
<link href=" {{ asset('backendfiles/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('backendfiles/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->

{{-- Sweet Alerts --}}
{{-- <link href="{{ asset('backendfiles/plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
<link href="{{ asset('backendfiles/plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
{{-- <link href="{{ asset('backendfiles/assets/css/components/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" /> --}}


<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

{{-- DATATABLES --}}
<link href="{{ asset('backendfiles/plugins/table/datatable/datatables.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('backendfiles/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet" type="text/css" >

{{-- Summernote css/js --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

{{-- FileUpload --}}
<link href="{{ asset('backendfiles/plugins/file-upload/file-upload-with-preview.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stack('styles')
