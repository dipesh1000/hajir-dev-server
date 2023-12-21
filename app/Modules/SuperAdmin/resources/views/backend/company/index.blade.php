@extends('layouts.admin.master')

@section('title', '| Companies')

@section('breadcrumb', 'Companies')

@push('styles')
<link href="{{ asset('backendfiles/assets/css/apps/todolist.css')}}" rel="stylesheet" type="text/css" />


@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="widget-content widget-content-area br-6">
                        <div class="col-12">
                            <h5 style="display: inline;">Company Table</h5>
                            {{-- <a class="btn btn-success float-right " href="{{ route('receptionist.client.create')}}">Create</a> --}}

                        </div>
                        <hr class="mb-0">
                        <div class="table-responsive mb-2 mt-2">
                            <table id="global-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.no.</th>
                                        <th>Company Name</th>
                                        <th>Code</th>
                                        <th>Email</th>
                                        <th>Candidates</th>
                                        <th>Employer Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    <!--  END CONTENT AREA  -->
@endsection

@push('scripts')

<script src="{{asset('backendfiles/assets/js/apps/todoList.js')}}"></script>

    <script>
        $('#global-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.company.getCompanyData') }}",
            columns: [
                {
                    width:'1%',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    class:'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    width:'20%',
                    data: 'name',
                    render: function(data, type, row) {
                        return '<p class="text-capitalize">'+row.name+'</p>';
                    }
                },
                {
                    width:'10%',
                    data: 'code',
                    render: function(data, type, row) {
                        return '<span class="badge badge-info">'+row.code+'</span>';
                    }
                },
                {
                    width:'10%',
                    data: 'email',
                    render: function(data, type, row) {
                        return row.email;
                    }
                },
                {   
                    orderable: false,
                    searchable: false,
                    width:'5%',
                    data:'candidate_count',
                    class:'text-center',
                    render: function(data, type, row) {
                        return '<span class="badge badge-info">'+data+'</span>';
                    }
                },
                {
                    orderable: false,
                    searchable: false,
                    width:'15%',
                    data: 'employer',
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    width:'12%',
                    data: 'status',
                    name: 'status',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
                 {
                    width:'2%',
                    data: 'action',
                    name: 'action',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
            ]
        });

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

        // $(document).on('change', '.clientStatus', function() {

        //     event.preventDefault();
        //     var id = $(this).attr('data-id');
        //     var editUrl = "{{ route('client.changeClientStatus', ':id') }}";
        //     myUrl = editUrl.replace(':id', id);
        //     var status = $(this).val();
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         type: 'POST',
        //         url: myUrl,
        //         data:{ 'status':status} ,
        //         success: function(data) {
        //             console.log(data);
        //             toastr.success(data.message);
        //             $('#global-table').DataTable().ajax.reload();
        //         },
        //     });
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
