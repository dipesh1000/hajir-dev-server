<div class="row">
    @if(isset($files))
        @foreach ($files as $file)
            <div class="col-xl-2 col-md-3 col-sm-4 mb-4">
                <div class="card component-card_2">
                    <div class="dropdown d-inline-block more-actions" style="position: absolute;right:0px;">
                        <a class="nav-link dropdown-toggle" id="more-actions-btns-dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="more-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="more-actions-btns-dropdown" style="will-change: transform;">
                            <a class="dropdown-item action-view" data-id="{{ $file->id }}" href="javascript:void(0);">
                                <i data-feather="eye"></i> View
                            </a>
                            <a class="dropdown-item action-delete" data-id="{{ $file->id }}" href="javascript:void(0);">
                                <i data-feather="trash-2"></i> Trash
                            </a>
                        </div>
                    </div>
                    <div style="height:150px;overflow:hidden;">
                        @if($file->type == "image")
                        <img src="{{ thumbnail_url($file) }}" class="card-img-top" alt="widget-card-2">
                        @else
                        <svg style="width: 100%;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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
                    <div class="card-body">
                        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($file->title??'', 10) }}</h5>
                        <p class="card-text">{{ round($file->file_size / 1024??0) }} KB</p>
                    </div>
                </div>
            </div>
        @endforeach

    @endif
</div>