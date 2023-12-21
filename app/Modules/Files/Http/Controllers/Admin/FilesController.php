<?php

namespace Files\Http\Controllers\Admin;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Files\Models\UploadFile;
use Files\Repositories\FileInterface;
use Illuminate\Http\Request;

class FilesController extends Controller{

    protected $files, $response;

    public function __construct(FileInterface $files, ResponseService $response)
    {
        $this->files = $files;
        $this->response = $response;
    }

    public function index(Request $request){
        try{

            $files = $this->files->getFiles($request);
            return view('Files::admin.index', compact('files'));

        }catch (\Exception $e){
            Toastr::success($e->getMessage(), 'Error');
            return redirect()->back();
        }

    }

    public function upload(){
        return view('Files::admin.upload');
    }

    public function store(Request $request){

        try{
            $upload = $this->files->store($request);
            return $this->response->responseSuccessMsg("Successfully Added.");

        }catch (\Exception $e){
            return $this->response->responseError($e->getMessage(), 400);
        }
    }

    public function delete(Request $request, $id){

        try{
            $delete = $this->files->delete($id);
            if(!$delete){
                return $this->response->responseError('File not found!', 400);
            }
            return $this->response->responseSuccessMsg("Successfully Deleted.");

        }catch (\Exception $e){
            return $this->response->responseError($e->getMessage(), 400);
        }
    }

    public function view($id){
        try{
            $file = $this->files->showFile($id);
            return view('Files::admin.view', compact('file'));

        }catch (\Exception $e){
            Toastr::success($e->getMessage(), 'Error');
            return redirect()->back();
        }

    }


    public function getUploadFiles(Request $request){
        try{
            $uploadfiles = UploadFile::latest()->paginate(32);
            if($request->ajax()){
                if($request->fileType != null ){
                    $uploadfiles = UploadFile::where('type',$request->fileType)->latest()->paginate(32);
                }
                if($request->search){
                    $uploadfiles = UploadFile::where('title','like','%'.$request->search.'%')->latest()->paginate(32);
                }
            }
            $data = [
                'view' => view('Files::admin.appendDataToModal',compact('uploadfiles'))->render()
            ];
            return $this->response->responseSuccess($data, 'success', 200);


        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

}
