<?php

namespace App\Http\Controllers\Api;

use App\Coupon;
use App\Http\Controllers\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use ApiResponse;

    public function get(Request $request){
        $limit = 5;
        $page = 0;
        if(isset($request->page) && !empty($request->page)){
            $page = $request->page;
        }
        if(isset($request->limit) && !empty($request->limit)){
            $limit = $request->limit;
        }
        $payment = Coupon::select('amount','type','status','expiration')->where('user_id', $this->user_id)
            ->orderBy('id', 'desc')
            ->offset($page)->limit($limit)
            ->get()->toArray();
        return $this->successData($payment);
    }
}
