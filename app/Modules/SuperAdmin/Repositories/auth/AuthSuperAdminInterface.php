<?php

namespace SuperAdmin\Repositories\auth;


interface AuthSuperAdminInterface
{

    public function register($request);

    public function verifyOtp($request);

    public function passwordSubmit($request);

    public function login($request);

}
