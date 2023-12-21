<?php

namespace Employer\Repositories\auth;


interface AuthEmployerInterface
{

    public function register($request);

    public function verifyOtp($request);
    
    public function webVerifyOtp($request);

    public function passwordSubmit($request);

    public function changePhone($request);

}
