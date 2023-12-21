@extends('layouts.admin.master')

@section('title', '| Employers')

@section('breadcrumb', 'Employers')

@push('styles')
<link href="{{ asset('backendfiles/assets/css/apps/todolist.css')}}" rel="stylesheet" type="text/css" />


@endpush

@section('content')
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-6">
                <div class="col-12">
                    <h5 style="display: inline;">Employers Table</h5>
                </div>
                <hr class="mb-0">
                <div class="table-responsive mb-2 mt-2">
                    <table id="global-table" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.no.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>No. Of Company </th>
                                <th>Phone No.</th>
                                <th>Address</th>
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
        
@endsection

@push('scripts')

<script src="{{asset('backendfiles/assets/js/apps/todoList.js')}}"></script>

    <script>
        $('#global-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.employer.getEmployerData') }}",
            columns: [
                {
                    width:'1%',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
                    width:'20%',
                    data: 'email',
                    render: function(data, type, row) {
                        return row.email?? 'N/A';;
                    }
                },
                {
                    orderable: false,
                    searchable: false,
                    width:'14%',
                    data: 'company',
                    class:'text-center',
                    render: function(data, type, row) {
                        return '<span class="badge badge-primary" >'+row.company?? 'N/A' +'</span>';;
                    }
                },
                {
                    width:'15%',
                    data: 'phone',
                    render: function(data, type, row) {
                        return row.phone?? 'N/A';
                    }
                },
                {
                    width:'15%',

                    data: 'address',
                    render: function(data, type, row) {
                        return row.address ?? 'N/A';
                    }
                },
                // {
                //     width:'15%',
                //     data: 'status',
                //     name: 'status',
                //     class:'text-center',
                //     orderable: true,
                //     searchable: true
                // },
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


        $(document).on('click', '.deleteClient', function() {
            event.preventDefault();
            var url = $(this).attr('data-id');
            swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "get",
                    url: route,
                    contentType: false,
                    processData: false,
                    beforeSend: function(data) {
                        loader();
                    },
                    success: function(data) {
                        toastr.success('Successfully Deleted !!');
                        $('#global-table').DataTable().ajax.reload();
                        currentevent.attr('disabled', false);
                    },
                    error: function(err) {
                        if (err.status == 422) {
                            $.each(err.responseJSON.errors, function(i, error) {
                                var el = $(document).find('[name="' + i + '"]');
                                el.after($('<span style="color: red;">' + error[0] + '</span>')
                                    .fadeOut(9000));
                            });
                        }

                        currentevent.attr('disabled', false);
                    },
                    complete:function(){
                        $.unblockUI();
                    }
                });
            }
        });
        });

       

    </script>

    {{-- <script>
         $(document).on('change', '.clientStatus', function() {

            event.preventDefault();
            var id = $(this).attr('data-id');
            var editUrl = "{{ route('client.changeClientStatus', ':id') }}";
            myUrl = editUrl.replace(':id', id);
            var status = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: myUrl,
                data:{ 'status':status} ,
                success: function(data) {
                    console.log(data);
                    toastr.success(data.message);
                    $('#global-table').DataTable().ajax.reload();
                },
            });
        });
    </script> --}}

@endpush
