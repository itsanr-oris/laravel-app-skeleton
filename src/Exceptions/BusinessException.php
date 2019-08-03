<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 4:42 PM
 */

namespace Foris\LaExtension\Exceptions;

use Foris\LaExtension\Http\Response;

/**
 * 业务异常
 *
 * @package Foris\LaExtension\Exceptions
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class BusinessException extends BaseException
{
    /**
     * 构建异常响应结果
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return Response::failure($this->getMessage(), $this->getData(), $this->getCode());
    }
}