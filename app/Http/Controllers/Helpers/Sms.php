<?php
/**
 * Created by PhpStorm.
 * User: Wongluyu
 * Date: 2018/5/26
 * Time: 2:53
 */

namespace App\Http\Controllers\Helpers;

use Mockery\Exception;
use Qcloud\Sms\SmsSingleSender;
class Sms
{
    private $appid;
    private $appkey;
    private $sender;
    private $sign;

    public function __construct() {
        $this->appid = config('sms.appid');
        $this->appkey = config('sms.appkey');
        $this->sign = config('sms.sign');
        $this->sender = new SmsSingleSender($this->appid, $this->appkey);
    }

    public function sendSingle($phone,$templId,$params){
        try {
            if(!in_array($templId,config('sms.templId'))){
                throw new Exception('模板id不存在');
            }
            $result = $this->sender->sendWithParam("86", $phone, $templId,
                $params, $this->sign, "", "");
            $rsp = json_decode($result,true);
            if($rsp['result'] == 0){
                return true;
            }else{
                return $rsp['errmsg'];
            }
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

}