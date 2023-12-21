@extends('layouts.employer.master')

@section('title', '| Monthly Report')

@section('breadcrumb', 'Monthly Report')

@push('styles')

<link href="{{ asset('backendfiles/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />


<style>
    a.btn.active{
        color: #1ec41e  !important;
    }
   
</style>


@endpush

@section('content')
    <!--  BEGIN CONTENT AREA  -->

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-6">
                @php
                    $urlYear = Request::get('year') ;
                @endphp
                <div class="col-12 ">
                    <h5 class="">Monthly Report
                    <a class="btn btn-secondary float-right " href="{{ route('employer.company.index')}}">Previous Page</a>

                </div>
                <hr class="mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control yearSelect">
                            @foreach ($years as $year)
                                <option value="{{ $year}}" {{ $urlYear == $year ? 'selected' : ''}} >
                                    {{ $year}}
                                </option>  
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-center">
                       
                        @foreach ($months as $month)
                            <a href="{{ route('employer.company.monthlyReports',$company->slug).'?month='.$month.'&year='.$urlYear}}" 
                                 class="btn mr-2 {{ $month == $activeMonth ? 'active' : ''}}">{{ $month }}</a>
                        @endforeach
                      
                    </div>
                </div>
                <div class="row mt-4">
                   
                    <div class="col-md-8 mx-auto">
                         <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalAmount = 0;
                                @endphp
                                @if(isset($payments) && count($payments)>0)
                                    @foreach ($payments as $payment)
                                    
                                        <tr>
                                            <td>{{ $payment->candidate->firstname }}</td>
                                            <td class="text-center">{{ $payment->status}}</td>
                                            <td class="text-center">{{ $payment->paid_amount }}</td>
                                        </tr>
                                        @php
                                            $totalAmount = $totalAmount + $payment->paid_amount;
                                        @endphp
                                    @endforeach

                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Balance</td>
                                            <td class="text-center">{{ $totalAmount ?? 0}}</td>
                                        </tr>
                                    </tfoot>
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No Payments Available</td>
                                    </tr>
                                @endif
                               
                            </tbody>
                           
                         </table>
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
        $(document).on('change','.yearSelect', function () {
            var year = $(this).val();
            var url = "{{ route('employer.company.monthlyReports',$company->slug).'?year='}}"+year;
            console.log(url);
            window.location.href = url;
        });
    </script>

    {{-- <script>
         
        $('#global-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employer.company.getCompanyData','type='.request()->type??'') }}",
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
                    width:'15%',
                    data: 'name',
                    render: function(data, type, row) {
                        return '<p class="text-capitalize">'+row.name+'</p>';
                    }
                },
                {
                    width:'4%',
                    data: 'code',
                    render: function(data, type, row) {
                        var code = '';
                        (row.code == 1) ? code = 'Automatic' : code='Manual'
                        return '<span class="badge badge-info">'+
                                code
                            +'</span>';
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
                    // orderable: false,
                    searchable: false,
                    width:'5%',
                    data:'candidate_count',
                    class:'text-center',
                    render: function(data, type, row) {
                        return '<span class="badge badge-info">'+data+'</span>';
                    }
                },
                {
                    width:'6%',
                    data: 'status',
                    name: 'status',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
                 {
                    width:'12%',
                    data: 'action',
                    name: 'action',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
            ]
        });

        $(document).on('change', '.clientStatus', function() {

            event.preventDefault();
            var slug = $(this).attr('data-slug');
            var editUrl = "{{ route('employer.company.statusChange', ':slug') }}";
            myUrl = editUrl.replace(':slug', slug);
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
                    toastr.success(data.message);
                    $('#global-table').DataTable().ajax.reload();
                },
            });
        });

    </script> --}}


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
