@extends('layouts.admin.master')

@section('title', '| Package')

@section('breadcrumb', 'Package')


@section('content')
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="statbox widget box box-shadow" style="padding: 0px">
                <div class="widget-content widget-content-area">

                    <div class="col-md-12">
                        <h5>Create Leave Type</h5>
                    </div>
                    <hr>
                    <form action="{{ route('backend.package.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" value="{{ old('title') }}"
                                        placeholder="Enter Title..." name="title" required>
                                        @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" step=any min=0 class="form-control" id="price"
                                        value="{{ old('price') }}" placeholder="Enter Price..." name="price" required>
                                        @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="">
                                    <div class="form-group ">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option disabled selected>Select Status</option>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control " id="remarks" name="remarks"> {{ old('remarks') }}</textarea>
                                    @error('remarks')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary float-right mb-3">Submit</a>
                            </div>
                            <div class="col-md-6">
                                <label for="title">Features</label>
                                <div class="col-md-12">
                                    <div class="form-group d-flex">
                                        <input type="text" class="form-control" id="feature"
                                             placeholder="Enter Feature..." name=""
                                            >
                                        <button class="btn btn-info ml-2 featureAdd" type="button">Add</button>
                                    </div>
                                    @if (old('feature') !=null && !Empty(old('feature')) )
                                        @foreach ( old('feature') as $feature)
                                        <div class="form-group d-flex">
                                            <input type="text" class="form-control" id="feature"
                                                value="{{ $feature }}" placeholder="Enter Feature..." name="feature[]"
                                                required>
                                            <button class="btn btn-danger ml-2 featureDelete" type="button">Del</button>
                                        </div>
                                        @error('feature')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @endforeach
                                       
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.featureAdd', function(e) {
            e.preventDefault();
            var value = $(this).siblings('input').val();
            $(this).parent('div').parent('div').parent('div').append('<div class="col-md-12"><div class="form-group d-flex">'+
                                        '<input type="text" class="form-control" id="feature" value="'+value+'" placeholder="Enter Feature..." name="feature[]" required >'+
                                        '<button class="btn btn-danger ml-2 featureDelete" type="button">Del</button></div></div>'
                                    );

            $(this).siblings('input').val('');
        });

        $(document).on('click', '.featureDelete', function(e) {
            e.preventDefault();
            $(this).parent('div').parent('div').remove();
        });
    </script>
@endpush
