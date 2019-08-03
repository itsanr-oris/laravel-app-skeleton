<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 9:52 AM
 */

namespace Foris\LaExtension;

use ReflectionClass;

/**
 * 组件服务类
 *
 * @package Foris\LaExtension
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Component
{
    /**
     * 扫描目录文件
     *
     * @param string $dir
     * @return array
     */
    public static function scanFiles(string $dir = '')
    {
        $files = [];
        $fds   = scandir($dir);
        unset($fds[array_search('.', $fds, true)]);
        unset($fds[array_search('..', $fds, true)]);

        foreach ($fds as $fd) {
            $path  = $dir . '/' . $fd;
            $farr  = is_dir($path) ? static::scanFiles($path) : [$path];
            $files = array_merge($files, $farr);
        }

        return $files;
    }

    /**
     * 发现并注册组件
     *
     * @param string $dir
     * @param array  $expect
     * @return void
     */
    public static function discover(string $dir = '', $expect = [])
    {
        $expect = array_merge(['.', '..'], $expect);

        collect(static::scanFiles(app_path($dir)))->diff($expect)->each(function ($item) {
            // 将扫描出来的路径转换为完整类名
            $path  = base_path() . '/';
            $class = ucfirst(str_replace([$path, '.php', '/'], ['', '', '\\'], $item));

            // 检查类是否可以实例化，是否存在register方法，注册绑定实例
            $reflect = new ReflectionClass($class);
            $reflect->isInstantiable() && $reflect->hasMethod('register') && call_user_func_array([$class, 'register'], []);
        });
    }
}