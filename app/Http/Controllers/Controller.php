<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends AuthBaseController
{
    public $user;
    public $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->user_id = $this->user['id'];
    }
}
