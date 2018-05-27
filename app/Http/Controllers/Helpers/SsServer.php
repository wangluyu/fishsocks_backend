<?php
/**
 * Created by PhpStorm.
 * User: Wongluyu
 * Date: 2018/5/26
 * Time: 17:59
 */

namespace App\Http\Controllers\Helpers;


class SsServer
{
    public static function send($command,$in){
        $flag = false;
        switch ($command){
            case 'add':
                if(empty($in) || !is_array($in) || !isset($in['server_port']) || empty($in['server_port']) || !isset($in['password']) || empty($in['password'])){
                    return false;
                }
                break;
            case 'remove':
                if(empty($in) || !is_array($in) || !isset($in['server_port']) || empty($in['server_port'])){
                    return false;
                }
                break;
            default:
                return false;
        }
        error_reporting(E_ALL);
        set_time_limit(0);

        //创建socket
        if(($socket = socket_create(AF_UNIX,SOCK_DGRAM,0)) < 0) {
            return false;
//            echo "socket_create() error:".socket_strerror($socket)."\n";
//            exit();
        }
        //给套接字绑定名字
        $CLIENT_ADDRESS = '/tmp/ss/ssadd'.randomString().'.sock';
        unlink($CLIENT_ADDRESS);
        if(($sock = socket_bind($socket, $CLIENT_ADDRESS))<0) {
            return false;
//            echo "socket_bind() error:".socket_strerror($sock)."\n";
//            exit();
        }

        //连接socket
        $SERVER_ADDRESS = '/var/run/shadowsocks-manager.sock';
        if(($sock = socket_connect($socket, $SERVER_ADDRESS)) < 0){
            return false;
//            echo "socket_connect() error:".socket_strerror($sock)."\n";
//            exit();
        }

        $in = $command.': '.json_encode($in);

        //写数据到socket缓存
        if(($sock = socket_write($socket, $in, strlen($in)))<0) {
            return false;
//            echo "socket_write() error:".socket_strerror($sock)."\n";
//            exit();
        }
        //接收信息
        if($out = socket_read($socket, 2048)) {
            if ($out == 'ok'){
                $flag = true;
            }
        }
        socket_close($socket);
        unlink($CLIENT_ADDRESS);
        return $flag;
    }
}