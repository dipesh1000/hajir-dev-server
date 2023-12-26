<?php

use App\Model\MemberOrganizationInfo;
use Candidate\Models\CompanyCandidate;
use CMS\Models\PointInfo;
use SiteSetting\Models\SiteSetting;
use Files\Models\UploadFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Membership\Models\UserPointReduction;
use PublicOpinion\Models\PublicOpinionVote;
use User\Models\UserInfo;
use Vendor\Models\Booking;

function seperator($depth)
{
    $space = '';
    for ($i = 1; $i < $depth; $i++) {
        $space .= '-';
    }
    return $space;
}


function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
    return array_map(
        function ($timestamp) use ($format) {
            // dd($timestamp);
            return date($format, $timestamp);
        },
        range(strtotime($start) + ($start < $end ? 4000 : 8000), strtotime($end) + ($start < $end ? 8000 : 4000), 86400)
    );
}







function getdeliverydetails($userid = null)
{
    if ($userid != null) {
        $userinfo = UserInfo::where('user_id', $userid)->pluck('value', 'key')->toArray();
        if ($userinfo != null) {
            return $userinfo;
        }
        return false;
    }
    return false;
}


function getSiteSetting($key)
{
    $config = SiteSetting::where('key', '=', $key)->first();
    if ($config != null) {
        return $config->value;
    }
    return null;
}


function totalVoteUsers()
{
    $totalvoteusers = PublicOpinionVote::groupBy('user_id')->count();
    return $totalvoteusers;
}


function returnUserDetail($key = null, $userid = null)
{
    if ($key != null && $userid != null) {
        $userinfo = UserInfo::where('user_id', $userid)->where('key', $key)->first();
        if ($userinfo) {

            return $userinfo->value;
        }
    }
    return null;
}


function returnSiteSetting($key = null)
{
    if ($key != null) {
        $sitesetting = SiteSetting::where('key', $key)->first();
        if ($sitesetting) {

            return $sitesetting->value;
        }
    }
    return null;
}


function vendorBookingApprovals()
{
    if (Auth::check()) {
        $bookings = Booking::where('vendor_id', Auth::id())
            ->with(['venues', 'applications'])->latest()->get();
        if (!is_null($bookings)) {
            return $bookings;
        }
        return false;
    }
    return false;
}



function ProductRating($rating)
{
    if ($rating->count() > 0) {
        return $rating->sum('rating') / $rating->count();
    } else {
        return 0;
    }
}

function thumbnail_url($file)
{
    $supportExtension = array('jpg', 'png', 'gif', 'webp');
    if (in_array($file->extension, $supportExtension)) {
        return Storage::url('resize/' . $file->path);
    } else {
        return Storage::url($file->path);
    }

    return null;
}

function getThumbnailUrl($id)
{
    $file = UploadFile::where('id', $id)->first();
    if ($file) {
        $supportExtension = array('jpg', 'png', 'gif', 'webp');
        if (in_array($file->extension, $supportExtension)) {
            return Storage::url('resize/' . $file->path);
        } else {
            return Storage::url($file->path);
        }
    }
    return null;
}


function getOrginalUrl($id)
{
    $file = UploadFile::where('id', $id)->first();
    if ($file) {
        return Storage::url($file->path);
    }
    return null;
}


function getFileTitle($id)
{
    $file = UploadFile::where('id', $id)->first();
    if ($file) {
        return $file->title;
    }
    return null;
}

function getFilePath($id)
{
    $file = UploadFile::where('id', $id)->first();
    if ($file) {
        return $file->path;
    }
    return null;
}

function original_url($file)
{
    $supportExtension = array('jpg', 'png', 'gif', 'webp');
    if (in_array($file->extension, $supportExtension)) {
        return Storage::url($file->path);
    } else {
        return Storage::url($file->path);
    }

    return null;
}


function returnImage($image, $path)
{
    if (File::exists($path)) {
        File::delete($path);
    }
    $requestedimage = $image;
    $imagename = time() . str_replace(" ", "", $requestedimage->GetClientOriginalName());
    $path = public_path('image/product');

    $requestedimage->move($path, $imagename);
    return 'image/product/' . $imagename;
}

function returnBrandBanner($image, $path)
{

    if (File::exists($path)) {
        File::delete($path);
    }
    $requestedimage = $image;
    $imagename = time() . str_replace(" ", "", $requestedimage->GetClientOriginalName());
    $path = public_path('image/brand');

    $requestedimage->move($path, $imagename);
    return 'image/brand/' . $imagename;
}
function returnCategoryBanner($image, $path)
{

    if (File::exists($path)) {
        File::delete($path);
    }
    $requestedimage = $image;
    $imagename = time() . str_replace(" ", "", $requestedimage->GetClientOriginalName());
    $path = public_path('image/category/banner');
    $requestedimage->move($path, $imagename);
    return 'image/category/banner/' . $imagename;
}
function returnCategoryLogo($image, $path)
{

    if (File::exists($path)) {
        File::delete($path);
    }
    $requestedimage = $image;
    $imagename = time() . str_replace(" ", "", $requestedimage->GetClientOriginalName());
    $path = public_path('image/category/logo');

    $requestedimage->move($path, $imagename);
    return 'image/category/logo/' . $imagename;
}




function returnOrganizationMemberInfo($memberid, $key)
{
    $memberorg_info = MemberOrganizationInfo::where('member_org_id', $memberid)->where('key', $key)->first();
    if ($memberorg_info) {
        return $memberorg_info->value;
    }
    return null;
}



function getFileUrlByUploads($upload = null, $type = null)
{
    $file = $upload;
    if ($file != null) {

        if ($type == "small") {
            $supportExtension = array('jpg', 'png', 'gif', 'webp');
            if (in_array($file->extension, $supportExtension)) {
                return Storage::url('resize/' . $file->path);
            } else {
                return Storage::url($file->path);
            }
        } else {
            return Storage::url($file->path);
        }
    }
    return null;
}


function returnProfileUrl($upload = null, $type = null)
{
    $file = $upload;
    if ($file && $file != null) {
        return url(Storage::url($file->path));
    }
    return "https://assets-prod.sumo.prod.webservices.mozgcp.net/static/default-FFA-avatar.2f8c2a0592bda1c5.png";
}

function checkFileExists($id = null)
{
    $uploadfile = UploadFile::where('id', $id)->first();
    if ($uploadfile) {
        if (Storage::exists($uploadfile->path)) {
            return true;
        }
        return false;
    }
    return false;
}



function checkMemberReduction($propertydetailid = null)
{
    $memberreduction  = UserPointReduction::where('user_id', Auth::id())
        ->where('property_detail_id', $propertydetailid)
        ->first();
    if ($memberreduction) {
        return true;
    }
    return false;
}


function getMemberViewPoint()
{

    $viewPoint = cache()->remember('member-view-point', 60 * 60, function () {

        $pointsInfo = PointInfo::where('role', 'member')->where('type', 'view')->first();
        if ($pointsInfo) {
            return  $pointsInfo->point;
        } else {
            return PointInfo::DEFAULT_VIEW_POINT;
        }
    });

    return $viewPoint;
}





function checkBusinessLeave($include, $array = array())
{
    if (in_array($include, $array)) {
        return "Business Leave";
    }
    return null;
}
function checkSpecialHoliday($include, $array = array())
{
    if (in_array($include, $array)) {
        return "Special Leave";
    }
    return null;
}
function checkGovermentHoliday($include, $array = array())
{
    if (in_array($include, $array)) {
        return "Goverment Leave";
    }
    return null;
}

function checkAttendance($include, $attendances)
{
    if ($attendances) {
        $matchDate = $attendances->where('created_at', $include)
                                    ->where('employee_status','!=', null)
                                    ->first();
        if ($matchDate) {
            return  $matchDate->employee_status;
        }
    }
    return null;
}


function checkIncomeHistoryAttendance($include, $attendances)
{
    if ($attendances) {
        // dd($include);
        // dd($attendances->where('created_at', "2023-02-28 11:58:06"));

        // dd($attendances->where('created_at', 'LIKE', '%'.Carbon\Carbon::parse("2023-02-28").'%'));

        // dd($attendances->where('created_at',Carbon\Carbon::parse("2023-02-28") ));

        $matchDate = $attendances->where('check_date', $include->format('Y-m-d'))
            ->whereIn('employee_status', ['Present', 'Late', 'Leave'])
            ->first();
        // dd($matchDate);
        if ($matchDate) {
            return [
                'status' => $matchDate->employee_status,
                'earning' => $matchDate->earning
            ];
        }
    }
    return null;
}





function checkIncomeHistoryBusinessLeave($include,  $companyid, $candidateid, $array = array())
{
    if (in_array($include, $array)) {
        return [
            'status' => "Business Leave",
            'earning' => getCompanyCandidateEarning($companyid, $candidateid)
        ];
    }
    return null;
}
function checkIncomeHistorySpecialHoliday($include, $companyid, $candidateid, $array = array())
{
    if (in_array($include, $array)) {
        return [
            'status' => "Special Leave",
            'earning' => getCompanyCandidateEarning($companyid, $candidateid)
        ];
    }
    return null;
}
function checkIncomeHistoryGovermentHoliday($include, $companyid, $candidateid, $array = array())
{
    if (in_array($include, $array)) {
        return [
            'status' => "Goverment Leave",
            'earning' => getCompanyCandidateEarning($companyid, $candidateid)
        ];
    }
    return null;
}


function getCompanyCandidateEarning($companyid = null, $candidateid = null)
{
    $totaldays = Carbon\Carbon::today()->daysInMonth;
    $companycandidate = CompanyCandidate::where('company_id', $companyid)
        ->where('candidate_id', $candidateid)
        ->where('verified_status', 'verified')
        ->where('status', 'Active')->first();
    if ($companycandidate) {
        if ($companycandidate->salary_type == "monthly") {
            return $companycandidate->salary_amount / ($totaldays);
        } elseif ($companycandidate->salary_type == "monthly") {
            return $companycandidate->salary_amount / (7);
        } else {
            return $companycandidate->salary_amount;
        }
    }
    return null;
}


function checkPaymentandAttendanceOfCandidate($payments = null, $attendances = null, $month = null)
{
    $status = "unpaid";
    $earning = null;
    if ($month) {

        $checkpayments =  $payments->where('check_month', $month)->first();
        $check_paymentttendances = $attendances->where('check_month', $month);

        if ($checkpayments) {
            $status = $checkpayments->status;
            $earning = $checkpayments->paid_amount;
        } elseif ($check_paymentttendances) {

            $totalunpaidearning = 0;
            foreach ($check_paymentttendances as $paymentattendance) {
                $totalunpaidearning += $paymentattendance->earning ?? 0;
            }
            $earning = $totalunpaidearning;
        }

        return [
            'month' => month($month) ,
            'status' => $status,
            'earning' => $earning
        ];
    }
    return [
        'month' => 1,
        'status' => $status,
        'earning' => 0
    ];
}


function month($month)
{
    $monthlyarray = [
        'Jan' => '1',
        'Feb' => '2',
        'Mar' => '3',
        'Apr' => '4',
        'May' => '5',
        'Jun' => '6',
        'July' => '7',
        'Aug' => '8',
        'Sep' => '9',
        'Oct' => '10',
        'Nov' => '11',
        'Dec' => '12',
    ];
    return array_search($month, $monthlyarray);
}
