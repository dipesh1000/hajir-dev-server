<?php

namespace Candidate\Repositories\auth;


interface AuthCandidateInterface
{

    public function register($request);

    public function verifyOtp($request);

    public function passwordSubmit($request);

    public function changePhone($request);

}
