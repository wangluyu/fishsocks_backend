<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    public static function getPayment($user_id,$page,$limit){
        $payment = DB::table('payments')
            ->select('payments.id','ports.ip','ports.port','coupons.amount as coupon_amount','coupons.type as coupon_type','payments.amount','payments.type','payments.status','payments.created_at')
            ->leftjoin('coupons',function ($join){
                $join->on('payments.coupon_id','=','coupons.id');
            })
            ->leftjoin('ports',function ($join){
                $join->on('payments.port_id','=','ports.id');
            })
            ->where('payments.user_id',$user_id)
            ->offset($page)->limit($limit)
            ->orderBy('payments.id', 'desc')
            ->get()->toArray();
        return $payment;
    }
}
