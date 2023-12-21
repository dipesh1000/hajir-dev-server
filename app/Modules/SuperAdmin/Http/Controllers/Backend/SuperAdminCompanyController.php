<?php

namespace SuperAdmin\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Employer\Models\Company;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminCompanyController extends Controller
{
    public $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }
   
    public function index(){
        try{
            return view('SuperAdmin::backend.company.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show($slug){
        try{
            $company = Company::where('slug', $slug)->with('employer','candidates')->first();
            return view('SuperAdmin::backend.company.show',compact('company'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id){
        try{
            $company = Company::where('id', $id)->first();
            if($company->delete() == true){
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

    public function getCompanyData(Request $request){
        try {
            if ($request->ajax()) {
                $data = Company::query()->with('employer','candidates');
                    return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('employer',function($row){
                        return $row->employer ? $row->employer->name : 'No Employer';
                    })
                    ->editColumn('candidate_count',function($row){
                        return $row->candidates->count();
                    })
                    ->editColumn('status',function($row){
                        $main = '<select name="status" class="form-control clientStatus" data-id='.$row->id.'>';
                        $mainlast = '</select>';
                        $activeSelected = '<option  value="Active" selected>Active</option>
                                            <option  value="Inactive">Inactive</option>';
                        $inactiveSelected = '<option  value="Inactive">Active</option>
                                            <option  value="Inactive" selected>Inactive</option>';

                        if($row->status == "Active"){
                            return $main.$activeSelected.$mainlast;
                        }else{
                            return $main.$inactiveSelected.$mainlast;
                        }
                    })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a class="btn btn-primary btn-sm" href="'. route('backend.company.show',$row->slug) .'">View</a>
                   ';
                    return $actionBtn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
            }
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
