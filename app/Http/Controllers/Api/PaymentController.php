<?php

namespace App\Http\Controllers\Api;

use App\Coupon;
use App\Flow;
use App\Http\Controllers\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\helper;
use App\Http\Middleware\RefreshToken;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
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
        $payment = Payment::getPayment($this->user_id,$page,$limit);
        return $this->successData($payment);
    }

    public function pay(Request $request){
        $messages = [
            'port_id.required'  => '请选择端口',
            'flow.required'  => '请输入要购买的流量数',
        ];
        // 验证规则，使用手机号码登录
        $rules = [
            'port_id' => 'required',
            'flow' => 'required',
        ];

        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $this->validate($request, $rules, $messages);
        DB::beginTransaction();
        try {
            $payment = new Payment();
            $payment->user_id = $this->user_id;
            $payment->port_id = $request->port_id;
            $payment->flow = helper::GBToByte($request->flow);
            $amount = helper::flowToMoney($request->flow);
            //是否使用代金券
            if(!empty($request->coupon_id)){
                $coupon = Coupon::where(array(array('status',0),array('user_id',$this->user_id)))->find($request->coupon_id);
                if(!empty($coupon)){
                    $payment->coupon_id = $coupon->id;
                }
                switch ($coupon['type']){
                    case 1:
                        $amount -= $coupon['amount'];
                        break;
                    case 2:
                        $amount *= ($coupon['amount']/10);
                        break;
                }
                //如果不需要付款
                if($amount <= 0){
                    $amount = 0;
                    $payment->status = 1;
                    $payment->type = 4;
                    $flow = new Flow();
                    $flow->port_id = $request->port_id;
                    $flow->flow = helper::GBToByte($request->flow);
                    $flow->type = 1;
                    $flow->month = date('Ym');
                    $flow->day = date('Ymd');
                    $flow->save();
                }
                $coupon->status = 1;
                $coupon->save();
            }
            $payment->amount = $amount;
            $payment->save();
            DB::commit();
            return $this->success('提交成功，请在今日之内缴费，否则此申请作废');
        }catch (Exception $e){
            DB::rollback();
            return $this->error('提交失败，请稍后重试',$e->getMessage());
        }
    }
}
