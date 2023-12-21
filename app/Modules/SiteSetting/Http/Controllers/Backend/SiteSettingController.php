<?php

namespace SiteSetting\Http\Controllers\Backend;

use Exception;
use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Files\Repositories\FileInterface;
use SiteSetting\Http\Requests\SiteSettingRequest;
use SiteSetting\Models\SiteSetting;
use Spatie\Analytics\AnalyticsClientFactory;
use Spatie\Analytics\Period;



class SiteSettingController extends Controller
{
    protected $response, $file;

    public function __construct(ResponseService $response, FileInterface $file)
    {
        $this->response = $response;
        $this->file = $file;
    }


    public function create(){
        try{
            return view('SiteSetting::backend.create');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function siteSettingSubmit(SiteSettingRequest $request){
        try{

            $inputs = $request->all();
            foreach ($inputs as $inputKey => $inputValue) {
                if ( $inputKey == 'logo') {
                    $sitesetting = SiteSetting::where('key', '=', $inputKey)->first();

                    if($sitesetting)
                    {
                        if(file_exists($sitesetting->value)){
                            unlink($sitesetting->value);
                        }
                    }
                    $uploaded = $this->file->storeFile($request->logo);
                    if ($uploaded) {
                        $imageid = $uploaded->id;
                    }

                    $inputValue = $imageid;
                }

                if ( $inputKey == 'favicon') {
                    $sitesetting = SiteSetting::where('key', '=', $inputKey)->first();

                    if($sitesetting)
                    {
                        if(file_exists($sitesetting->value)){
                            unlink($sitesetting->value);
                        }
                    }
                    $uploaded = $this->file->storeFile($request->favicon);
                    if ($uploaded) {
                        $favid = $uploaded->id;
                    }
                    $inputValue =  $favid;
                }

                if ( $inputKey == 'chairperson_sign') {
                    $sitesetting = SiteSetting::where('key', '=', $inputKey)->first();

                    if($sitesetting)
                    {
                        if(file_exists($sitesetting->value)){
                            unlink($sitesetting->value);
                        }
                    }
                    $uploaded = $this->file->storeFile($request->chairperson_sign);
                    if ($uploaded) {
                        $sign = $uploaded->id;
                    }
                    $inputValue = $sign;
                }

                $sitesubmit = SiteSetting::updateOrCreate(['key'=>$inputKey],[
                    'value' => $inputValue,
                ]);

            }
            if($sitesubmit){
                Toastr::success('Successfully Updated');
                return redirect()->back();
            }
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }

    }


}
