
@extends('layouts.admin.master')

@section('title', '| Package - Edit')

@section('breadcrumb','Package - Edit')


@section('content')

<div class="row layout-top-spacing">

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="statbox widget box box-shadow" style="padding: 0px">
            <div class="widget-content widget-content-area">

                <div class="col-md-12">
                    <h5>Update Leave Type</h5>
                </div>
                <hr>
            <div class="col-xl-12 col-md-12 col-sm-12">
                <form action="{{ route('backend.package.update',$package->slug) }}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        {{-- {{ var_dump(old('feature.0')) }} --}}
                        <input type="hidden" class="form-control" id="updateid" value="{{ $package->slug }}">

                        <div class="col-md-6">
                        
                            <div class="form-group ">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter Title" value = "{{ $package->title }}" name="title">
                            </div>
                       
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" placeholder="Enter Price..." value = "{{ $package->price }}" name="price">
                            </div>
                       
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option disabled selected>Select Status</option>
                                    <option value="Active" {{ $package->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $package->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        
                            <div class="form-group">
                                <label for="remarks">Details</label>
                                <textarea class="form-control summernote" id="remarks" name="remarks">{{ $package->remarks}}</textarea>
                            </div>
                        

                        </div>
                        <div class="col-md-6">
                            <label for="title">Features</label>
                            <div class="col-md-12">
                                <div class="form-group d-flex">
                                    <input type="text" class="form-control" id="feature"
                                        placeholder="Enter Feature..." >
                                    <button class="btn btn-info ml-2 featureAdd" type="button">Add</button>
                                </div>
                                
                                @if($package->feature != null && !Empty($package->feature))
                                    @foreach ($package->feature as $key => $value)
                                  
                                    <div class="form-group d-flex">
                                        <input type="text" class="form-control" id="feature"
                                            value="{{ old('title') ?? $value }}" placeholder="Enter Feature..." name="feature[]">
                                        <button class="btn btn-danger ml-2 featureDelete" type="button">Del</button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mt-2">Submit</a>
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
            $(this).parent('div').parent('div').parent('div').append(
                '<div class="col-md-12"> <div class="form-group d-flex"> <input type="text" class="form-control" id="feature" value="'+value+'" placeholder="Enter Feature..." name="feature[]" required ><button class="btn btn-danger ml-2 featureDelete" type="button">Del</button></div></div>'
                );
            $(this).siblings('input').val('');

        });

        $(document).on('click', '.featureDelete', function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
        });
    </script>
@endpush