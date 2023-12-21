@extends('backend.layouts.master')

@section('title','EGP Nepal | Notice Type - Trash Index')

@section('breadcrumb', 'Notice Type Trash')


@section('content')
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            {{-- Table --}}
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="col-12">
                        <h5 style="display: inline;">Notice Type</h5>
                        <a href="{{ route("backend.global.option.noticeType.index") }}" class="btn btn-secondary float-right">Previous Page</a>
                    </div>
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

        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->
@endsection


@push('scripts')

<script>
    $('#global-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.global.option.noticeType.getTrashedNoticeTypeData') }}",
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
