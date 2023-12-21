<?php

namespace SiteSetting\Http\Controllers\Api;

use App\GlobalService\ResponseService;
use App\Http\Controllers\Controller;
use Exception;
use SiteSetting\Http\Resources\SiteSettingResource;
use SiteSetting\Models\SiteSetting;

class SiteSettingApiController extends Controller
{
    protected $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function getSiteDetails(){
        try{
            $details = SiteSetting::whereIn('key',['title','primary_email','secondary_email',
                        'address','primary_phone','secondary_phone','site_description','about_us','whatsapp',
                        'facebook_link','twitter_link','instagra_link','about_us_image'])->select('key','value')
                        ->get();

                        $site_details = [];
                        foreach($details as $detail){
                            if($detail->key == 'about_us_image'){
                                $site_details[$detail->key] = getOriginalUrl($detail->value);
                            }else{
                                $site_details[$detail->key] = $detail->value;
                            }

                        }
            return $this->response->responseSuccess([
                'site_details' => $site_details
            ],'Success');

        }catch(Exception $e){
         return $this->response->responseError($e->getMessage());
        }

     }

}
