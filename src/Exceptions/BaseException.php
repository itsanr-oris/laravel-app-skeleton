<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 4:09 PM
 */

namespace Foris\LaExtension\Exceptions;

use Foris\LaExtension\Http\Response;
use Log;
use Throwable;

/**
 * Class Exception
 * @package Foris\LaExtension\Exceptions
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class BaseException extends \Exception
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Exception constructor.
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @param array          $data
     */
    public function __construct(
        string $message = "",
        int $code = 1,
        Throwable $previous = null,
        $data = []
    ) {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     * 设置异常数据
     *
     * @param array $data
     * @return $this
     */
    public function setData($data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 获取异常数据
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 异常信息转换为请求响应结果
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return Response::exception($this->getMessage(), $this->getData(), $this->getCode());
    }

    /**
     * 自定义report
     *
     * @return void
     */
    public function report()
    {
        $context = [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => $this->data,
            'request' => [
                'url' => request()->url(),
                'input' => request()->input(),
                'ip' => request()->getClientIp(),
            ]
        ];

        Log::error($this->getMessage(), $context);
    }
}