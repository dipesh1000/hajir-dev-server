@extends('layouts.employer.master')

@section('title', '| Inbox')

@section('breadcrumb', 'Inbox')

@push('styles')

<link href="{{ asset('backendfiles/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />


<style>
    .btn-table{
        padding: 4px !important;
        font-size: 12px !important;
    }
    #global-table_wrapper{
        padding-right: 0px !important;
        padding-left: 0px !important;

    }
    .leaveText{
        min-width: 65%;
    }
    
    .leaveBorder{
        border-bottom-right-radius: 5px;
        border-right: 2px solid grey;
        border-bottom: 2px solid grey;
        padding-bottom: 6px;

    }

    h6{
        font-weight: 700;
        font-size: 18px;
    }
</style>


@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-6">
                <div class="col-12 d-flex justify-content-between">
                    <h5 class="">Inbox</h5>
                </div>
                <hr class="m-0">
                <div class="row">
                    @if (isset($leaves) && count($leaves) > 0)
                        @foreach ($leaves as $leave)
                            <div class="col-md-4 mt-4">
                                <a href="{{ route('employer.leaveDetail',$leave->id)}}">
                                    <div class="d-flex justify-content-start leaveBorder align-items-center">
                                        <div class="mr-3 pl-2">
                                        <img src="{{asset('backendfiles/assets/img/90x90.jpg')}}" width="90px" height="90px" alt="PP">
                                        </div>
                                        <div class="leaveText">
                                            <h6>{{ ($leave->candidate->firstname?? '').' '.($leave->candidate->lastname ?? '')}}</h6>
                                            <div class="d-flex justify-content-between">
                                                <p>Sick Leave</p>
                                                <p>{{ $leave->created_at->format('d M, Y')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-12 mt-4">
                            <h6 class="text-center"> No Any Messages.</h6>
                        </div>
                    @endif

                    {{-- <a href="{{ route('employer.leaveDetail',1)}}">
                        <div class="col-md-4 mt-4">
                            <div class="d-flex justify-content-start leaveBorder align-items-center">
                                <div class="mr-3 pl-2">
                                <img src="{{asset('backendfiles/assets/img/90x90.jpg')}}" width="90px" height="90px" alt="PP">
                                </div>
                                <div class="leaveText">
                                    <h6>{{ $leave->candidate->firstname ?? ''}}</h6>
                                    <div class="d-flex justify-content-between">
                                        <p>Sick Leave</p>
                                        <p>{{ 'asdasd'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a> --}}
                   
                    {{-- <div class="col-md-4 mt-4">
                        <div class="d-flex justify-content-start leaveBorder align-items-center">
                            <div class="mr-3 pl-2">
                            <img src="{{asset('backendfiles/assets/img/90x90.jpg')}}" width="90px" height="90px" alt="PP">
                            </div>
                            <div class="leaveText">
                                <h6>Sarad Shrestha</h6>
                                <div class="d-flex justify-content-between">
                                    <p>Sick Leave</p>
                                    <p>2020-20-2</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                  
                </div>
                
                <div class="row  mt-4">
                    <div class="col-md-12 d-flex justify-content-center">
                       
                        {{ $leaves->links() }}
                       
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!--  END CONTENT AREA  -->
@endsection

@push('scripts')

<script src="{{asset('backendfiles/assets/js/apps/todoList.js')}}"></script>
<script src="{{ asset('backendfiles/plugins/sweetalerts/sweetalert2.min.js')}}"></script>

    <script>
        $(document).on('click','.deleteCompany', function () {
            var currentThis = $(this);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            padding: '2em'
            }).then(function(result) {
                if (result.value) {
                    var url = currentThis.data('href');
                    window.location.href = url;
                }
            })
        })
    </script>

   


{{--         

        // $(document).on('click', '.deleteClient', function() {
        //     event.preventDefault();
        //     var url = $(this).attr('data-id');
        //     swal({
        //     title: 'Are you sure?',
        //     text: "You won't be able to revert this!",
        //     type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonText: 'Delete',
        //     padding: '2em'
        // }).then(function(result) {
        //     if (result.value) {
        //         $.ajax({
        //             type: "get",
        //             url: route,
        //             contentType: false,
        //             processData: false,
        //             beforeSend: function(data) {
        //                 loader();
        //             },
        //             success: function(data) {
        //                 toastr.success('Successfully Deleted !!');
        //                 $('#global-table').DataTable().ajax.reload();
        //                 currentevent.attr('disabled', false);
        //             },
        //             error: function(err) {
        //                 if (err.status == 422) {
        //                     $.each(err.responseJSON.errors, function(i, error) {
        //                         var el = $(document).find('[name="' + i + '"]');
        //                         el.after($('<span style="color: red;">' + error[0] + '</span>')
        //                             .fadeOut(9000));
        //                     });
        //                 }

        //                 currentevent.attr('disabled', false);
        //             },
        //             complete:function(){
        //                 $.unblockUI();
        //             }
        //         });
        //     }
        // });
        // });

        

        // $(document).on('click', '.edit-new', function() {

        //     event.preventDefault();
        //     var id = $(this).attr('data-id');
        //     var editUrl = "{{ route('backend.user.edit', ':id') }}";
        //     myUrl = editUrl.replace(':id', id);

        //     $.ajax({
        //         type: 'GET',
        //         url: myUrl,
        //         success: function(data) {
        //             $("#editModal").modal('show');
        //             $("#editModal").html(data);
        //         },
        //     });
        // });

        // $(document).on("submit", "#user-update-form", function(e) {

        //     e.preventDefault();
        //     var currentevent = $(this);
        //     currentevent.attr('disabled');
        //     var params = $('#user-update-form').serializeArray();
        //     var formData = new FormData($('#user-update-form')[0]);

        //     var id = $("#updateid").val();
        //     var route = $(this).attr('action');
        //     var myUrl = route.replace(':id', id);

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     $.ajax({
        //         type: "POST",
        //         url: myUrl,
        //         contentType: false,
        //         processData: false,
        //         cache: false,
        //         data: formData,
        //         beforeSend: function(data) {
        //             loader();
        //         },
        //         success: function(data) {
        //             toastr.success(data.message);
        //             $('#editModal').modal('hide');
        //             $('#global-table').DataTable().ajax.reload();
        //         },
        //         error: function(err) {
        //             if (err.status == 422) {
        //                 console.log(err);
        //                 $.each(err.responseJSON.errors, function(i, error) {
        //                     var el = $(document).find('[name="' + i + '"]');
        //                     el.after($('<span style="color: red;">' + error[0] + '</span>')
        //                         .fadeOut(3000));
        //                 });
        //             }
        //         },
        //         complete: function() {}
        //     });
        // }); --}}


@endpush
