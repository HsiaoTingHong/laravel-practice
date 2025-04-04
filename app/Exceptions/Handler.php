<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponseTrait; // 引用特徵
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponseTrait; // 使用特徵，類似將Trait撰寫的方法貼到這個類別中

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // 讓它顯示一下是哪一個異常狀況
        // dd($exception);
        // dd看到是ModelNotFoundException錯誤

        // 錯誤處理
        if ($request->expectsJson()) {
            // 1. ModelNotFoundException 找不到資源
            if ($exception instanceof ModelNotFoundException) {
                // 呼叫 errorResponse 方法
                return $this->errorResponse(
                    '找不到這筆資料',
                    Response::HTTP_NOT_FOUND
                );
            }
            // 2. NotFoundHttpException 網址輸入錯誤
            if ($exception instanceof NotFoundHttpException) {
                // 呼叫 errorResponse 方法
                return $this->errorResponse(
                    '無法找到此網址',
                    Response::HTTP_NOT_FOUND
                );
            }
            // 3. MethodNotAllowedHttpException 網址不允許該請求動詞
            if ($exception instanceof MethodNotAllowedHttpException) {
                // 呼叫 errorResponse 方法
                return $this->errorResponse(
                    $exception->getMessage(), // 回傳例外內的訊息
                    Response::HTTP_METHOD_NOT_ALLOWED
                );
            }
        }

        return parent::render($request, $exception);
    }
}
