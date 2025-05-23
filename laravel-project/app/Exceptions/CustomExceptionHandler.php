<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CustomExceptionHandler extends ExceptionHandler
{

    /**
     * カスタムエラーを表示するエラーコード
     * @var array
     */
    private array $_custom_error_code = [

    ];

    /**
     * 検証用に上書き
     * @param mixed $request
     * @param \Throwable $e
     * @return mixed|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {

        if ($this->isHttpException($e)) {
            \Log::debug('HttpException caught in render()');
            return $this->renderHttpException($e);
        }

        \Log::debug('Non-HttpException caught in render()');

        return parent::render($request, $e);
    }
    
    /**
     * httpエラー発生時の処理をオーバーライド
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     * @return mixed|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @todo エラーコード毎にviewを用意
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $status = $e->getStatusCode();
        $viewPath = $this->getErrorViewPath($status);

        // カスタムエラーページを用意している場合のみ
        if (view()->exists($viewPath)) {
            return response()->view($viewPath, [
                'exception' => $e,
            ], $status, $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }

    protected function getErrorViewPath(int $statusCode): string
    {
        $prefix = request()->is('admin/*') || (auth()->check() && auth()->user()->is_admin)
            ? 'errors.admin'
            : 'errors.user';

        return "{$prefix}.{$statusCode}";
    }

}
