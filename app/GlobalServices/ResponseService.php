<?php


namespace App\GlobalServices;

class ResponseService{

    public function __construct(){

    }

    public function responseError($msg, $code=''){
        return response([
            'status' => 'error',
            'code' => $code,
            'message' => $msg
        ], $code != '' ? $code : 400);
    }

    public function responseSuccess($data=[], $msg='', $code=''){
        return response([
            'status' => 'success',
            'message' => $msg,
            'data' => $data,
        ], $code != '' ? $code : 200);
    }

    public function responseSuccessMsg($msg="", $code=""){
        return response([
            'status' => 'success',
            'message' => $msg,
        ], $code != '' ? $code : 200);
    }


    public function abcResources($data, $code){
        return response([
            'status' => 'success',
            'data'=>$data
        ], $code != '' ? $code : 200);


    }

}
