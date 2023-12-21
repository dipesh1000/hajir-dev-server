<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\http\Controllers\Controller;
use Employer\Http\Resources\PackageResource;
use SuperAdmin\Models\Package;

class ApiPackageController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
    $this->response = $response;
    }

    public function index()
    {
        try{
            $packages = Package::active()->latest()->get();
            $data = [
                'packages' => PackageResource::collection($packages)
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

   
}
