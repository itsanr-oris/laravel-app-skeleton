## Author

    作者：F.oris
    邮箱：us@f-oris.me

## 简介

&emsp;&emsp;自用laravel基础应用程序扩展包，主要用于前后端分离项目，前后端接口数据格式为json格式，已实现curd基础接口模板代码，可通过artisan命令快速构建crud接口

## 扩展说明

- [x] 增加repository、service, model层级结构，增加repository、service创建指令
- [x] 修改laravel原有model、controller指令resource参数，适配repository、service代码分层结构
- [x] 在laravel原有response基础上新增自定义response封装类，用于接口响应数据格式化、标准化处理
- [x] 扩展laravel exception handler，增加ErrorException，BusinessException用于区别错误异常以及针对不同异常的响应结果处理

## 通过Composer安装

composer require "f-oris/laravel-app-skeleton:~1.0"

## publish 配置文件

php artisan vendor:publish --provider="Foris\LaExtension\ServiceProvider"

## artisan 命令说明

- php artisan make:service 创建业务逻辑层服务类
- php artisan make:repository 创建数据存储层服务类
- php artisan make:model 修改laravel官方make:model指令中resource参数，适配repository、service代码分层结构
- php artisan make:controller 扩展laravel官方make:controller指令中resource参数，适配repository、service代码分层结构

## 代码目录说明

通过上述的artisan命令创建相应的代码文件后，原laravel代码目录结构将新增以下几个文件目录，目录位置可通过配置文件进行修改，具体说明如下
- app\Models 数据模型层，ORM
- app\Services 业务逻辑层，主要负责数据加工处理相关逻辑,对数据进行缓存控制
- app\Repositories 数据存储层，主要负责数据存储相关逻辑

#### 代码执行顺序

用户请求 -> 逻辑层 -> 数据存储层 -> 数据模型层

## 快速构建curd接口

1. 执行指令：php artisan make:model Resource -mr
2. 找到上述指令创建的迁移文件，完善表结构，执行指令：php artisan migrate
3. 打开路由文件，增加路由：Route::resource('resource', 'ResourceController');
4. 通过客户端访问相关路由接口，测试基础crud流程是否通畅
5. 根据业务需求情况，完善相关代码

## 组件容器注册说明

通过预设的artisan命令创建的repository组件以及service组件，底层均使用了一个ComponentRegister的Trait，该Trait定义了一个组件的容器注册方法（register）以及注册到容器内所绑定的容器服务名称(name)，系统在启动时，会根据配置文件中的'scan_path'扫描相应的组件目录，如果扫描到的组件类中存在register方法，并且该组件类可以实例化，即会将该组件注册绑定到laravel服务容器内。默认组件名称是通过类名转化处理所得，组件实例化的方式为普通bind的方式。如果需要修改某一个组件的服务绑定名称以及注册方法，则在类内部重写name方法已经register方法即可