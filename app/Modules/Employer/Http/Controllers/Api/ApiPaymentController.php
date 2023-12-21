<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Employer\Models\Payment;

class ApiPaymentController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function paymentStore(Request $request, $company_id, $employer_id)
    {
        try{
            $payment = new Payment();
            $payment->status = 'Paid';
            $payment->paid_amount = $request->amount;
            $payment->payment_date = today();
            $payment->payment_for_month = Carbon::parse($request->month_date);
            $payment->company_id = $company_id;
            $payment->employer_id = $employer_id;
            if($payment->save() == true){
                return $this->response->responseSuccessMsg("Successfully Saved.",200);
            }
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }
}
