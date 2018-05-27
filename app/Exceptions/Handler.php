<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\Helpers\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        // 参数验证错误的异常，我们需要返回 400 的 http code 和一句错误信息
        if ($exception instanceof ValidationException) {
            return $this->error(array_first(array_collapse($exception->errors())));
        }elseif ($exception instanceof UnauthorizedHttpException || $exception instanceof TokenInvalidException) {
            // 用户认证的异常，我们需要返回 401 的 http code 和错误信息

            return $this->error($exception->getMessage(),[],401);
        }elseif ($exception instanceof MethodNotAllowedHttpException){
            return $this->error($exception->getMessage(),[],405);
        }elseif ($exception instanceof NotFoundHttpException){
            return $this->notFond();
        }else{
//            return $this->error($exception->getMessage(),[],400);
            return parent::render($request, $exception);
        }
    }
}
