<?php

namespace Employer\Repositories\candidate;


interface CandidateInterface
{


    public function store($request, $id);

    public function update($request, $company_id, $candidate_id);

    public function getCandidatesByCompany($id);


}
