<?php
/**
 * Created by PhpStorm.
 * User: Wongluyu
 * Date: 2018/4/18
 * Time: 23:29
 */

namespace App\Http\Controllers\Api\Helpers;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Response;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $message
     * @param array $data
     * @return mixed
     */
    public function response($message='', $data = [],$code='')
    {
        $response = array(
            'code'=> empty($code)?$this->getStatusCode():$code,
            'message'=>$message,
            'data'=>$data
        );
        return Response::json($response,$response['code']);
    }

    /**
     * @param $message
     * @param array $data
     * @return mixed
     */
    public function error($message = 'error', $data = [],$code = FoundationResponse::HTTP_BAD_REQUEST){
        return $this->setStatusCode($code)->response($message,$data);
    }

    /**
     * @param string $message
     * @param array $data
     * @return mixed
     */
    public function success($message = 'success',$data = [],$code= FoundationResponse::HTTP_OK){
        return $this->setStatusCode($code)->response($message,$data);
    }

    public function successData($data = []){
        return $this->success('success',$data);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Page Not Fond!'){
        return $this->setStatusCode(Foundationresponse::HTTP_NOT_FOUND)->response($message);
    }
}