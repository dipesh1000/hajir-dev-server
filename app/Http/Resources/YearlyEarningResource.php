<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YearlyEarningResource extends JsonResource
{
   
    public function toArray($request)
    {
       
        return [
            [
                'month' => "Jan",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Feb",
                'status' => "hold",
                "amount" => 15000 . '/'
            ],
            [
                'month' => "Apr",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "May",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Jun",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Jul",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Aug",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Sep",
                'status' => "unpaid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Nov",
                'status' => "unpaid",
                "amount" => 20000 . '/'
            ],
            [
                'month' => "Dec",
                'status' => "paid",
                "amount" => 20000 . '/'
            ],

        ];
    }


    private function checkMonth($request)
    {
        switch ($this->month) {
            case ('January'):
                return "JAN";
                break;

            case ('February'):

                return "FEB";
                break;
            case ('February'):

                return "FEB";
                break;

            default:
                $msg = 'Something went wrong.';
        }
    }


    private function checkEarning($request)
    {
        if ($this->month == "January") {
            if ($this->total_earning != null) {
                return $this->total_earning;
            }
            return 0;
        }
    }
}
