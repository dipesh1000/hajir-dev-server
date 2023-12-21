@extends('layouts.admin.master')

@push('css')

@endpush

@section('content')
    <div class="modal fade" id="MediaModal" role="dialog">
        <div class="modal-dialog medialibraryModal modal-xl">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li><a data-toggle="tab" href="#medialibraryUpload">Upload Files</a></li>
                        <li class="active"><a data-toggle="tab" href="#medialibraryselect">Media Library</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="medialibraryUpload" class="tab-pane fade">
                            <form method="post" action="{{ route('admin.files.store') }}" enctype="multipart/form-data"
                                class="dropzone" id="dropzone">
                                @csrf
                            </form>
                        </div>
                        <div id="medialibraryselect" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="medialibraryfiltersection">
                                        <div class="filterSection">
                                            <h4>
                                                Filter Media
                                            </h4>
                                            <div class="filter-select">
                                                <select name="filterFiles" id="filterFiles" class="form-control">
                                                    <option value="">All Files</option>
                                                    <option value="image">Images</option>
                                                    <option value="video">Videos</option>
                                                    <option value="audio">Audios</option>
                                                    <option value="archive">Archives</option>
                                                    <option value="document">Documents</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filterSection">
                                            <h4>Search</h4>
                                            <div class="filter-search">
                                                <input type="search" id="Search" placeholder="Search Media Files"
                                                    name="search" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="medialibraryfilesection">

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="medialibraryDetails">
                                        <h4>ATTACHMENT DETAILS</h4>
                                        <div class="attachment-file">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>


    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">All uploaded files</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <div class="row">
                <div class="col-md-6 text-md-right">
                </div>
                <div class="col-md-6">
                   
                    <a href="{{ route('admin.files.upload') }}" class=" btn btn-primary">Add Files</a>

                </div>
            </div>
        </div>


    </div>

    <div class="row">

        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-12 col-12">
                            <h4>All files</h4>
                        </div>
                        <div class="col-xl-8 col-md-8 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <select class="form-control" id="exampleFormControlSelect1">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="t-text" class="sr-only">Text</label>
                                        <input id="t-text" type="text" name="txt" placeholder="Some Text..."
                                            class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="submit" name="txt" class="mt-2 btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    @include('Files::admin.images');
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
 
@endpush
