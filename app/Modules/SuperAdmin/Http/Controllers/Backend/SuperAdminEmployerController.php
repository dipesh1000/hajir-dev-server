<?php

namespace SuperAdmin\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Employer\Models\Company;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminEmployerController extends Controller
{
    public $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }
   
    public function index(){
        try{
            return view('SuperAdmin::backend.employer.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show($id){
        try{
            $employer = User::employers()->where('id', $id)
                    ->with('employerCompany')->first();

            return view('SuperAdmin::backend.employer.show',compact('employer'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id){
        try{
            $employer = User::employers()->where('id', $id)->first();
            if($employer->delete() == true){
                Toastr::success('Successfully Deleted.');
                return redirect()->back();
            }
            Toastr::error("Something Went Wrong");
            return redirect()->back();
        }catch(Exception $e){
             Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    
    public function getEmployerData(Request $request){
        try {
            if ($request->ajax()) {
                $data = User::employers()->with('employerCompany');
                // dd($data);
                return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('company',function($row){
                    return $row->employerCompany ? $row->employerCompany->count() : 'No Company';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a class="btn btn-primary btn-sm" href="'. route('backend.employer.show',$row->id) .'">View</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

}
