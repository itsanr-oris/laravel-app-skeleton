<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 9:23 AM
 */

namespace Foris\LaExtension;

use Foris\LaExtension\Console\Command\MakeController;
use Foris\LaExtension\Console\Command\MakeModel;
use Foris\LaExtension\Console\Command\MakeRepository;
use Foris\LaExtension\Console\Command\MakeService;
use Foris\LaExtension\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider as LaServiceProvider;

/**
 * 服务提供类
 *
 * @package Foris\LaExtension
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ServiceProvider extends LaServiceProvider
{
    /**
     * 执行服务注册时启动。
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/app-ext.php' => config_path('app-ext.php'),
        ]);

        if ($this->app->runningInConsole()) {
            // 覆盖原来的model创建命令
            $this->app->extend('command.model.make', function ($command, $app) {
                return new MakeModel($command, $app['files']);
            });

            // 覆盖原来的控制器创建命令
            $this->app->extend('command.controller.make', function ($command, $app) {
                return new MakeController($command, $app['files']);
            });

            $this->app->singleton('command.service.make', function ($app) {
                return new MakeService($app['files']);
            });

            $this->app->singleton('command.repository.make', function ($app) {
                return new MakeRepository($app['files']);
            });

            $this->commands(['command.service.make', 'command.repository.make']);
        }
    }

    /**
     * 在容器中注册绑定相关服务
     *
     * @return void
     */
    public function register()
    {
        $this->extendExceptionHandler();
        $this->registerComponent();
    }

    /**
     * 扩展异常处理类
     *
     * @return void
     */
    public function extendExceptionHandler()
    {
        if (config('app-ext.handle_exception')) {
            $this->app->extend(ExceptionHandler::class, function ($handler, $app) {
                return new Handler($handler, $app);
            });
        }
    }

    /**
     * 注册组件
     *
     * @return void
     */
    public function registerComponent()
    {
        foreach (config('app-ext.component.scan_path', []) as $path) {
            is_dir(app_path($path)) && Component::discover($path);
        }
    }
}