<?php

namespace App;

use Illuminate\Support\Facades\DB;

class VerificationCode extends BaseModel
{
    protected $table = 'verification_codes';
    protected $fillable = [
        'user', 'code', 'type',
    ];

    public static function getLast($user,$type,$time = 60){
        if(!empty($user) && !empty($type)){
            return DB::table('verification_codes')
                ->where([['user','=',$user],['type','=',$type],['created_at','>',date('Y-m-d H:i:s',time()-$time)]])
                ->orderBy('id', 'desc')
                ->first();
        }
        return false;
    }


}
