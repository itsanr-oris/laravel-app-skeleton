<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 9:52 AM
 */

namespace Foris\LaExtension\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * 服务自动注册
 *
 * @package Foris\LaExtension\Di
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
trait ComponentRegister
{
    /**
     * 服务注册
     */
    public static function register()
    {
        app()->bind(static::name(), function () {
            return new static();
        });
    }

    /**
     * 获取服务名称
     *
     * App\Services\TestService => test.service
     * App\Services\TestAbcService => test-abc.service
     * App\Services\Test\AbcService => test.abc.service
     *
     * @return     string  服务名称
     */
    public static function name()
    {
        $classSegment = explode('\\', static::class);
        $className    = end($classSegment);
        $nameSegment  =  Arr::except($classSegment, [0, 1, count($classSegment) - 1]);

        // 驼峰转中折线
        $snakeClassName    = Str::snake($className, '-');
        $snakeClassSegment = explode('-', $snakeClassName);

        // 将类名重组进服务名称
        $nameSegment[] = implode('-', Arr::except($snakeClassSegment, [count($snakeClassSegment) - 1]));
        $suffixSegment = explode('\\', Str::snake(self::class));
        $nameSegment[] = end($suffixSegment);

        return config('app-ext.component.name_prefix') . strtolower(implode('.', $nameSegment));
    }
}