<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Helpers\ApiResponse;
use App\Port;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    use ApiResponse;

    /**
     * 返回主页所需数据
     * 1、用户余额
     * 2、剩余流量和总流量
     * 3、ip、端口、状态、密码
     * 4、二维码（可以保存图片），后台返回链接地址
     * 5、影梭的iOS和安卓下载链接
     * @param Request $request
     */
    public function index(Request $request)
    {
        $ss = Port::getByUser($this->user_id);
        $data = [
            'ss'=>$ss,
            'link'=>[
                'android'=>'',
                'ios'=>''
            ]
        ];
        return $this->success('',$data);
    }

    public function changePortPassword(Request $request){
        $messages = [
            'port.required' => '请选择端口',
            'password.required'  => '请输入修改后的密码',
            'password.min'  => '密码不能少于6位',
            'password.max'  => '密码不能超过10位'
        ];
        $rules = [
            'port'   => 'required',
            'password'   => 'required|string|min:6|max:10'
        ];
        $this->validate($request, $rules, $messages);
        if(Port::where([['user_id',$this->user_id],['port',$request->port]])->doesntExist()){
            return $this->error('非法端口');
        }
        try{
            if(!SsServer::send('remove',['server_port'=>$request->port])){
                throw new \Exception('修改失败');
            }
            if(!SsServer::send('add',['server_port'=>$request->port,'password'=>$request->password])){
                SsServer::send('remove',['server_port'=>$request->port]);
                throw new \Exception('修改失败');
            }
            Port::where('port',$request->port)->update(['password' => $request->password]);
            return $this->success('修改成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
