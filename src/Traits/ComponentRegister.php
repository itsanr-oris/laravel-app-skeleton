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
        $floderSegment =  Arr::except($classSegment, [0, 1, count($classSegment) - 1]);

        $nameSegment= [];
        foreach ($floderSegment as $floderName) {
            $nameSegment[] = static::segment($floderName);
        }

        $nameSegment[] = static::segment(end($classSegment), true);

        return config('app-ext.component.name_prefix') . strtolower(implode('.', $nameSegment));
    }

    /**
     * Convert segment name to snake name.
     *
     * @param      $name
     * @param bool $last
     */
    protected static function segment($name, $last = false)
    {
        if (!$last) {
            return strtolower(Str::snake($name, '-'));
        }

        $snakesegment = explode('-', Str::snake($name, '-'));
        $nameSegment[] = implode('-', Arr::except($snakesegment, [count($snakesegment) - 1]));
        $nameSegment[] = end($snakesegment);

        return strtolower(implode('.', $nameSegment));
    }
}
