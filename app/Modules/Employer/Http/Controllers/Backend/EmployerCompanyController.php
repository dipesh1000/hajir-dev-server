<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Candidate\Models\BusinessLeaveday;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Employer\Models\Company;
use Employer\Models\Payment;
use Employer\Repositories\company\CompanyInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class EmployerCompanyController extends Controller
{

    protected $company, $response, $file;
    public function __construct(CompanyInterface $company, ResponseService $response, FileInterface $file)
    {
        $this->company = $company;
        $this->response = $response;
        $this->file = $file;
    }

    public function index(){
        try{
            return view('Employer::backend.company.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function create(){
        try{
            $businessLeaves = BusinessLeaveday::all();
            return view('Employer::backend.company.create',compact('businessLeaves'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request){
        try{
            // dd($request->all());
            $companystore = $this->company->storeWebEmployer($request);
            if($companystore){
                Toastr::success('Successfully Saved.');
                return redirect()->route('employer.company.index');
            }
            Toastr::success('Something Went Wrong. Please Try Again.');
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($slug){
        try{
            $businessLeaves = BusinessLeaveday::all();
            $employer_id = Auth::guard('web')->id();
            $company = Company::where('slug', $slug)
                            ->where('employer_id',$employer_id)
                            ->with('businessLeaves','govLeaves','specialLeaves')->first();
            return view('Employer::backend.company.edit',compact('company','businessLeaves'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $slug){
        try{
            $companystore = $this->company->updateBySlugWebEmployer($request,$slug);
            if($companystore){
                Toastr::success('Successfully Updated.');
                return redirect()->route('employer.company.index');
            }
            Toastr::success('Something Went Wrong. Please Try Again.');
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function show($slug){
        try{
            $employer_id = Auth::guard('web')->id();
            $company = Company::where('slug', $slug)
                        ->where('employer_id',$employer_id)
                        ->with('employer','candidates')
                        ->first();
            return view('Employer::backend.company.show',compact('company'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($slug){
        try{
            $employer_id = Auth::guard('web')->id();
            $company = Company::where('slug', $slug)->where('employer_id',$employer_id)->first();
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
            
            $employer_id = Auth::guard('web')->id();
            if ($request->ajax()) {
                $data = Company::query()->where('employer_id',$employer_id)->with('employer','candidates')->get();
                if($request->type){
                    $data=$data->where('status',$request->type);  
                }
                    return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('employer',function($row){
                        return $row->employer ? $row->employer->name : 'No Employer';
                    })
                    ->editColumn('candidate_count',function($row){
                        return $row->candidates->count();
                    })
                    ->editColumn('status',function($row){
                        $main = '<select name="status" class="form-control clientStatus" data-slug='.$row->slug.'>';
                        $mainlast = '</select>';
                        $activeSelected = '<option  value="Active" selected>Active</option>
                                            <option  value="Inactive">Inactive</option>';
                        $inactiveSelected = '<option  value="Active">Active</option>
                                            <option  value="Inactive" selected>Inactive</option>';

                        if($row->status == "Active"){
                            return $main.$activeSelected.$mainlast;
                        }else{
                            return $main.$inactiveSelected.$mainlast;
                        }
                    })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a class="btn btn-primary btn-table" href="'. route('employer.company.show',$row->slug) .'">View</a>
                        <a class="btn btn-warning btn-table" href="'. route('employer.company.edit',$row->slug) .'">Edit</a>
                        <button type="button" class="btn btn-danger deleteCompany btn-table" data-href="'. route('employer.company.destroy',$row->slug) .'">Delete</button>
                        <a class="btn btn-info btn-table " href="'. route('employer.company.viewEmployees',$row->slug) .'">Employees</a>
                        <a class="btn btn-secondary btn-table mt-2" href="'. route('employer.company.currentDayReport',$row->slug) .'">Attendance</a>
                        <a class="btn btn-secondary btn-table mt-2" href="'. route('employer.company.dailyOverAllReport',$row->id) .'">Overall Reports</a>
                        <a class="btn btn-secondary btn-table mt-2" href="'. route('employer.company.monthlyReports',$row->slug) .'">Monthly Reports</a>

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


    public function statusChange($slug, Request $request){
        try{
            if ($request->ajax()) {
                $employer_id = Auth::guard('web')->id();
                $company = Company::where('slug',$slug)->where('employer_id',$employer_id)
                            ->first();
                if($company){
                    $company->status = $request->status;
                    $company->update();
                    return $this->response->responseSuccessMsg("Status Successfully Changed To ".$company->status);
                }
            }
        }catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function viewEmployees($slug){
        try{
            $company = Company::where('slug',$slug)->select('id','slug','name')->first();
            if($company){
                return view('Employer::backend.candidate.index',compact('company'));
            }
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function monthlyReports($slug, Request $request){
        try{
            $company = Company::where('slug',$slug)->select('id','slug','name','created_at')->first();
            if($company){
                $now = Carbon::now();
                if($request->year && $request->year != $now->format('Y')){
                    $startDate = $request->year."-01-01";
                    $endDate = Carbon::parse($startDate)->endOfYear()->format('Y-m-d');
                }
                else{
                    $startDate = $now;
                    $endDate = $startDate->format('Y-m-d');
                }
                if($request->month){
                    $month = Carbon::parse($request->month)->format('m');
                    $activeMonth = $request->month;
                    $dateString = $request->month;
                    $date = Carbon::createFromFormat('M-y', $dateString)->startOfMonth();
                    $year = $date->format('Y');
                    // dd($year,$month,$request->month);
                }else{
                    if($request->year && $request->year != Carbon::now()->format('Y')){
                        $todayDate = Carbon::parse($request->year."-01-01");
                    }else{
                        $todayDate = $now;
                    }
                    $month =$todayDate->format('m');
                    $year =$todayDate->format('Y');
                    $activeMonth = $todayDate->format('M-y');
                }
                
                $months = $this->getAllCompanyCandidateMonths($startDate,$endDate); 
                $years = $this->getAllCompanyCandidateYears($company); 
                $payments = Payment::where('company_id',$company->id)
                                    ->whereYear('payment_for_month','=',$year)
                                    ->whereMonth('payment_for_month','=',$month)
                                    ->with('candidate')->get();
                // dd($payments);
                                    
                return view('Employer::backend.company.monthlyReport',compact('company','payments','months','years','activeMonth'));
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function getAllCompanyCandidateMonths($startDate,$endDate)
    {
        try {
            $start    = (new DateTime(Carbon::parse($startDate)->startOfYear()->format('y-m-d')));
            $endDate    = (new DateTime(Carbon::parse($endDate)->format('y-m-d')));
            $interval = DateInterval::createFromDateString('1 month'); // 1 month interval
            $period   = new DatePeriod($start, $interval,$endDate);
            $months = [];
            foreach ($period as $dt) {
                $months[] = $dt->format("M-y");
            }
            return $months;           
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function getAllCompanyCandidateYears($company)
    {
        try {
            $end  =  Carbon::now()->addYear()->format('Y-m-d');
            $years = [];
            for ($date = $company->created_at->copy(); 
                    $date < ($end); $date->addYear()) {
                $years[] = $date->format('Y');
            }
            return $years;         
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


}
