<?php

namespace App;

use App\Http\Controllers\Helpers\helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Port extends Model
{

    public static function getByUser($user_id)
    {
        $ss = array();
        $port =  DB::table('users')
            ->leftJoin('ports', function ($join) {
                $join->on('users.id', '=', 'ports.user_id');
            })
            ->leftJoin('flows', function ($join) {
                $join->on('ports.id', '=', 'flows.port_id')
                ->where(array(array('type','=','1'),array('month','=',date('Ym'))));
            })
            ->select('ports.id as port_id','ports.ip','ports.status','ports.password',DB::raw('sum(flows.flow) as total_flow'),'ports.port')
            ->where('users.id','=',$user_id)
            ->groupBy('ports.port')
            ->get()->toArray();
        if(!empty($port)){
            foreach ($port as $p){
                $p->left_flow = $p->total_flow - Redis::hget('flow_'.$p->port,date('Y-m'));
                $p->left_flow = helper::byteToMB($p->left_flow);
                $p->total_flow = helper::byteToMB($p->total_flow);
                $ss[] = $p;
            }
        }
        return $ss;
    }

}
