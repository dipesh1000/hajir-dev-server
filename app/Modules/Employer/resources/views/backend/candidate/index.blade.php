@extends('layouts.employer.master')

@section('title', '| Candidates')

@section('breadcrumb', 'Candidates')

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
    .table > tbody::before{
        content: none !important;
    }
</style>
@endpush

@section('content')
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="widget-content widget-content-area br-6">
                <div class="col-12 d-flex justify-content-between">
                    <h5>{{ $company->name }} - Candidates Table</h5>
                    
                    <div class="d-flex">
                        <a class="btn btn-secondary mr-2 pl-3 pr-3" href="{{ route('employer.company.viewEmployees',$company->slug).'?type=Active'}}">Active</a>
                        <a class="btn btn-secondary  mr-2 pl-3 pr-3 " href="{{ route('employer.company.viewEmployees',$company->slug).'?type=Inactive'}}">Inactive</a>
                    </div>
                    <a class="btn btn-primary float-right " href="{{ route('employer.candidate.create',$company->slug)}}">Add New</a>
                </div>
                <hr class="mb-0">
                <div class="table-responsive mb-2 mt-2">
                    <table id="global-table" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.no.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Code</th>
                                <th>Phone No.</th>
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
        
@endsection

@push('scripts')
    <script>
        $('#global-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employer.candidate.getCandidateData',$company->id).'?type='.request()->type??'' }}",
            columns: [
                {
                    width:'1%',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    width:'18%',
                    data: 'candidate.firstname',
                    render: function(data, type, row) {
                        return '<p class="text-capitalize">'+row.candidate.firstname+' '+row.candidate.lastname ??''+'</p>';
                    }
                },
                {
                    width:'15%',
                    data: 'candidate.email',
                    render: function(data, type, row) {
                        return row.candidate.email?? 'N/A';;
                    }
                },
                {
                    width:'8%',
                    data: 'code',
                    class:'text-center',
                    render: function(data, type, row) {
                        return '<span class="badge badge-primary" >'+row.code?? 'N/A' +'</span>';;
                    }
                },
                {
                    width:'10%',
                    data: 'contact',
                    render: function(data, type, row) {
                        return row.candidate.phone?? 'N/A';
                    }
                },
                {
                    width:'8%',
                    data: 'status',
                    name: 'status',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
            
                 {
                    width:'10%',
                    data: 'action',
                    name: 'action',
                    class:'text-center',
                    orderable: true,
                    searchable: true
                },
            ]
        });


        $(document).on('click', '.deleteCompantCandidate', function() {
            event.preventDefault();
            var currentThis = $(this);
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
                var url = currentThis.data('href');
                    window.location.href = url;
            }
        });
        });

       

    </script>

    <script>
         $(document).on('change', '.companyCandidateStatus', function() {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var editUrl = "{{ route('employer.candidate.statusChange', ':id') }}";
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
    </script>

@endpush
