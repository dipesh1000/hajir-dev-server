<div class="col-xl-5 col-lg-6 col-sm-5  layout-spacing">
    <div class="statbox widget box box-shadow" style="padding: 0px">
        <div class="widget-content widget-content-area">
            <div class="form-group col-md-12">
                <h5>Create Leave Type</h5>
            </div>
            <hr>
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row">
                <form action="{{ route('backend.leave.type.store') }}" method="post" enctype="multipart/form-data" id="submit-form">
                    <div class="form-group col-md-12">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" value="{{ old('title') }}" placeholder="Enter Title" name="title" required>
                    </div>
                    <div class="col-md-12">
                    <div class="form-group ">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option disabled selected>Select Status</option>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : ''}}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : ''}}>Inactive</option>
                        </select>
                    </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control summernote" id="remarks" name="remarks"> {{ old('remarks') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mb-3">Submit</a>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
