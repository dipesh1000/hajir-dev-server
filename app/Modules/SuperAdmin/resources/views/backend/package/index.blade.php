@extends('layouts.admin.master')

@section('title','| Package')

@section('breadcrumb', 'Package')


@section('content')
  <!--  BEGIN CONTENT AREA  -->

        <div class="row layout-top-spacing">
            {{-- Table --}}
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="col-12">
                        <h5 style="display: inline;">Package</h5>
                        <a href="{{ route("backend.package.create") }}" class="btn btn-primary float-right"> Add New Package</a>
                    </div>
                    <hr>
                    <div class="table-responsive mb-4 mt-4">
                        <table id="global-table" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="1%">S.No.</th>
                                    <th width="10%">Title</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Price</th>
                                    <th width="10%">Features</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Create Form --}}
             {{-- @include('SuperAdmin::backend.package.create') --}}
        </div>
   

    <!-- Modal -->

   

</div>
<!--  END CONTENT AREA  -->
@endsection


@push('scripts')

<script>
    $('#global-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('backend.package.getPackageData') }}",
        columns: [{
                    "data": 'DT_RowIndex',
                    width:'1%',
                    orderable: false,
                    searchable: false
            },
            {
                data: 'title',
                width:'20%',
                render: function(data, type, row) {
                    return row.title;
                }
            },
            {
                data: 'status',
                width:'10%',
                render: function(data, type, row) {
                    return row.status;
                }
            },
            {
                data: 'price',
                width:'10%',
                render: function(data, type, row) {
                    return row.price;
                }
            },
            {
                data: 'feature',
                width:'25%',
                render: function(data, type, row) {
                    separator = '<br>';
                    feature = row.feature.join(separator);
                    return feature
                }
            },
            {
                data: 'action',
                name: 'action',
                width:'10%',
                orderable: true,
                searchable: true
            },
        ]
    });
</script>

<script>
    $('#remarks').summernote({
        tabsize: 5,
        height: 200,
        toolbar: [
        // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],    
            ['para', ['ul', 'ol', 'paragraph']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
        ]
    });
</script>

@endpush
