<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Helpers\ApiResponse;
use App;
use App\Http\Controllers\Helpers\SsServer;
use App\Port;
use App\User;
use App\VerificationCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Helpers\Sms;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    private $messages = [
        'phone.required' => '请输入手机号',
        'phone.size' => '请填写正确的手机号',
        'name.required'  => '请输入用户名',
        'name.min'  => '用户名的字符不能少于6位',
        'name.max'  => '用户名的字符不能超过20位',
        'password.required'  => '请输入密码',
        'password.min'  => '密码不能少于6位',
        'password.max'  => '密码不能超过20位',
        'code.required'  => '请输入验证码',
        'code.size'  => '请输入4位数验证码',
    ];

    use ApiResponse;

    public function getRegisterCode(Request $request){
        $rules = [
            'phone'   => 'required|string|size:11'
        ];
        $params = $this->validate($request, $rules, $this->messages);
        $phone = $params['phone'];
        $code = new VerificationCode();
        //查找60秒内发送的验证码
        $issend = VerificationCode::getLast($phone,1);
        if(!empty($issend)){
            return $this->error('验证码已发送，请60秒后再操作');
        }
        $sms = new Sms();
        $rand = mt_rand(1000,9999);
        $sendParams = [$rand,5];
        $sms = $sms->sendSingle($phone,127656,$sendParams);
        //如果发送成功
        if($sms == true){
            $code->user = $phone;
            $code->code = $rand;
            $code->type = 1;
            $code->save();
            return $this->success('验证码发送成功，五分钟内有效',$phone);
        }else{
            return $this->error('验证码发送失败，请稍后再试',$sms);
        }
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        // 验证规则，使用手机号码登录
        $rules = [
            'phone'   => 'required|string|size:11',
            'name'   => 'required|string|min:6|max:20',
            'password' => 'required|string|min:6|max:20',
            'code' => 'required|string|size:4',
        ];

        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $this->validate($request, $rules, $this->messages);

        //查找60秒内发送的验证码
        $phone = $request->phone;
        $code = DB::table('verification_codes')
            ->where([['user','=',$phone],['type','=',1],['created_at','>',date('Y-m-d H:i:s',time()-300)]])
            ->orderBy('id', 'desc')
            ->first();
        if(empty($code->code) || $code->code != $request->code){
            return $this->error('验证码错误');
        }
        DB::beginTransaction();
        try {
            //创建新用户
            $user = new User;
            $user->phone = $request->phone;
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            $user->status = 1;
            $user_id = $user->save();
            //分配port
            $last_port = DB::table('ports')->orderBy('id', 'desc')->value('port');
            $send_port = [
                "server_port"   =>  empty($last_port)?9526:$last_port,
                "password"  =>  randomString()
            ];
            $add_port = true;
            $try_times = 0;
            while(!$add_port){
                if($try_times >= 3){
                    throw new Exception('添加端口失败');
                }
                $send_port['server_port'] ++;
                $add_port = SsServer::send('add',$send_port);
                $try_times ++;
            }
            //将port添加到数据库
            $port = new Port();
            $port->user_id = $user_id;
            $port->port = $send_port['server_port'];
            $port->password = $send_port['password'];
            $port->flow = 5000;
            $port->status = 1;
            $port->save();
            DB::commit();
            return $this->success('注册成功');
        }catch (Exception $e){
            DB::rollback();
            return $this->error('注册失败，请稍后重试',$e->getMessage());
        }finally{
            //删除已使用的验证码
            DB::table('verification_codes')->where('id', '=', $code->id)->delete();
        }
    }

    /**
     * Get a JWT token via given credentials.
     * @param Request $request
     */
    public function login(Request $request)
    {
        // 验证规则，由于业务需求，这里我更改了一下登录的用户名，使用手机号码登录
        $rules = [
            'phone'   => [
                'required',
                'size:11',
                'exists:users',
            ],
            'password' => 'required|string|min:6|max:20',
        ];
        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules, $this->messages);
        $params['status'] = 1;
        // 使用 Auth 登录用户，如果登录成功，则返回 201 的 code 和 token，如果登录失败则返回
        if($token = Auth::guard('api')->attempt($params)){
            return $this->success('success','bearer ' . $token,201);
        }else{
            return $this->error('error');
        }
    }

    /**
     * 处理用户登出逻辑
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->success('退出成功');
    }
}
