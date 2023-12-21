<div class="modal-dialog modal-dialog-centered" role="document" id="standardModalLabel">
    <div class="modal-content">
      <div class="modal-body text-center">
          <div class="icon-content">
            <div class="avatar avatar-xl">
                @if($file->type == "image")
                        <img src="{{ thumbnail_url($file) }}" class="rounded-circle" ">
                        @else
                        <svg style="width: 150px;margin-bottom:10px;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                   <circle style="fill:#FF432E;" cx="256" cy="256" r="256"/>
                   <path style="fill:#DB0404;" d="M510.427,284.375l-125.86-125.86l-13.818,43.368L224.808,55.942L163.31,60l-38.04,359.271
                       l89.389,89.388c13.46,2.186,27.265,3.34,41.342,3.34C387.793,512,496.307,412.404,510.427,284.375z"/>
                   <polygon style="fill:#FFFFFF;" points="308.398,82.346 125.269,82.346 125.269,419.271 384.567,419.271 384.567,158.515 "/>
                   <polygon style="fill:#F2F6FC;" points="384.567,158.515 308.398,82.346 255.571,82.346 255.571,419.271 384.567,419.271 "/>
                   <polygon style="fill:#DCE1EB;" points="308.398,158.515 384.567,158.515 308.398,82.346 "/>
                   <polygon style="fill:#FFD400;" points="244.954,82.346 125.269,82.346 125.269,302.544 294.734,302.544 294.734,132.126 "/>
                   <polygon style="fill:#FFB000;" points="294.734,132.126 244.953,82.346 209.143,82.346 209.143,302.544 294.734,302.544 "/>
                   <polygon style="fill:#FF9500;" points="244.953,132.126 294.734,132.126 244.953,82.346 "/>
                   <path style="fill:#4A7AFF;" d="M188.701,197.227c-24.679,0-44.757-20.078-44.757-44.757v-41.944h24v41.944
                       c0,11.446,9.312,20.757,20.757,20.757s20.757-9.312,20.757-20.757V82.348c0-11.446-9.312-20.757-20.757-20.757
                       s-20.757,9.312-20.757,20.757h-24c0-24.679,20.078-44.757,44.757-44.757s44.757,20.078,44.757,44.757v70.122
                       C233.458,177.149,213.38,197.227,188.701,197.227z"/>
                   <path style="fill:#2864F0;" d="M188.701,37.591c-0.131,0-0.26,0.009-0.391,0.01v24.01c0.131-0.002,0.259-0.02,0.391-0.02
                       c11.446,0,20.757,9.312,20.757,20.757v70.122c0,11.446-9.312,20.757-20.757,20.757c-0.132,0-0.26-0.017-0.391-0.02v24.01
                       c0.131,0.001,0.26,0.01,0.391,0.01c24.679,0,44.757-20.078,44.757-44.757V82.348C233.458,57.669,213.38,37.591,188.701,37.591z"/>
                   <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                        @endif
            </div>
          </div>
            <div class="form-group">
                <label >File Name</label>
                <input type="text" class="form-control" value="{{ $file->title??'' }}" disabled>
            </div>
            <div class="form-group">
                <label >File Type</label>
                <input type="text" class="form-control" value="{{ $file->type??'' }}" disabled>
            </div>
            <div class="form-group">
                <label >File Size</label>
                <input type="text" class="form-control" value="{{ round($file->file_size / 1024??0) }} KB" disabled>
            </div>
            <div class="form-group">
                <label >Uploaded By</label>
                <input type="text" class="form-control" value="aalok" disabled>
            </div>
            <div class="form-group">
                <label >Uploaded At</label>
                <input type="text" class="form-control" value="{{ $file->created_at->format('Y M, d') }}" disabled>
            </div>
       </div>
      <div class="modal-footer justify-content-between">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
      </div>
    </div>
  </div>
