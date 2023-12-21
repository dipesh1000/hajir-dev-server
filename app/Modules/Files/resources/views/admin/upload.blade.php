@extends('Backend.layout.master')

@section('content')

<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="h3">Upload New File</h1>
    </div>
    <div class="col-md-6 text-md-right">
        <a href="{{ route('admin.files.index') }}">
            <i class="fas fa-angle-left"></i>
            <span>Back to uploaded files</span>
        </a>
    </div>
</div>

<div class="row">

    <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                        <h4>Drag and Drop your files</h4>
                    </div>  
                    </div>

                    <form method="post" action="{{route('admin.files.store')}}" enctype="multipart/form-data"
                    class="dropzone" id="dropzone">
                        @csrf
                    </form> 
                </div>


            </div>
        </div>
    </div>



</div>
@endsection

@push('script')
<script type="text/javascript">

    Dropzone.options.dropzone =
    {
        autoProcessQueue: true,
        maxFilesize: 10,
        renameFile: function (file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        // acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: false,
        timeout: 60000,
        success: function (file, response) {
            if(response.status == "success"){
                toastr.success(response.message);
            }else{
                toastr.error(response.message);
            }
        },
        error: function (file, response) {
            toastr.error(response.message);
            return false;
        }
    };

   
</script>
@endpush
