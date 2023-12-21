@if (isset($uploadfiles) && $uploadfiles->isNotEmpty())
    <ul>
        @foreach ($uploadfiles as $file)
            @if ($file)
                @php
                    if ($file->type == 'image') {
                        $thumbFileUrl = Storage::url('resize/' . $file->path);
                    } else {
                        $thumbFileUrl = asset('image/file.png');
                    }
                    
                @endphp
                <li class="libraryfilediv" data-file-id="{{ $file->id }}" data-file-type="{{ $file->type }}"
                    data-file-size="{{ $file->file_size }}" data-file-extension="{{ $file->extension }}"
                    data-file-uploadat="{{ $file->updated_at }}" data-file-path="{{ $thumbFileUrl }}">
                    <img src="{{ $thumbFileUrl }}" >
                </li>
            @else
                <div class="mb-4 text-center">
                    <h2>No Files Found</h2>
                </div>
            @endif
        @endforeach
    </ul>
    <div class="mt-2">
        {{ $uploadfiles->links('vendor.pagination.default') }}
    </div>
@else
    <div class="mb-4 text-center">
        <h2>No Files Found</h2>
    </div>

@endif
