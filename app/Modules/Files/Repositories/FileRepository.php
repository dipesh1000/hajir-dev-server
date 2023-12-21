<?php

namespace Files\Repositories;

use Files\Models\UploadFile;
use Illuminate\Support\Facades\Storage;

class FileRepository implements FileInterface
{

    public static function resizeImage($file, $fileExtension = null)
    {
        if ($file) {
            $height = 250;
            $width = 250;
            $time = time();
            $define_path = 'upload/' . date('y/') . date('m');
            $filename = $time . $file->getClientOriginalName();
            $originalPath = $file->storeAs($define_path, $filename);

            $supportExtension = array('jpg', 'png', 'gif', 'webp');
            if (in_array($fileExtension, $supportExtension)) {
                $define_path = 'upload/' . date('y/') . date('m/');
                $ImageUpload = \Image::make($file);
                $thumbnailPath = '/resize/' . $define_path . $filename;
                $ImageUpload->resize($height, $width, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $ImageUpload->resizeCanvas($height, $width, 'center', false, 'fff');

                Storage::put(
                    $thumbnailPath,
                    $ImageUpload->stream()
                );
            }



            return $originalPath;
        }

        return false;
    }




    public function storeFile($file)
    {
        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
            "mp4" => "video",
            "mpg" => "video",
            "mpeg" => "video",
            "webm" => "video",
            "ogg" => "video",
            "avi" => "video",
            "mov" => "video",
            "flv" => "video",
            "swf" => "video",
            "mkv" => "video",
            "wmv" => "video",
            "wma" => "audio",
            "aac" => "audio",
            "wav" => "audio",
            "mp3" => "audio",
            "zip" => "archive",
            "rar" => "archive",
            "7z" => "archive",
            "doc" => "document",
            "txt" => "document",
            "docx" => "document",
            "pdf" => "document",
            "csv" => "document",
            "xml" => "document",
            "ods" => "document",
            "xlr" => "document",
            "xls" => "document",
            "xlsx" => "document"
        );

        if ($file) {
            $fileName = $file->getClientOriginalName();
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $basename = pathinfo($fileName, PATHINFO_FILENAME);
            $fileSize = filesize($file);

            $upload = new UploadFile();
            $upload->title = $basename;
            $upload->path = $this->resizeImage($file, $fileExtension);
            $upload->user_id = 1;
            $upload->file_size = $fileSize;
            $upload->extension = $fileExtension;
            $upload->type = $type[$fileExtension];
            $upload->save();

            return $upload;
        }
    }


    public function store($request)
    {
        return $this->storeFile($request->file);
    }




    public function getFiles($request)
    {
        $files = UploadFile::orderBy('id', 'DESC')->paginate(18);
        return $files;
    }

    public function delete($id)
    {
        $file = UploadFile::where('id', $id)->first();
        if ($file) {
            $orginalPath =  $file->path;
            $thumbnailPath = 'resize/' . $file->path;
            Storage::delete([$orginalPath, $thumbnailPath]);
            $file->delete();
            return true;
        }
        return false;
    }

    public function showFile($id)
    {
        $file = UploadFile::where('id', $id)->first();
        return $file;
    }
}
