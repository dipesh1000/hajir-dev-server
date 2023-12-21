<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Brian2694\Toastr\Facades\Toastr;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Employer\Http\Requests\CandidateWebStoreRequest;
use Employer\Models\Company;
use Employer\Repositories\candidate\CandidateInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EmployerCandidateController extends Controller
{
    protected $candidate, $response, $file;
    public function __construct(CandidateInterface $candidate, ResponseService $response, FileInterface $file)
    {
        $this->candidate = $candidate;
        $this->response = $response;
        $this->file = $file;
    }

    public function create($company_slug){
        try{
         
            $company = Company::where('slug', $company_slug)->where('employer_id',Auth::guard('web')->id())->first();

            if($company){
                $code = null;
                $str = $company->name;
                $words = explode(' ', $str);
                if(count($words)>1){
                    $initial = $words[0][0]. $words[1][0];
                }
                else{
                    $initial = $words[0][0];
                }
                
                if($company->code == 1){
                    $code = $initial.'-'.rand(0000, 9999);
                }
            }
            return view('Employer::backend.candidate.create',compact('company','code'));
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request,$company_id){
        try{
            // dd($request->all());
            $company = Company::where('id', $company_id)->first();
            if ($company) {
                $candidate = $this->candidate->store($request,$company_id);
                if($candidate == true){
                    Toastr::success(" Candidate Details Successfully Saved.");
                    return redirect()->route('employer.company.viewEmployees',$company->slug);
                }
                Toastr::error("Something Went Wrong While Saving Candidate.");
                return redirect()->back();
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
            
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id){
        try{
            $companyCandidate = CompanyCandidate::where('id', $id)->with('candidate')->first();  
            if($companyCandidate){
                return view('Employer::backend.candidate.edit',compact('companyCandidate'));
            } 
            Toastr::error("Data Not Found.");
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $company_candidate_id){
        try{
            $companyCandidate = CompanyCandidate::where('id',$company_candidate_id)
                                ->with('company')
                                ->first();
            if($companyCandidate){
                $company_id = $companyCandidate->company_id;
                $candidate_id = $companyCandidate->candidate_id;
                $candidate = $this->candidate->update($request,$company_id,$candidate_id);
                if($candidate == true){
                    Toastr::success(" Candidate Details Successfully Saved.");
                    return redirect()->route('employer.company.viewEmployees',$companyCandidate->company->slug);
                }
                Toastr::error("Something Went Wrong. Please Try Again.");
                return redirect()->back();
            }
            Toastr::error("Data Not Found.");
            return redirect()->back();            
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function show($id){
        try{
            $candidate = CompanyCandidate::where('id', $id)->with('candidate')->first();  
            if($candidate){
                return view('Employer::backend.candidate.show',compact('candidate'));
            } 
            Toastr::error("Data Not Found.");
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id){
        try{
            $companyCandidate = CompanyCandidate::where('id', $id)->first();  
            if($companyCandidate){
                $companyCandidate->attendaces()->delete();
                Leave::where('company_id', $companyCandidate->company_id)
                        ->where('candidate_id', $companyCandidate->candidate_id)->delete();
                if($companyCandidate->delete() == true){
                    Toastr::success('Successfully Deleted.');
                    return redirect()->back();
                }
            }
            Toastr::error("Data Not Found.");
            return redirect()->back();
        }catch(Exception $e){
             Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    
    public function getCandidateData(Request $request,$company_id){
        try {
          
            if ($request->ajax()) {       
                $data = CompanyCandidate::where('company_id',$company_id)->with('candidate');
                if($request->type){
                    $data=$data->where('status',$request->type);  
                }
                        return DataTables::of($data)
                            ->addIndexColumn() 
                            ->addColumn('candidate_firtname', function ($row) {
                                return $row->candidate->name ?? '';
                            })

                    ->editColumn('status',function($row){
                        $main = '<select name="status" class="form-control companyCandidateStatus" data-id='.$row->id.'>';
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
                    $attendance='';
                    $invitationSent = Invitation::where('candidate_id',$row->candidate_id)
                                        ->where('company_id',$row->company_id)->first();           
                    $actionBtn = '
                            <a class="btn btn-primary btn-table" href="'. route('employer.candidate.show',$row->id) .'">View</a>
                            <a class="btn btn-warning btn-table" href="'. route('employer.candidate.edit',$row->id) .'">Edit</a>
                            <a class="btn btn-danger btn-table deleteCompantCandidate" data-href="'. route('employer.candidate.destroy',$row->id) .'">Delete</a> 
                        ';                    
                    $invitation = '<form action="'.route('employer.candidate.sendInvitation',$row->company_id).'" method="POST">
                                    <input type="hidden" name="_token" value="'.@csrf_token().'">
                                    <input type="hidden" name="candidate_id" value="'.$row->candidate_id.'">
                                        <button class="btn btn-secondary btn-table mt-2">Send Invitation</button>  
                                    </form>';
                    if($invitationSent){
                        if($invitationSent->status == 'Approved'){
                            $invitation = '<span class="badge badge-success mt-2">Invitation Approved</span>';
                            $attendance = '<a class="btn btn-secondary btn-table mt-2" href="'. route('employer.company.candidateDailyAttendanceReport',[$row->company_id,$row->candidate_id]) .'">Attendance Reports</a>';
                        }elseif($invitationSent->status == 'Decline'){
                            $invitation = '<span class="badge badge-success mt-2">Invitation Declined</span>';
                        }else{
                            $invitation = '<span class="badge badge-info mt-2">Invited</span>';
                        }
                    }
                    return $actionBtn.$attendance.$invitation;
                })
                ->rawColumns(['action','status'])
                ->make(true);
            }
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function statusChange($company_candidate_id, Request $request){
        try{
            if ($request->ajax()) {
                $companyCandidate = CompanyCandidate::where('id', $company_candidate_id)
                                    ->first();
                if($companyCandidate){
                    $companyCandidate->status = $request->status;
                    $companyCandidate->update();
                    return $this->response->responseSuccessMsg("Status Successfully Changed To ".$companyCandidate->status);
                }
            }
        }catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function sendInvitation(Request $request, $company_id)
    {
        try {
            // dd($request->all());
            $user_id = Auth::guard('web')->id();
            $invitation = new Invitation();
            $invitation->employer_id = $user_id;
            $invitation->candidate_id = $request->candidate_id;
            $invitation->status = "Not-Approved";
            $invitation->company_id = $company_id;

            if ($invitation->save() == true) {
                CompanyCandidate::updateOrCreate([
                    'company_id' => $company_id,
                    'candidate_id' => $request->candidate_id
                ], [
                    'invitation_id' => $invitation->id
                ]);
                Toastr::success('Invitation Send Successfully.');
                return redirect()->back();
            }
            Toastr::error('Something Went Wrong While Sending Invitation. Please Try Again..');
            return redirect()->back();
           
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }
}
