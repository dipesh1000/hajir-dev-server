@extends('layouts.admin.master')

@section('title','Leave Type')

@section('breadcrumb', 'Leave Type')


@section('content')
  <!--  BEGIN CONTENT AREA  -->

        <div class="row layout-top-spacing">
            {{-- Table --}}
            <div class="col-xl-7 col-lg-6 col-sm-7  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="col-12">
                        <h5 style="display: inline;">Leave Type</h5>
                        {{-- <a href="{{ route("backend.global.option.noticeType.trashedIndex") }}" class="btn btn-danger float-right"><i class="fa fa-trash"></i> Trash</a> --}}
                    </div>
                    <hr>
                    <div class="table-responsive mb-4 mt-4">
                        <table id="global-table" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Title</th>
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
            {{-- Create Form --}}
             @include('SuperAdmin::backend.leaveType.create')
        </div>
   

    <!-- Modal -->
    <div class="modal animated fadeInUp" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">

    </div>

</div>
<!--  END CONTENT AREA  -->
@endsection


@push('scripts')

<script>
    $('#global-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.leave.type.getLeaveTypeData') }}",
        columns: [{
                "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
            },
            {
                data: 'title',
                render: function(data, type, row) {
                    return row.title;
                }
            },
            {
                data: 'status',
                render: function(data, type, row) {
                    return row.status;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ]
    });
</script>

@endpush
