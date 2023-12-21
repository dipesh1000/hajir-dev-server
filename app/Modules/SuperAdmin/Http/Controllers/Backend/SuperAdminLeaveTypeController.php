<?php

namespace SuperAdmin\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use Employer\Models\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminLeaveTypeController extends Controller
{
    public $response;
    
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function index(){
        return view('SuperAdmin::backend.leaveType.index');
    }

    public function getleaveTypeData(Request $request){
        try {
            if($request->ajax()) {
                $data = LeaveType::query();
                return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status',function($row){
                    if($row->status == "Active"){
                        return '<div class="badge badge-success">'.$row->status.'</div>';
                    }else{
                        return '<div class="badge badge-danger">'.$row->status.'</div>';
                    }
                })
                ->addColumn('action',function($row){
                    $actionBtn = '<a href="javascript:void(0)" id="'. route('backend.leave.type.edit',$row->slug) .'" data-id=' . $row->slug . ' class="edit btn btn-info btn-sm" title="Edit"><i
                                class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" id="'. route('backend.leave.type.destroy',$row->slug) .'" data-id='.$row->slug.' class="delete btn btn-danger btn-sm" title="Delete"><i
                                class="far fa-trash-alt"></i></a>
                              ';
                    return $actionBtn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
            }
        }catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function store(Request $request ){
        try {
            $leaveType = new LeaveType();
            $leaveType->title = $request->title;
            $leaveType->status = $request->status;
            $leaveType->desc = $request->remarks;
            if($leaveType->save() == true){
                return $this->response->responseSuccessMsg('Successfully Stored!!');
            }
            return $this->response->responseError('Something Went Wrong While Saving. Please Try Again.');

        } catch(Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    public function edit($slug){
        try{
            $leaveType = LeaveType::where('slug',$slug)->first();
            if($leaveType){
                return view('SuperAdmin::backend.leaveType.edit',compact('leaveType'));
            }
            return $this->response->responseError('Leave Type Not Found');        

        }catch(Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    public function update(Request $request,$slug ){
        try {
            $leaveType = LeaveType::where('slug',$slug)->first();
            if($leaveType){
                $leaveType->title = $request->title;
                $leaveType->status = $request->status;
                $leaveType->desc = $request->remarks;
                if ($leaveType->update() == true) {
                    return $this->response->responseSuccessMsg('Successfully Updated!!');
                }
                return $this->response->responseError('Something Went Wrong While Updating. Please Try Again.');
            }
            return $this->response->responseError('Leave Type Not Found');        
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function destroy($slug){
        try{
            $leaveType = LeaveType::where('slug',$slug)->first();
            if($leaveType){
                $leaveTypeDelete=$leaveType->delete();
                if($leaveTypeDelete == true){
                    return $this->response->responseSuccessMsg('Successfully Deleted');
                }
                return $this->response->responseError('Can not be Deleted');
            }
            return $this->response->responseError('Leave Type Not Found');        
        }catch(Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

}
