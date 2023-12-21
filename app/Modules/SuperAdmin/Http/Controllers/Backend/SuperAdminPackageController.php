<?php

namespace SuperAdmin\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use SuperAdmin\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use SuperAdmin\Http\Requests\PackageStoreRequest;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminPackageController extends Controller
{
    public $response;
    
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function index(){
        return view('SuperAdmin::backend.package.index');
    }

    public function getPackageData(Request $request){
        try {
            if($request->ajax()) {
                $data = Package::query();
                return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price',function($row){
                    return "Rs ".number_format( $row->price,2);
                })
                ->editColumn('status',function($row){
                    if($row->status == "Active"){
                        return '<div class="badge badge-success">'.$row->status.'</div>';
                    }else{
                        return '<div class="badge badge-danger">'.$row->status.'</div>';
                    }
                })
                ->addColumn('action',function($row){
                    $actionBtn = '<a href="'. route('backend.package.edit',$row->slug) .'" class="btn btn-info btn-sm" title="Edit"><i
                                class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" id="'. route('backend.package.destroy',$row->slug) .'" data-id='.$row->slug.' class="delete btn btn-danger btn-sm" title="Delete"><i
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

    public function create(){
        try{
            return view('SuperAdmin::backend.package.create');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function store(PackageStoreRequest $request ){
        try {
            $package = new Package();
            $package->title = $request->title;
            $package->status = $request->status;
            $package->price = $request->price;
            $package->remarks = $request->remarks;
            $package->feature = $request->feature;
            if($package->save() == true){
                Toastr::success('Successfully Stored!!');
                return redirect()->back();
            }
            Toastr::error("Something Went Wrong While Saving. Please Try Again.");
            return redirect()->back();
                
        } catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($slug){
        try{
            $package = Package::where('slug',$slug)->first();
            if($package){
                return view('SuperAdmin::backend.package.edit',compact('package'));
            }
            Toastr::error("Package Not Found");
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request,$slug ){
        try {
            $package = Package::where('slug',$slug)->first();
            if($package){
                $package->title = $request->title;
                $package->status = $request->status;
                $package->price = $request->price;
                $package->remarks = $request->remarks;
                $package->feature = $request->feature;
                if ($package->update() == true) {
                    Toastr::success('Successfully Updated!!');
                    return redirect()->back();
                }
                Toastr::error("Something Went Wrong While Updating. Please Try Again.");
                return redirect()->back();      
            }
            Toastr::error("Package Not Found");
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($slug){
        try{
            $package = Package::where('slug',$slug)->first();
            if($package){
                $leaveTypeDelete=$package->delete();
                if($leaveTypeDelete == true){
                    return $this->response->responseSuccessMsg('Successfully Deleted');
                }
                return $this->response->responseError('Can not be Deleted');
            }
            return $this->response->responseError('Package Not Found');        
        }catch(Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }
}
